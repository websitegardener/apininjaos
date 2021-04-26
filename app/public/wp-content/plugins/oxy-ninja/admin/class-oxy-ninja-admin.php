<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://oxyninja.com
 * @since      3.1.0
 *
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/admin
 * @author     OxyNinja <hello@oxyninja.com>
 */
class Oxy_Ninja_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    3.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, OXYNINJA_URI . '/css/oxy-ninja-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    3.1.0
	 */
	public function enqueue_scripts() {

		$bkais = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) ) ?? false;
		$core = get_option('oxygen_vsb_source_sites')['core']['accesskey'] ?? 0;
		$woocore = get_option('oxygen_vsb_source_sites')['woocore']['accesskey'] ?? 0;
		$otb = class_exists( 'OXY_Toolbox_Plugin_Updater' ) ? 'active' : FALSE;
		
		if (defined('SHOW_CT_BUILDER') && $bkais) {
			wp_enqueue_script( 'jquery-ui-autocomplete', false, ['jquery']);
			wp_enqueue_script(
				'oxyNinjaKlazzy',
				OXYNINJA_URI . '/js/oxy-ninja-klazzy.js',
				[],
				$this->version,
				true
			);
			wp_enqueue_script(
				'axios',
				OXYNINJA_URI . '/js/axios/axios.min.js',
				[],
				"0.21.1",
				false
			);
			wp_enqueue_script(
				'sweetAlert2',
				OXYNINJA_URI . '/js/sweetalert2.all.min.js',
				[],
				"10.13.3",
				false
			);
			wp_enqueue_script(
				'oxyNinjaAdmin',
				OXYNINJA_URI . '/js/oxy-ninja-admin.js',
				[],
				$this->version,
				true
			);
			wp_enqueue_script(
				'oxyNinjaConnection',
				OXYNINJA_URI . '/js/oxy-ninja-connection.js',
				[],
				$this->version,
				true
			);
			wp_localize_script("oxyNinjaConnection", 'konnektor', [
				'core' => !empty($core) ? TRUE : FALSE,
				'woocore' => !empty($woocore) ? TRUE : FALSE,
				'lon' => get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) === base64_decode('dmFsaWQ=') ? TRUE : FALSE,
				'third' => get_option('oxygen_vsb_enable_3rdp_designsets') ? TRUE : FALSE,
				'ajax_url' => admin_url('admin-ajax.php'),
				'oxyNinjaFirstRun' => FALSE,
				'oxyNinjaOnlyStyleSheets' => FALSE,
				'otb' => $otb
			]);
		}

	}

	/**
	 * Register the JavaScript for the Oxygen Iframe area.
	 *
	 * @since    3.1.0
	 */
	public function core_oxy_ninja_iframe() {

		$nasif = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) ) ?? false;

		if (defined('SHOW_CT_BUILDER') && $nasif === base64_decode('dmFsaWQ=')) {
			wp_enqueue_script(
				'oxyNinjaIframe',
				OXYNINJA_URI . '/js/oxy-ninja-iframe.js',
				[],
				$this->version,
				true
			);
		 	wp_enqueue_script('splide');
			wp_enqueue_style('splide');
		}

	}

	/**
	 * Add license isset and only when path is helpers !
	 *
	 * @since    3.1.0
	 */
	public static function ct_new_api_remote_get_on($source, $path = '', $licenses) {

		if (empty($path)) {
			return false;
		}
	
		$args = [
			'headers' => [
				'oxygenclientversion' => '2.1+',
				'eddlicense' => $licenses,
			],
			'timeout' => 15,
		];
	
		// figure out the access key
		$accessKey = '';
		$site = ct_find_source_site_from_url($source);
	
		if (is_array($site)) {
			if (!isset($site['system']) || $site['system'] !== true) {
				$accessKey = md5($site['accesskey']);
			} else {
				$accessKey = false;
			}
		}
	
		if ($accessKey !== false) {
			$args['headers']['auth'] = $accessKey;
		}
	
		$result = wp_remote_request(
			$source . '/wp-json/oxygen-vsb-connection/v1/' . $path,
			$args
		);
	
		$status = wp_remote_retrieve_response_code($result);
	
		if (is_wp_error($result)) {
			return json_encode([
				'error' => $result->get_error_message(),
			]);
			die();
		} elseif ($status !== 200) {
			return json_encode([
				'error' => wp_remote_retrieve_response_message($result),
			]);
			die();
		}
	
		if (is_array($result)) {
			return $result['body'];
		}
	
		return false;

	}

	/**
	 * API Security Check - Taken from Oxygen Builder
	 *
	 * @since    3.3.0
	 */
	private function ct_ns_api_call_security_check($call_type) {

		$failure = false;
	
		$nonce  	= $_REQUEST['nonce'];
		$post_id 	= $_REQUEST['post_id'];

		// check nonce
		if ($call_type === 'oxyninja') {
			if ( ! wp_verify_nonce( $nonce, 'oxygen-nonce-' . $post_id ) ) {
				$failure = true;
		}
		}

		if ($call_type === 'lizzy' || $call_type === 'lizzy_remove') {
			if ( ! wp_verify_nonce( $nonce, 'oxyninja_lizzer_lizon' ) ) {
				$failure = true;
			}
		}
	
		if (!oxygen_vsb_current_user_can_access()) {
			$failure = true;
		}
	
		if($failure) {
			wp_send_json_error();
		}
	
	}

	/**
	 * Add license isset and only when path is helpers !
	 *
	 * @since    3.1.0
	 */
	public function oxy_ninja_api_call() {
		
		$call_type = isset($_REQUEST['call_type'])
    ? sanitize_text_field($_REQUEST['call_type'])
		: false;
		
		$this->ct_ns_api_call_security_check($call_type);
		
		if ($call_type === 'oxyninja') {
			$this->ct_get_oxyninja_json();
		}

		if ($call_type === 'lizzy') {
			$this->oxyninja_react_activate();
		}

		if ($call_type === 'lizzy_remove') {
			$this->oxyninja_react_deactivate();
		}
	
		die();

	}

	/**
	 * Add license isset and only when path is helpers !
	 *
	 * @since    3.1.0
	 */
	protected function ct_get_oxyninja_json() {

		global $ct_source_sites;

		$name = isset($_REQUEST['name'])
			? sanitize_text_field($_REQUEST['name'])
			: false;
		$type = isset($_REQUEST['type'])
			? sanitize_text_field($_REQUEST['type'])
			: false;

		$skvarkos = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9rZXk=') ) );
	
		if (isset($ct_source_sites[$name]) && !empty($type)) {
			$result = $this->ct_new_api_remote_get_on(
				$ct_source_sites[$name]['url'],
				$type . '/', $skvarkos
			);
			echo $result;
		}
	
		die();

	}

	/**
	 * License Activation API
	 *
	 * @since    3.3.0
	 */
	protected function oxyninja_react_activate() {

		$license = isset($_REQUEST['license'])
		? sanitize_text_field($_REQUEST['license'])
		: false;
	
		$user = wp_get_current_user();
		delete_transient('oxygen-token-check-user-' . $user->ID);
	
		update_option( 'oxyninja_license_key', trim( $license ) );
	
		// retrieve the license from the database
		$license = trim( get_option( 'oxyninja_license_key' ) );
	
		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( "OxyNinja Plugin" ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, 'https://oxyninja.com' ), array( 'timeout' => 15, 'sslverify' => true ) );
	
		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;
	
		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
		// if valid license update the hash
		if ( isset( $license_data->site_hash ) && $license_data->license == "valid" ) {
			update_option( 'oxyninja_license_site_hash', $license_data->site_hash );
		}
	
		update_option( 'oxyninja_license_status', $license_data->license );
	
		die();
	}

	/**
	 * License Deactivation API
	 *
	 * @since    3.3.0
	 */
	protected function oxyninja_react_deactivate() {

		// retrieve the license from the database
		$license = trim( get_option( 'oxyninja_license_key' ) );

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( "OxyNinja Plugin" ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, 'https://oxyninja.com' ), array( 'timeout' => 15, 'sslverify' => true ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'oxyninja_license_status' );
			delete_option( 'oxyninja_license_key' );

		die();
	}

	/**
	 * Register the JavaScript for the settings area.
	 *
	 * @since    3.3.0
	 */
	public function oxyninja_settings_assets() {
		wp_register_script(
			'oxyninja-settings',
			OXYNINJA_URI . '/js/settings.js',
			array( 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ),
			$this->version,
			true
		);
		wp_register_style( 'oxyninja-settings', OXYNINJA_URI . '/css/settings.css', array( 'wp-components' ), $this->version);
		wp_localize_script('oxyninja-settings', 'lizzo', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' =>  wp_create_nonce( 'oxyninja_lizzer_lizon' )
		));
	}

	/**
	 * Register the Options
	 *
	 * @since    3.3.0
	 */
	public function oxyninja_register_options() {
		register_setting(
			'oxyninja_settings',
			'oxyninja_license_key',
			array(
				'type' => 'string',
				'show_in_rest' => true,
			)
		);
	
		register_setting(
			'oxyninja_settings',
			'oxyninja_license_status',
			array(
				'type' => 'string',
				'show_in_rest' => true,
			)
		);

		register_setting(
			'oxyninja_settings',
			'oxyninja_storage',
			array(
				'show_in_rest' => array(
					'schema' => array(
							'type'       => 'object',
							'properties' => array(
									'id' => array(
										'type' => 'boolean',
										'default' => false,
									),
									'badge' => array(
										'type' => 'boolean',
										'default' => false,
									),
							)
					),
			),
			)
		);
	}

	/**
	 * OxyNinja Settings
	 *
	 * @since    3.3.0
	 */
	public function oxyninja_settings() {
		wp_enqueue_style( 'oxyninja-settings' );
		wp_enqueue_script( 'oxyninja-settings' );
		echo '<div id="oxyninja"></div>';
	}

	/**
	 * SubPage WP MENU
	 *
	 * @since    3.3.0
	 */
	public function oxyninja_settings_menu() {

		// $oxyninja_hook_suffix = add_options_page( 'OxyNinja', 'OxyNinja', 'manage_options', 'oxyninja', array( $this, 'oxyninja_settings'), 99);

		// $oxyninja_hook_suffix = add_menu_page( "OxyNinja", "OxyNinja", 'manage_options', 'oxyninja', array( $this, 'oxyninja_settings'), 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA5NS4xOCAxMTQuMDMiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojZjJmMmYyO30uY2xzLTJ7ZmlsbDojZTZlNmU2O30uY2xzLTN7ZmlsbDojMzMzO308L3N0eWxlPjwvZGVmcz48ZyBpZD0iVnJzdHZhXzIiIGRhdGEtbmFtZT0iVnJzdHZhIDIiPjxnIGlkPSJMYXllcl8xIiBkYXRhLW5hbWU9IkxheWVyIDEiPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTQ3LjU5LDE4Ljg1QTQ3LjU5LDQ3LjU5LDAsMSwwLDk1LjE4LDY2LjQ0LDQ3LjU5LDQ3LjU5LDAsMCwwLDQ3LjU5LDE4Ljg1WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTk1LjE4LDY2LjQ0QTQ3LjYxLDQ3LjYxLDAsMCwwLDYxLDIwLjc3Qzc3Ljk0LDUwLjMyLDQ2Ljc2LDYyLjk0LDQ2Ljc2LDYyLjk0UzYzLjU5LDc0LjU3LDU0LjUyLDk0LjJjNS42OSw2LjI3LDIuMDYsMTguMjMtMS42MSwxOS41M0E0Ny41OSw0Ny41OSwwLDAsMCw5NS4xOCw2Ni40NFoiLz48cGF0aCBjbGFzcz0iY2xzLTMiIGQ9Ik00Ny42Niw2Ny4zQTU3Ljc0LDU3Ljc0LDAsMCwxLDEyLDU1VjkxLjkxaDcxLjNWNTVBNTcuNyw1Ny43LDAsMCwxLDQ3LjY2LDY3LjNaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjEuMjksNzIuMzVDMTkuNDcsNzIuMzUsMTgsNzQuNDQsMTgsNzdzMS40Nyw0LjY3LDMuMjksNC42N1MyNC41Nyw3OS42LDI0LjU3LDc3LDIzLjEsNzIuMzUsMjEuMjksNzIuMzVaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNTAuNDcsMjEuNTNsMjQtMTEuOHM0LDUuNzQtNS43OCwxNGMwLDAtNi45Miw0LjgyLTcuMywzLjIxQzYwLjY4LDIzLjg1LDUwLjQ3LDIxLjUzLDUwLjQ3LDIxLjUzWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTU5LjQ3LDQuMjksNDQuMiwyMC4xN3MzLjcyLDEuOTQsMTEuMzEuMjlTNjIuNzcsNi4yNyw1OS40Nyw0LjI5WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTU5LjcxLDBsLTE3LDIyTDU5LjQyLDQuN1M2MS4yLDEyLjc5LDU4Ljg5LDE4bDE0LjI3LTYuMXMxLjMyLDkuMjQtMTcuNDEsMTdjMCwwLDguNzItLjI5LDE0LjU2LTQuOTVDNzcuNDUsMTguMjMsNzYuNzksMTIsNzYsOEw2Mi43NywxNC4xMVM2My44NCw0LjYyLDU5LjcxLDBaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNzQuNzYsNzIuMzVjLTEuODEsMC0zLjI5LDIuMDktMy4yOSw0LjY3czEuNDgsNC42NywzLjI5LDQuNjdTNzguMDUsNzkuNiw3OC4wNSw3Nyw3Ni41OCw3Mi4zNSw3NC43Niw3Mi4zNVoiLz48L2c+PC9nPjwvc3ZnPg==', 1313 );

		// add_action( "load-{$oxyninja_hook_suffix}", array($this, 'oxyninja_settings_assets'));
	}
}