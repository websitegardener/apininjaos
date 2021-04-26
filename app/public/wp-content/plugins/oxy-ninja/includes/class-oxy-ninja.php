<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://oxyninja.com
 * @since      3.1.0
 *
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      3.1.0
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/includes
 * @author     OxyNinja <hello@oxyninja.com>
 */
class Oxy_Ninja {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    3.1.0
	 * @access   protected
	 * @var      Oxy_Ninja_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    3.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    3.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    3.1.0
	 */
	public function __construct() {
		if ( defined( 'OXYNINJA_VERSION' ) ) {
			$this->version = OXYNINJA_VERSION;
		} else {
			$this->version = '3.3.3';
		}
		$this->plugin_name = 'oxy-ninja';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_helpers_hooks();
		$this->define_public_hooks();
		$this->define_acf_fields();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Oxy_Ninja_Loader. Orchestrates the hooks of the plugin.
	 * - Oxy_Ninja_i18n. Defines internationalization functionality.
	 * - Oxy_Ninja_Admin. Defines all hooks for the admin area.
	 * - Oxy_Ninja_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    3.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-oxy-ninja-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-oxy-ninja-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-oxy-ninja-admin.php';

		/**
		 * The class responsible for defining helpers that occur in the Oxygen panel area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-helper-integration.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-oxy-ninja-public.php';

		$this->loader = new Oxy_Ninja_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Oxy_Ninja_Admin( $this->get_plugin_name(), $this->get_version() );

		// $this->loader->add_action( 'init', $plugin_admin, 'oxyninja_register_options' );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'oxyninja_settings_menu' );
		$this->loader->add_action( 'oxygen_enqueue_ui_scripts', $plugin_admin, 'enqueue_scripts', 99 );
		$this->loader->add_action( 'oxygen_enqueue_ui_scripts', $plugin_admin, 'enqueue_styles', 99 );
		$this->loader->add_action( 'wp_ajax_oxy_ninja_api_call', $plugin_admin, 'oxy_ninja_api_call', 120 );
		$this->loader->add_action( 'oxygen_enqueue_iframe_scripts', $plugin_admin, 'core_oxy_ninja_iframe', 150 );

	}

		/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Oxy_Ninja_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    3.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Oxy_Ninja_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 */
	private function define_helpers_hooks() {

		global $on_helper_integration;
		$on_helper_integration = new ON_HELPER_INTEGRATION();

		include OXYNINJA_MAIN . 'admin/view/sidebar.php';
		include OXYNINJA_MAIN . 'admin/view/helpers.php';
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Oxy_Ninja_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'oxygen_enqueue_scripts', $plugin_public, 'enqueue_scripts', 0 );
		$this->loader->add_action( 'oxygen_enqueue_scripts', $plugin_public, 'enqueue_styles', 0 );
		$this->loader->add_shortcode( 'wc_sec_image', $plugin_public, 'oxyninja_second_product_thumbnail' );
		$this->loader->add_shortcode( 'wc_new_badge', $plugin_public, 'oxyninja_new_badge' );
		$this->loader->add_shortcode( 'wc_sale_badge', $plugin_public, 'oxyninja_sale_badge' );

	}

	/**
	 * Register ACF Field for Product Custom Badge
	 *
	 * @since    3.2.0
	 * @access   private
	 */
	private function define_acf_fields() {
		
		// $oxyninja_storage = get_option( 'oxyninja_storage' );
		// if( function_exists('acf_add_local_field_group') && $oxyninja_storage['badge'] ):
		if( function_exists('acf_add_local_field_group') ):

			acf_add_local_field_group(array(
				'key' => 'group_5fcf911da5e77',
				'title' => __('Custom Product Badge', 'oxy-ninja'),
				'fields' => array(
					array(
						'key' => 'field_5fcf9128be5fd',
						'name' => 'on_custom_product_badge',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'product',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));
			
			endif;
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    3.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     3.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     3.1.0
	 * @return    Oxy_Ninja_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     3.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}