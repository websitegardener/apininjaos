<?php

/**
 *
 * @link              https://oxyninja.com
 * @since             3.1.0
 * @package           Oxy_Ninja
 *
 * Plugin Name:       OxyNinja
 * Plugin URI:        https://oxyninja.com
 * Description:       If you can build it with Oxygen, you can do it twice as fast with OxyNinja.
 * Version:           3.3.3
 * Author:            OxyNinja
 * Author URI:        https://oxyninja.com
 * License:           EULA + GNU General Public License v3.0
 * License URI:       https://oxyninja.com/eula/
 * Text Domain:       oxy-ninja
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('OXYNINJA_VERSION', '3.3.3');
define('OXYNINJA_MAIN', plugin_dir_path(__FILE__));
define('OXYNINJA_URI', plugin_dir_url(__FILE__) . "admin");
define('OXYNINJA_URI_PUBLIC', plugin_dir_url(__FILE__) . "public");
define('OXYNINJA_URI_ELEMENTS', plugin_dir_url(__FILE__) . "elements");
define('OXYNINJA_STORE_URL', 'https://oxyninja.com');
define('OXYNINJA_ITEM_ID', 10606);
define('OXYNINJA_LICENSE_PAGE', 'oxyninja');
define('OXYNINJA_ITEM_NAME', 'OxyNinja Plugin');
define('OXYNINJA_PLUGIN_FILE', __FILE__);

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oxy-ninja.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.1.0
 */
function run_oxy_ninja() {

	$plugin = new Oxy_Ninja();
	$plugin->run();

}
run_oxy_ninja();

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

// Taken from Oxygen WooCommerce Element
Class OxyNinjaUpdater {

	public $oxyninja_url 	= "https://oxyninja.com";
	
	/**
	 * Add the actions in the constructor
	 * 
	 * @since 1.0
	 */

	function __construct( $args ) {

		$this->prefix 		= $args["prefix"];
		$this->plugin_name 	= $args["plugin_name"]; // should be exact as EDD item name
		$this->priority 	= $args["priority"];
		$this->license_text = (isset($args["license_text"])) ? $args["license_text"] : __('Enter your license key to get updates', 'component-theme');

		add_action( 'admin_init', array( $this, 'init'), 0 );
		add_action( 'admin_init', array( $this, 'activate_license' ) );
		add_action( 'oxygen_license_admin_screen', array( $this, 'license_screen' ), $this->priority );
	}

	
	/**
	 * Initialize EDD_SL_Plugin_Updater class
	 * 
	 * @since 1.0
	 */

	function init() {

		// retrieve our license key from the DB
		$license_key = trim( get_option( $this->prefix . 'license_key' ) );

		// setup the updater
		$edd_updater = new EDD_SL_Plugin_Updater( 
			$this->oxyninja_url, 
			OXYNINJA_PLUGIN_FILE, // main plugin file, specify for each add-on
			array( 
				'version' 	=> OXYNINJA_VERSION,
				'license' 	=> $license_key,
				'item_name' => $this->plugin_name,
				'item_id' => OXYNINJA_ITEM_ID, 
				'author'  => 'OxyNinja',
				'beta'    => false,
			)
		);
	}


	/**
	 * License screen HTML output
	 * 
	 * @since 1.0
	 */

	function license_screen() {

		$license 	= get_option( $this->prefix . 'license_key' );
		$status 	= get_option( $this->prefix . 'license_status' );

		if ($license!="") {
			$type = "password";
		}
		else {
			$type = "text";
		}

		?>
		<div class="oxygen-license-wrap <?php echo $this->prefix . 'license-wrap'; ?>">
			<h2><?php echo $this->plugin_name; ?></h2>
			<form method="post" action="">
			
				<?php wp_nonce_field( $this->prefix . 'submit_license', $this->prefix . 'license_nonce_field' ); ?>
				
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<td>
								<input id="<?php echo $this->prefix; ?>license_key" name="<?php echo $this->prefix; ?>license_key" type="<?php echo $type; ?>" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
								<label for="<?php echo $this->prefix; ?>license_key"><?php echo $status; ?></label>
								<p class="description"><?php echo $this->license_text ?></p>
							</td>
						</tr>
					</tbody>
				</table>	
				<?php submit_button( __("Submit","oxygen"), "primary", $this->prefix."submit_license" ); ?>
			
			</form>
		</div>
		<?php
	}


	/**
	 * Send license key to oxyninja.com EDD to activate license
	 * 
	 * @since 1.0
	 */

	function activate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST[$this->prefix."submit_license"] ) ) {

			$user = wp_get_current_user();
			delete_transient('oxygen-token-check-user-' . $user->ID);

			// run a quick security check 
		 	if( ! wp_verify_nonce( $_POST[$this->prefix . 'license_nonce_field'], $this->prefix . 'submit_license' ) ) 	
				return;

			update_option( $this->prefix . 'license_key', trim( $_POST[$this->prefix . 'license_key'] ) );

			// retrieve the license from the database
			$license = trim( get_option( $this->prefix . 'license_key' ) );

			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'activate_license', 
				'license' 	=> $license, 
				'item_name' => $this->plugin_name,
				'item_id' => OXYNINJA_ITEM_ID, 
				'url'       => home_url()
			);
			
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $this->oxyninja_url ), array( 'timeout' => 30, 'sslverify' => true ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			update_option( $this->prefix . 'license_status', $license_data->license );

		}
	}


	/**
	 * Send license key to oxyninja.com EDD to deactivate license
	 * Not used anywhere though
	 * 
	 * @since 1.0
	 */

	function deactivate_license() {

		// listen for our activate button to be clicked
		if( isset( $_POST[$this->prefix.'license_deactivate'] ) ) {

			// run a quick security check 
		 	if( ! wp_verify_nonce( $_POST[$this->prefix . 'license_nonce_field'], $this->prefix . 'submit_license' ) )
				return;

			// retrieve the license from the database
			$license = trim( get_option( $this->prefix . 'license_key' ) );

			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'deactivate_license', 
				'license' 	=> $license, 
				'item_name' => $this->plugin_name,
				'item_id' => OXYNINJA_ITEM_ID,
				'url'       => home_url()
			);
			
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $this->oxyninja_url ), array( 'timeout' => 15, 'sslverify' => true ) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' )
				delete_option( $this->prefix . 'license_status' );
				delete_option( $this->prefix . 'license_key' );

		}
	}

	/**
	 * Taken from https://github.com/wp-premium/edd-software-licensing/blob/master/edd-software-licenses.php
	 * Taken from Oxygen Builder
	 * 
	 * Lowercases site URL's, strips HTTP protocols and strips www subdomains.
	 *
	 * @param string $url
	 * @return string
	 */
	
	function clean_site_url( $url ) {
		$url = strtolower( $url );
		
		// strip www subdomain
		$url = str_replace( array( '://www.', ':/www.' ), '://', $url );
	
		// strip protocol
		$url = str_replace( array( 'http://', 'https://', 'http:/', 'https:/' ), '', $url );
	
		$port = parse_url( $url, PHP_URL_PORT );
		if( $port ) {
			// strip port number
			$url = str_replace( ':' . $port, '', $url );
		}
		
		return sanitize_text_field( $url );
	}

}

// instantinate the classes
$oxyninja_plugin = new OxyNinjaUpdater( array(
		"prefix" 		=> "oxyninja_",
		"plugin_name" 	=> "OxyNinja Plugin",
		"priority" 		=> 30
) );

add_action('init', function () {
  /* check if Oxygen is active */
  if (!class_exists('OxyEl')) {
      return;
  }
  /* include our plugin main file */
  require_once __DIR__ . '/load-plugin.php';
});