<?php

namespace ERROPiX\HydrogenPack;

class SandboxController
{
    use Utils;

    private $sandbox_active = false;
    private $prefix = "hydro_sandbox_";
    private $cookie = "hydro_sandbox";
    private $action_sandbox_publish = "hydrogen_sandbox_publish";
    private $action_sandbox_delete = "hydrogen_sandbox_delete";
    private $secret_option_name = "hydrogen-sandbox-secret";
    private $secret_query_arg = "sbsecret";
    private $secret_link;
    private $secret;
    private $SettingsManager;
    private $settings;
    private $page_url;
    private $nonce_name;

    private $sandbox_metas = [
        "ct_builder_shortcodes",
        "ct_page_settings",
        "ct_render_post_using",
        "ct_use_inner_content",
        "ct_parent_template",
        "ct_other_template",
    ];

    private $sandbox_options = [
        "ct_global_settings",
        "ct_components_classes",
        "ct_custom_selectors",
        "ct_style_sets",
        "ct_style_folders",
        "ct_style_sheets",
        "oxygen_vsb_easy_posts_templates",
        "oxygen_vsb_comments_list_templates",
        "oxygen_vsb_latest_typekit_fonts",
        "oxygen_vsb_global_colors",
        "oxygen_vsb_element_presets",
        "oxygen_vsb_google_fonts_cache",
        "oxygen_vsb_universal_css_cache",
    ];

    public function __construct(SettingsManager $SettingsManager)
    {
        $this->SettingsManager = $SettingsManager;
        $this->settings = $SettingsManager->get_settings();
        $this->page_url = $SettingsManager->get_page_url();
        $this->nonce_name = $SettingsManager->get_nonce_name();
        $this->secret = get_option($this->secret_option_name);
        $this->sandbox_active = $this->set_sandbox_status();
        $this->secret_link = add_query_arg($this->secret_query_arg, $this->secret, site_url());

        add_action("init", [$this, "init"]);
        add_action("admin_post_{$this->action_sandbox_publish}", [$this, 'publish_changes']);
        add_action("admin_post_{$this->action_sandbox_delete}", [$this, 'delete_changes']);
        add_action("hydrogen_settings_updated", [$this, 'settings_updated']);
        add_filter("hydrogen_settings_features", [$this, 'add_settings_features']);

        if ($this->sandbox_active) {
            add_action("wp_head", [$this, 'add_admin_bar_style']);
            add_action("admin_head", [$this, 'add_admin_bar_style']);
            add_action("admin_bar_menu", [$this, 'add_admin_bar_node'], 40);
            add_action("hydrogen_settings_boxes", [$this, 'add_settings_boxes']);
            add_filter("get_post_metadata", [$this, "get_post_metadata"], 1, 4);

            foreach ($this->sandbox_options as $option) {
                add_filter("pre_option_{$option}", [$this, "pre_get_option"], 1, 3);
            }
        }
    }

    public function set_sandbox_status()
    {
        if ($this->settings->sandbox->enabled) {
            // Activated by cookie
            if ($this->validate_sandbox_cookie()) {
                return true;
            }

            // activated by secret key
            $secret = $_GET[$this->secret_query_arg] ?? false;
            if ($secret && $secret == $this->secret) {
                $this->activate_sandbox_cookie();
                return true;
            }
        }

        return false;
    }

    public function init()
    {
        if (!$this->sandbox_active && $this->secret && $this->settings->sandbox->enabled && current_user_can("manage_options")) {
            $this->activate_sandbox_cookie(true);
        }

        if ($this->sandbox_active) {
            add_filter("update_post_metadata", [$this, "update_post_metadata"], 1, 5);
            add_filter("delete_post_metadata", [$this, "delete_post_metadata"], 1, 5);
            add_filter("pre_update_option", [$this, "pre_update_option"], 1, 3);
        }
    }

    public function validate_sandbox_cookie()
    {
        $cookie = $_COOKIE[$this->cookie] ?? false;
        return $cookie && $cookie == $this->secret;
    }

    public function activate_sandbox_cookie($refresh = false)
    {
        setcookie($this->cookie, $this->secret, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

        if ($refresh && isset($_SERVER['REQUEST_URI']) && wp_redirect($_SERVER['REQUEST_URI'])) {
            exit;
        }
    }

    public function deactivate_sandbox_cookie($refresh = false)
    {
        setcookie($this->cookie, "", time() - MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);

        if ($refresh && isset($_SERVER['REQUEST_URI']) && wp_redirect($_SERVER['REQUEST_URI'])) {
            exit;
        }
    }

    public function publish_changes()
    {
        global $wpdb;

        // Check referer
        check_admin_referer($this->nonce_name);

        $options = $wpdb->get_results("SELECT * FROM $wpdb->options WHERE option_name like '{$this->prefix}%'");
        $postmeta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key like '{$this->prefix}%'");

        // Start MySQL transaction
        $wpdb->query("START TRANSACTION");

        // Publish options
        if ($options) {
            foreach ($options as $option) {
                $option_id = $option->option_id;
                $option_name = $this->unprefix($option->option_name);
                $option_value = $option->option_value;

                $target_id = $wpdb->get_var("SELECT option_id FROM $wpdb->options WHERE option_name='{$option_name}'");

                if ($target_id) {
                    $data = [
                        "option_value" => $option_value
                    ];
                    $where = [
                        "option_id" => $target_id
                    ];
                    $wpdb->update($wpdb->options, $data, $where, "%s", "%d");
                } else {
                    $data = [
                        "option_name" => $option_name,
                        "option_value" => $option_value
                    ];
                    $wpdb->insert($wpdb->options, $data, ["%s", "%s"]);
                }

                // Delete sandbox options
                $wpdb->delete($wpdb->options, ["option_id" => $option_id], "%d");
            }
        }

        // Publish metadata
        if ($postmeta) {
            foreach ($postmeta as $meta) {
                $post_id = $meta->post_id;
                $meta_id = $meta->meta_id;
                $meta_key = $this->unprefix($meta->meta_key);
                $meta_value = $meta->meta_value;

                $target_id = $wpdb->get_var("SELECT meta_id FROM $wpdb->postmeta WHERE post_id=$post_id AND meta_key='{$meta_key}' LIMIT 1");

                if ($target_id) {
                    $data = [
                        "meta_value" => $meta_value
                    ];
                    $where = [
                        "meta_id" => $target_id
                    ];
                    $wpdb->update($wpdb->postmeta, $data, $where, "%s", "%d");
                } else {
                    $data = [
                        "post_id" => $post_id,
                        "meta_key" => $meta_key,
                        "meta_value" => $meta_value
                    ];
                    $wpdb->insert($wpdb->postmeta, $data, ["%d", "%s", "%s"]);
                }

                // Delete sandbox metadata
                $wpdb->delete($wpdb->postmeta, ["option_id" => $option_id], "%d");
            }
        }

        // Commit changes
        $wpdb->query("COMMIT");

        // Disable sandbox mode
        $this->SettingsManager->update_user_settings("sandbox.enabled", false);
        $this->deactivate_sandbox_cookie();

        // Redirect to css cache page
        $redirect_to = admin_url('admin.php?page=oxygen_vsb_settings&tab=cache&start_cache_generation=true');
        wp_redirect($redirect_to);
    }

    public function delete_changes()
    {
        global $wpdb;

        // Check referer
        check_admin_referer($this->nonce_name);

        $deleted = 0;
        $redirect_to = $this->page_url;

        // Start MySQL transaction
        $wpdb->query("START TRANSACTION");

        // Delete sandboxed options
        $deleted += $wpdb->query("DELETE FROM $wpdb->options WHERE option_name like '{$this->prefix}%'");

        // Delete sandboxed metadata
        $deleted += $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key like '{$this->prefix}%'");

        // Commit changes
        $wpdb->query("COMMIT");

        if ($deleted) {
            $redirect_to = add_query_arg('settings-updated', 'true', $redirect_to);
            $this->SettingsManager->set_settings_message("Sandbox content deleted successfully.");
        }

        // Redirect to settings page
        wp_redirect($redirect_to);
    }

    public function settings_updated($settings)
    {
        $old_status = $this->settings->sandbox->enabled;
        $new_status = $this->array_get($settings, 'sandbox.enabled');

        if ($old_status == false && $new_status == true) {
            $secret = wp_generate_uuid4();

            update_option($this->secret_option_name, $secret);
        }
    }

    public function have_changes()
    {
        global $wpdb;

        $changes = 0;
        $changes += intval($wpdb->get_var("SELECT COUNT(option_id) FROM $wpdb->options WHERE option_name like '{$this->prefix}%'"));
        $changes += intval($wpdb->get_var("SELECT COUNT(meta_id) FROM $wpdb->postmeta WHERE meta_key like '{$this->prefix}%'"));

        return $changes;
    }

    public function add_settings_features($features)
    {
        $features['sandbox'] = [
            "label" => "Sandbox mode",
            "haveOptions" => $this->sandbox_active,
        ];

        return $features;
    }

    public function add_settings_boxes()
    {
        include $this->get_template('settings/sandbox');
    }

    public function add_admin_bar_node($admin_bar)
    {
        $admin_bar->add_node([
            'id'    => 'hydrogen-sandbox',
            'title' => 'Sandbox Mode',
            'href'  => $this->page_url . "#section-sandbox",
            'meta'  => [
                'class' => 'hydrogen-sandbox'
            ],
        ]);
    }

    public function add_admin_bar_style()
    {
        include $this->get_template('sandbox-css');
    }

    public function prefix($name)
    {
        return $this->prefix . $name;
    }

    public function unprefix($name)
    {
        if (strpos($name, $this->prefix) === 0) {
            $name = substr($name, strlen($this->prefix));
        }
        return $name;
    }

    public function get_post_metadata($value, $object_id, $meta_key, $single)
    {
        if (in_array($meta_key, $this->sandbox_metas)) {
            $meta_key = $this->prefix($meta_key);
            if (metadata_exists("post", $object_id, $meta_key)) {
                $value = get_metadata("post", $object_id, $meta_key, $single);
                if ($single && is_array($value)) {
                    $value = [$value];
                }
            }
        }
        return $value;
    }

    public function update_post_metadata($update, $object_id, $meta_key, $meta_value, $prev_value)
    {
        if (in_array($meta_key, $this->sandbox_metas)) {
            $meta_key = $this->prefix($meta_key);
            $update = update_metadata("post", $object_id, $meta_key, $meta_value, $prev_value);
        }
        return $update;
    }

    public function delete_post_metadata($delete, $object_id, $meta_key, $meta_value, $delete_all)
    {
        if (in_array($meta_key, $this->sandbox_metas)) {
            $meta_key = $this->prefix($meta_key);
            $delete = delete_metadata("post", $object_id, $meta_key, $meta_value, $delete_all);
        }
        return $delete;
    }

    public function option_exists($option)
    {
        global $wpdb;

        $suppress = $wpdb->suppress_errors();
        $query = $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option);
        $row = $wpdb->get_row($query);
        $wpdb->suppress_errors($suppress);

        return is_object($row);
    }

    public function pre_get_option($pre_option, $option, $default)
    {
        if ($option == "oxygen_vsb_universal_css_cache") {
            return "false";
        }

        $option = $this->prefix($option);
        if ($this->option_exists($option)) {
            $pre_option = get_option($option, $default);
        }

        return $pre_option;
    }

    public function pre_update_option($value, $option, $old_value)
    {
        if ($option == "oxygen_vsb_universal_css_cache") {
            return $old_value;
        }

        if (in_array($option, $this->sandbox_options)) {
            $option = $this->prefix($option);
            update_option($option, $value);
            return $old_value;
        }

        return $value;
    }
}
