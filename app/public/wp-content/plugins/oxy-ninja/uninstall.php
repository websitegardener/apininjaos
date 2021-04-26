<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://oxyninja.com
 * @since      3.2.0
 *
 * @package    Oxy_Ninja
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$network_active = false;
$prva = base64_decode('b3h5bmluamFfbGljZW5zZV9rZXk=');
$druha = base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=');
$optn_oxyninja = [$prva, $druha];

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
if (is_plugin_active_for_network('oxy-ninja/oxy-ninja.php')) {
	$network_active = true;
}

if ($network_active) {
	foreach ($optn_oxyninja as $v) :
		delete_site_option($v);
	endforeach;
} else {
	foreach ($optn_oxyninja as $v) :
		delete_option($v);
	endforeach;
}
