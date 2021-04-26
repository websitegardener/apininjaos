<?php

namespace ERROPiX\HydrogenPack;

/**
 * Class HydrogenPack
 * @package ERROPiX\HydrogenPack
 */
class HydrogenPack
{
    use Utils;

    private $settings;
    private $is_builder = false;
    private $is_iframe = false;

    public function __construct()
    {
        // Add settings manager
        $SettingsManager = new SettingsManager();

        $fs = epxhydro_fs();
        if ($fs->can_use_premium_code()) {
            // Load Hydrogen settings
            $this->settings = $SettingsManager->get_settings();

            add_action('wp_footer', [$this, 'render_template']);
            add_action('oxygen_enqueue_iframe_scripts', [$this, 'enqueue_iframe_scripts']);
            add_action('oxygen_enqueue_ui_scripts', [$this, 'enqueue_builder_scripts']);

            add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
            add_filter('plugin_action_links', [$this, 'plugin_action_links'], 10, 2);

            // Freemius hooks
            $fs->add_filter('hide_account_tabs', [$this, 'replace_account_tabs']);
            $fs->add_filter('show_affiliate_program_notice', '__return_false');

            // Disable edit locking
            if ($this->settings->disableEditLocking->enabled) {
                add_action('admin_head', [$this, 'disable_edit_locking']);
            }

            // Disable composite elements
            if ($this->settings->disableCompositeElements->enabled) {
                add_filter('pre_option_oxygen_vsb_is_composite_elements_agency_bundle', '__return_null');
            }

            // CSS Cache Enhancements
            if ($this->settings->cssCacheRegeneration->enabled) {
                if ($this->GET('action') === "ct_save_components_tree") {
                    add_action("pre_update_option_oxygen_breakpoints_cache_update_required", [$this, "regenerate_css_cache"]);
                    add_action("pre_update_option_oxygen_global_colors_cache_update_required", [$this, "regenerate_css_cache"]);
                }
            }

            // Dynamic Classes
            if ($this->settings->dynamicClasses->enabled) {
                new DynamicClasses();
            }

            // Class Lock
            if ($this->settings->classLock->enabled) {
                add_action("oxygen_before_toolbar_close", [$this, "render_template_locked_class"]);
            }

            // Sandbox Controller
            new SandboxController($SettingsManager);
        }
    }

    /**
     * Render builder template for both iframe and UI
     */
    public function render_template()
    {
        $settings = $this->settings;

        if ($this->is_iframe) {
            include $this->get_template('iframe');
        }

        if ($this->is_builder) {
            include $this->get_template('builder');
        }
    }

    /**
     * Display locked class template
     * @return void 
     */
    public function render_template_locked_class()
    {
        include $this->get_template("toolbar/locked-class");
    }

    /**
     * Intercept the transients to disable edit locking, also abort ajax request that set the transient
     */
    public function disable_edit_locking()
    {
        global $post;

        $transient = "oxygen_post_edit_lock";
        add_filter("pre_transient_{$transient}", "intval");

        if ($post && $post->ID) {
            add_filter("pre_transient_{$transient}_{$post->ID}", "intval");

            echo '<script>';
            echo 'jQuery.ajaxSetup({beforeSend:function(e,t){t.data&&t.data.indexOf("oxygen_edit_post_lock_transient")>0&&e.abort()}});';
            echo '</script>';
        }
    }

    /**
     * Regenerate CSS cache when global colors or breakpoints changed
     */
    public function regenerate_css_cache($value)
    {
        static $regenerated_cache = null;

        if ($value) {
            global $oxy_ajax_post_id;

            if ($regenerated_cache == null) {
                $post_id = intval($_REQUEST["post_id"] ?? 0);
                $post_ids = [];

                $post_types = ["any", "ct_template"];
                foreach ($post_types as $post_type) {
                    $posts = get_posts([
                        "nopaging"      => true,
                        "post_type"     => $post_type,
                        "fields"        => "ids",
                        "meta_query"    => [
                            [
                                "key"     => "ct_builder_shortcodes",
                                "value"   => "",
                                "compare" => "!=",
                            ],
                        ],
                    ]);

                    if (is_array($posts)) {
                        array_push($post_ids, ...$posts);
                    }
                }

                $post_ids = array_unique($post_ids);
                foreach ($post_ids as $id) {
                    if ($id !== $post_id) {
                        $oxy_ajax_post_id = $id;
                        oxygen_vsb_cache_page_css($id);
                    }
                }

                $regenerated_cache = true;
            }

            $value = false;
        }

        return $value;
    }

    /**
     * Add body classes for conditional CSS
     */
    public function editor_body_class($classes)
    {
        if ($this->is_iframe || $this->is_builder) {
            // Editor Enhancer compatibility
            $classes[] = defined('EDITOR_ENHANCER') ? "hydrogen-eed-on" : "hydrogen-eed-off";

            // Features status
            if ($this->settings->contextMenu->enabled) {
                $classes[] = "hydrogen-context-menu-on";
            }
        }

        if ($this->is_builder) {
            if ($this->settings->conditionsEnhancer->enabled) {
                $classes[] = "hydrogen-conditions-enhancer-on";
            }

            if ($this->settings->advancedStylesReset->enabled) {
                $classes[] = "hydrogen-advanced-styles-reset";
            }

            if ($this->settings->structureEnhancer->enabled) {
                $classes[] = "hydrogen-structure-enhancer-on";

                if ($this->settings->structureEnhancer->compact) {
                    $classes[] = "hydrogen-structure-compact";
                }

                if ($this->settings->structureEnhancer->icons) {
                    $classes[] = "hydrogen-structure-icons";
                }
            }
        }

        return $classes;
    }

    /**
     * Enqueue scripts and styles for the iframe preview
     */
    public function enqueue_iframe_scripts()
    {
        $this->is_iframe = true;

        wp_enqueue_style("epxhydro-iframe-style", $this->url('assets/css/iframe.min.css'), [], EPXHYDRO_VER);
        wp_enqueue_script("epxhydro-iframe-script", $this->url('assets/js/iframe.min.js'), [], EPXHYDRO_VER, true);

        wp_localize_script("epxhydro-iframe-script", 'hydrogen', [
            'siteUrl' => get_site_url(),
            'settings' => $this->settings,
        ]);

        add_action('body_class', [$this, 'editor_body_class']);
    }

    /**
     * Enqueue scripts and styles for the builder UI
     */
    public function enqueue_builder_scripts()
    {
        $this->is_builder = true;

        wp_enqueue_style("epxhydro-builder-style", $this->url('assets/css/builder.min.css'), [], EPXHYDRO_VER);
        wp_enqueue_script("epxhydro-builder-script", $this->url('assets/js/builder.min.js'), [], EPXHYDRO_VER, true);

        wp_localize_script("epxhydro-builder-script", 'hydrogen', [
            'siteUrl' => get_site_url(),
            'settings' => $this->settings,
        ]);

        add_action('body_class', [$this, 'editor_body_class']);
    }

    public function admin_enqueue_scripts($hook)
    {
        if ($hook == "oxygen_page_hydrogen-pack-account") {
            add_action("admin_head", function () {
                include $this->get_template("account-css");
            }, 0);
        }
    }

    /**
     * @param $actions string[]
     * @param $file    string
     *
     * @return array
     */
    public function plugin_action_links($actions, $file)
    {
        if ($file == EPXHYDRO_BASE) {
            $account_url = epxhydro_fs()->get_account_url();

            if ($account_url) {
                $new_actions = [
                    'account' => sprintf('<a href="%s">Account</a>', $account_url),
                ];

                $actions = array_merge($new_actions, $actions);
            }
        }

        return $actions;
    }

    /**
     * Override Freemius account page tabs with normal heading title
     * 
     * @return boolean
     */
    public function replace_account_tabs()
    {
        echo "<h1>Hydrogen Pack Account</h1>";
        return true;
    }
}
