=== OxyNinja ===
Contributors: rados51
Donate link: https://oxyninja.com/
Tags: oxyninja, Oxygen, Oxygen Builder, WooCommerce
Requires at least: 5.4
Tested up to: 5.7
Requires PHP: 7.1
Stable tag: 3.3.3
License: EULA + GNU General Public License v3.0
License URI: https://oxyninja.com/eula/

OxyNinja plugin with several new functionalities.

== Description ==

This small plugin a brain of all OxyNinja products. It does the initial import of selectors & stylesheets, adds class reset, class lock and grid helpers or remade columns from the Core framework.

It's made to save time when working with OxyNinja products.

== Frequently Asked Questions ==

= What do I need to do after install? =

Install the plugin, add the plugin license key in the Oxygen License settings, then add API key of a design set in the Oxygen Library settings. 

Go to any page or template in Oxygen and do the import via Manage > OxyNinja > Start Import

= Do I need to use different plugin for Core and WooCore? =

No, you always use this same plugin with all our products.

== Screenshots ==

* OxyNinja Plugin.

== Changelog ==

= 3.3.3 =
[Fix] OxyNinja Slider Per Move not working properly
Fixed Fatal error caused by WooCommerce Mix & Match

= 3.3.2 =
* [Tweak] Related query use native WooCommerce function (wc_get_related_products)
* [Tweak] ACF Custom Badge without OxyNinja branding
* Fixed cannot redeclare variable error
* Now you can find Agency Base JSON at the root of the folder

= 3.3.1 =
* [Fix] OxyNinja Slider also accepts multisite plugin
* [Tweak] Variable product image tweak
* [Tweak] Toolset accepts also external images from URL
* OxyToolbox UI additional fix
* Fixed fatal error on inserting OxyNinja Slider inside repeater
* Fixed undefined $scope on concated scripts
* Fixed undefined variable: max_percentage

= 3.3.0 =
* [NEW] Slider / Carousel Component
* [Feature] OxyNinja UI is hidden if “Enable 3rd party Design Sets” it disabled
* [Enhancement] Class Lock can now lock entire framework on demand
* [Enhancement] Class Reset can now reset classes in bulk using partial matches starting with c-
* [Enhancement] Core framework updated with new classes
* [Tweak] You can lock whole framework on import
* [Tweak] Class Reset DOM Rebuild — No need for activating class on reset
* [Tweak] On plugin uninstall, all plugin data from database are remove
* [Fix] Fixed Class Lock error when updating from older version then 3.1.0
* [Fix] UI fix for EE and OxyToolbox
* [Fix] Fixed endless spinning wheel on first import
* [Fix] Fixed wp_debug notices
* Compatibility with WordPress 5.7 & Oxygen 3.7
* Various other bug fixes

= 3.2.0 =
* [NEW] Possibility to migrate all selectors between sites
* Compatibility with WordPress 5.6 & Oxygen 3.7
* Third party libraries updated to actual versions
* Bug fixes

= 3.1.1 =
* [NEW]  CORE Framework - Hover classes and full grid span and positioning classes added
* [NEW]  Grid helpers - 6 new helpers / grids added
* [Tweak]  ID/Class lock - Icon change based on lock state
* [Feature]  ID/Class lock - Last state (locked/unlocked) save
* [Enhancement]  CSS Grid stylesheets loads in background in case it's missing
* Bug fixes

= 3.1 =
* [NEW] Class lock - Lock the classes on element to prevent accidental changes in wrong class
* [NEW] Grid helpers - Add pre-made columns from Core with responsive classes added from +Add panel
* [NEW] Auto Update from WP Dashboard
* Bug fixes

= 3.0 =
* Plugin rewritten to work with both Core and WooCore out of the box
* [NEW] Adds SplideJs, Sales and New badge to WooCore

= 2.0 =
* [NEW] Class reset - You can reset any class from Core to the default one
* Bug fixes

= 1 =
* Import of selectors and stylesheets

== Upgrade Notice ==

New feature that can copy & paste all selectors (classes) between sites. Also, We fixed issues with new version of Wordpress.

== Features list ==

1. Import of selectors and stylesheets
2. Class Reset
3. Class Lock
4. Classes Migration
5. Grid helpers of pre-made columns in Oxygen elements
6. OxyNinja Slider
