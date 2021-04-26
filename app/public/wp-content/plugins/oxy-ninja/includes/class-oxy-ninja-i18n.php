<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://oxyninja.com
 * @since      3.1.0
 *
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      3.1.0
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/includes
 * @author     OxyNinja <hello@oxyninja.com>
 */
class Oxy_Ninja_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    3.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'oxy-ninja',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
