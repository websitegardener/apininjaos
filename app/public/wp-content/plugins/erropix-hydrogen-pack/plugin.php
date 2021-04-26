<?php

/**
 * Plugin Name: Hydrogen Pack
 * Plugin URI: https://www.cleanplugins.com/products/hydrogen-pack/
 * Description: A Pack of time saving Oxygen Builder enhancements
 * Version: 1.3.1
 * Author: Clean Plugins
 * Author URI: https://www.cleanplugins.com/
 **/

// don't load directly
if (!defined('ABSPATH')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Plugin constants
define('EPXHYDRO_VER', '1.3.1');
define('EPXHYDRO_PHP_VERSION', '7.0');
define('EPXHYDRO_BASE', plugin_basename(__FILE__));
define('EPXHYDRO_URL', plugin_dir_url(__FILE__));
define('EPXHYDRO_DIR', plugin_dir_path(__FILE__));

// Require the minimum PHP version
if (version_compare(PHP_VERSION, EPXHYDRO_PHP_VERSION, '<')) {
    add_action('admin_notices', function () {
        echo '<div class="error notice"><p>Hydrogen Pack require PHP version ' . EPXHYDRO_PHP_VERSION . ' or newer</p></div>';
    });
} else {
    // Load the plugin class
    require EPXHYDRO_DIR . 'vendor/autoload.php';

    new ERROPiX\HydrogenPack\HydrogenPack;
}
