<?php

namespace ERROPiX\HydrogenPack;

/**
 * Class Settings
 * @package ERROPiX\HydrogenPack
 */
class SettingsManager
{
    use Utils;

    private $nonce_name = 'hydrogen-settings';
    private $error_code = 'hydrogen';
    private $action_settings_save = 'hydrogen_settings_save';
    private $action_settings_reset = 'hydrogen_settings_reset';
    private $page_slug = 'hydrogen-pack';
    private $capability = 'manage_options';
    private $page_url;
    private $hookname_settings;
    private $hookname_oxy_options;

    private $settings_option_name = 'hydrogen-settings';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_settings_page'], 12);
        add_action('admin_notices', [$this, 'admin_notices']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action("admin_post_{$this->action_settings_save}", [$this, 'save_settings']);
        add_action("admin_post_{$this->action_settings_reset}", [$this, 'reset_settings']);

        $this->page_url = admin_url("admin.php?page={$this->page_slug}");
    }

    public function get_settings()
    {
        static $settings = null;

        if (empty($settings)) {
            $default  = $this->get_default_settings();
            $user_settings = get_option($this->settings_option_name);

            if (!is_array($user_settings) || empty($user_settings)) {
                $settings = $default;
            } else {
                $settings = $this->array_extend($default, $user_settings);
            }

            $settings = new SafeObject($settings);
        }

        return $settings;
    }

    public function update_user_settings($path, $value)
    {
        $user_settings = get_option($this->settings_option_name, []);
        $this->array_set($user_settings, $path, $value);
        return update_option($this->settings_option_name, $user_settings);
    }

    public function reset_settings()
    {
        // Check referer
        check_admin_referer($this->nonce_name);
        $redirect_to = $this->page_url;

        // Delete the settings option
        if (delete_option($this->settings_option_name)) {
            $redirect_to = add_query_arg('settings-updated', 'true', $redirect_to);
            $this->set_settings_message("All settings has been reset successfully.", 'info');
        }

        // Redirect to settings page
        wp_redirect($redirect_to);
    }

    public function save_settings()
    {
        // Check referer
        check_admin_referer($this->nonce_name);

        $redirect_to = $this->page_url;
        $message = "Settings %s successfully.";
        $import = $this->POST('import');

        if (empty($import)) {
            $settings = $this->POST('settings');
            if (!empty($settings)) {
                array_walk_recursive($settings, function (&$item) {
                    if ($item === "true") {
                        $item = true;
                    } else if ($item === "false") {
                        $item = false;
                    }
                });

                // Allow modules to modify settings before update
                $settings = apply_filters("hydrogen_settings_pre_update", $settings);

                if (update_option($this->settings_option_name, $settings)) {
                    $redirect_to = add_query_arg('settings-updated', 'true', $redirect_to);
                    $this->set_settings_message(sprintf($message, "updated"));

                    // Fire action after settings update
                    do_action("hydrogen_settings_updated", $settings);
                }
            }
        } else {
            $settings = $this->decode($import);
            if (is_array($settings)) {
                // Allow modules to modify settings before update
                $settings = apply_filters("hydrogen_settings_pre_update", $settings);

                if (update_option($this->settings_option_name, $settings)) {
                    $redirect_to = add_query_arg('settings-updated', 'true', $redirect_to);
                    $this->set_settings_message(sprintf($message, "imported"));

                    // Fire action after settings update
                    do_action("hydrogen_settings_updated", $settings);
                }
            } else {
                $redirect_to = add_query_arg('settings-updated', 'false', $redirect_to);
                $this->set_settings_message("Failed to import settings! the provided code was malformed.", "error");
            }
        }

        // Redirect to settings page
        wp_redirect($redirect_to);
    }

    public function register_settings_page()
    {
        $this->hookname_settings = add_submenu_page(
            "ct_dashboard_page",
            "Hydrogen Pack Settings",
            "Hydrogen Pack",
            $this->capability,
            $this->page_slug,
            [$this, 'render_admin_page']
        );
    }

    public function admin_enqueue_scripts()
    {
        global $current_screen;

        if ($current_screen->id == $this->hookname_settings) {
            wp_enqueue_style("epxhydro-settings", $this->url('assets/css/settings.min.css'), [], EPXHYDRO_VER);
            wp_enqueue_script("epxhydro-settings", $this->url('assets/js/settings.min.js'), [], EPXHYDRO_VER, true);
        }
    }

    public function render_admin_page()
    {
        $ui = new UI();
        $user_settings = get_option($this->settings_option_name);
        $settings = $this->get_settings();

        include $this->get_template("settings");
    }

    public function render_options_editor_page()
    {
        include $this->get_template("oxygen-options");
    }

    public function admin_notices()
    {
        settings_errors($this->error_code);
    }

    public function set_settings_message($message, $type = "success")
    {
        $settings_errors = [
            [
                'setting' => $this->error_code,
                'code' => $this->error_code,
                'message' => $message,
                'type' => $type,
            ],
        ];
        set_transient('settings_errors', $settings_errors, 300);
    }

    public function get_page_url()
    {
        return $this->page_url;
    }

    public function get_nonce_name()
    {
        return $this->nonce_name;
    }

    public function get_option_name()
    {
        return $this->settings_option_name;
    }

    public function get_default_settings()
    {
        return [
            "contextMenu" => [
                "enabled" => true,
                "items" => [
                    "duplicate" => true,
                    "copy" => true,
                    "copyStyle" => true,
                    "copyConditions" => true,
                    "cut" => true,
                    "paste" => true,
                    "saveReusable" => true,
                    "saveBlock" => true,
                    "wrap" => true,
                    "wrapLink" => true,
                    "switchTextComponent" => true,
                    "showConditions" => true,
                    "rename" => true,
                    "changeId" => true,
                    "delete" => true
                ],
            ],
            "clipboard" => [
                "enabled" => true,
                "folder" => 'Copied Classes',
                "colorSet" => 'Copied Colors',
                "keepActiveComponent" => false,
                "flashCopiedComponent" => true,
                "processMediaImages" => true,
            ],
            "shortcuts" => [
                "enabled" => true,
                "hotkeys" => [
                    // Basic editor shortcuts
                    "componentBrowser" => [
                        "label" => "Components browser",
                        "arguments" => ["componentBrowser"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "a",
                    ],
                    "copy" => [
                        "label" => "Copy element",
                        "dependon" => "#settings_clipboard_enabled",
                        "arguments" => ["copy"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "c",
                    ],
                    "copyStyle" => [
                        "label" => "Copy style",
                        "dependon" => "#settings_clipboard_enabled",
                        "arguments" => ["copyStyle"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => true,
                        "key" => "c",
                    ],
                    "copyConditions" => [
                        "label" => "Copy conditions",
                        "dependon" => "#settings_clipboard_enabled",
                        "arguments" => ["copyConditions"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "cut" => [
                        "label" => "Cut element",
                        "dependon" => "#settings_clipboard_enabled",
                        "arguments" => ["cut"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "x",
                    ],
                    "duplicate" => [
                        "label" => "Duplicate element",
                        "arguments" => ["duplicate"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "d",
                    ],
                    "savePage" => [
                        "label" => "Save page",
                        "arguments" => ["savePage"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "s",
                    ],
                    "delete" => [
                        "label" => "Delete element",
                        "arguments" => ["delete"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "delete",
                    ],
                    "rename" => [
                        "label" => "Rename element",
                        "arguments" => ["rename"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "f2",
                    ],
                    "changeId" => [
                        "label" => "Change element ID",
                        "arguments" => ["changeId"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "f2",
                    ],
                    "moveUp" => [
                        "label" => "Move element up",
                        "arguments" => ["moveUp"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "arrowup",
                    ],
                    "moveDown" => [
                        "label" => "Move element down",
                        "arguments" => ["moveDown"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "arrowdown",
                    ],
                    "wrap" => [
                        "label" => "Wrap element with DIV",
                        "arguments" => ["wrap"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => true,
                        "key" => "w",
                    ],
                    "wrapLink" => [
                        "label" => "Wrap element with link",
                        "arguments" => ["wrapLink"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "setConditions" => [
                        "label" => "Manage conditions",
                        "arguments" => ["showConditions"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "clearConditions" => [
                        "label" => "Clear conditions",
                        "arguments" => ["clearConditions"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "activateParent" => [
                        "label" => "Activate parent",
                        "arguments" => ["activateParent"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "p",
                    ],
                    "editContent" => [
                        "label" => "Edit content",
                        "arguments" => ["editContent"],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "e",
                    ],
                    "switchTextComponent" => [
                        "label" => "Switch text component",
                        "arguments" => ["switchTextComponent"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],

                    // Switch media shortcuts
                    "setMediaPrevious" => [
                        "label" => "Switch to previous breakpoint",
                        "arguments" => ["setMedia", -1],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "arrowleft",
                    ],
                    "setMediaNext" => [
                        "label" => "Switch to next breakpoint",
                        "arguments" => ["setMedia", 1],
                        "ctrl" => true,
                        "alt" => false,
                        "shift" => false,
                        "key" => "arrowright",
                    ],
                    "setMediaDefault" => [
                        "label" => "Switch to all devices",
                        "arguments" => ["setMedia", "default"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "setMediaPageWidth" => [
                        "label" => "Switch to page container",
                        "arguments" => ["setMedia", "page-width"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "setMediaTablet" => [
                        "label" => "Switch to tablet",
                        "arguments" => ["setMedia", "tablet"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "setMediaLandscape" => [
                        "label" => "Switch to phone landscape",
                        "arguments" => ["setMedia", "phone-landscape"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "setMediaPortrait" => [
                        "label" => "Switch to phone portrait",
                        "arguments" => ["setMedia", "phone-portrait"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],

                    // Add new components shortcuts
                    "addSection" => [
                        "label" => "Add section",
                        "arguments" => ["addComponent", "ct_section"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "s",
                    ],
                    "addButton" => [
                        "label" => "Add button",
                        "arguments" => ["addComponent", "ct_link_button"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "b",
                    ],
                    "addColumns" => [
                        "label" => "Add columns",
                        "arguments" => ["addComponent", "ct_new_columns"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "c",
                    ],
                    "addDiv" => [
                        "label" => "Add div",
                        "arguments" => ["addComponent", "ct_div_block"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "d",
                    ],
                    "addHeading" => [
                        "label" => "Add heading",
                        "arguments" => ["addComponent", "ct_headline"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "h",
                    ],
                    "addImage" => [
                        "label" => "Add image",
                        "arguments" => ["addComponent", "ct_image"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "i",
                    ],
                    "addCode" => [
                        "label" => "Add code block",
                        "arguments" => ["addComponent", "ct_code_block"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "k",
                    ],
                    "addLink" => [
                        "label" => "Add text link",
                        "arguments" => ["addComponent", "ct_link_text"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "l",
                    ],
                    "addRepeater" => [
                        "label" => "Add repeater",
                        "arguments" => ["addComponent", "oxy_dynamic_list"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "r",
                    ],
                    "addText" => [
                        "label" => "Add text block",
                        "arguments" => ["addComponent", "ct_text_block"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "t",
                    ],
                    "addRichText" => [
                        "label" => "Add rich text",
                        "arguments" => ["addComponent", "oxy_rich_text"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "addVideo" => [
                        "label" => "Add video",
                        "arguments" => ["addComponent", "ct_video"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => true,
                        "key" => "v",
                    ],
                    "addLinkWrapper" => [
                        "label" => "Add link wrapper",
                        "arguments" => ["addComponent", "ct_link"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "addIcon" => [
                        "label" => "Add icon",
                        "arguments" => ["addComponent", "ct_fancy_icon"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "addShortcode" => [
                        "label" => "Add shortcode",
                        "arguments" => ["addComponent", "ct_shortcode", "shortcode"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "addEasyPosts" => [
                        "label" => "Add easy posts",
                        "arguments" => ["addComponent", "oxy_posts_grid"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "addGallery" => [
                        "label" => "Add gallery",
                        "arguments" => ["addComponent", "oxy_gallery"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "addModal" => [
                        "label" => "Add modal",
                        "arguments" => ["addComponent", "ct_modal"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],

                    // Show advanced style tabs
                    "switchAdvancedTabBackground" => [
                        "label" => "Edit background",
                        "arguments" => ["switchAdvancedTab", "background"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabPosition" => [
                        "label" => "Edit size & spacing",
                        "arguments" => ["switchAdvancedTab", "position"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabLayout" => [
                        "label" => "Edit layout",
                        "arguments" => ["switchAdvancedTab", "layout"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabTypography" => [
                        "label" => "Edit typography",
                        "arguments" => ["switchAdvancedTab", "typography"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabBorders" => [
                        "label" => "Edit borders",
                        "arguments" => ["switchAdvancedTab", "borders"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabEffects" => [
                        "label" => "Edit effects",
                        "arguments" => ["switchAdvancedTab", "effects"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabCustomCSS" => [
                        "label" => "Edit custom css",
                        "arguments" => ["switchAdvancedTab", "custom-css"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "switchAdvancedTabJavascript" => [
                        "label" => "Edit javascript",
                        "arguments" => ["switchAdvancedTab", "custom-js"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],

                    // Switch between code block PHP, CSS and JS tabs
                    "switchCodeBlockTabs" => [
                        "label" => "Switch code block tabs",
                        "arguments" => ["switchCodeBlockTabs"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],

                    "applyCode" => [
                        "label" => "Apply code",
                        "arguments" => ["applyCode"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],

                    // Toggle editor panels shortcuts
                    "toggleLeftSidebar" => [
                        "label" => "Toggle left panel",
                        "arguments" => ["toggleLeftSidebar"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "toggleStructurePanel" => [
                        "label" => "Toggle structure panel",
                        "arguments" => ["switchTab", "sidePanel", "DOMTree"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "toggleSettingsPanel" => [
                        "label" => "Toggle settings panel",
                        "arguments" => ["toggleSettingsPanel"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "toggleStylesheetsPanel" => [
                        "label" => "Toggle stylesheets panel",
                        "arguments" => ["switchTab", "sidePanel", "styleSheets"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "toggleSelectorsPanel" => [
                        "label" => "Toggle selectors panel",
                        "arguments" => ["switchTab", "sidePanel", "selectors"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "manageGlobalColors" => [
                        "label" => "Manage global colors",
                        "arguments" => ["switchSettingsTab", "colors"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "manageGlobalFonts" => [
                        "label" => "Manage global fonts",
                        "arguments" => ["switchSettingsChildTab", "default-styles", "fonts"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "manageGlobalBreakpoints" => [
                        "label" => "Manage breakpoints",
                        "arguments" => ["switchSettingsChildTab", "default-styles", "page-width"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "manageGlobalLinks" => [
                        "label" => "Manage links styles",
                        "arguments" => ["switchSettingsTab", "links"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "manageGlobalHeadings" => [
                        "label" => "Manage headings styles",
                        "arguments" => ["switchSettingsChildTab", "default-styles", "headings"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "manageGlobalBodyText" => [
                        "label" => "Manage text styles",
                        "arguments" => ["switchSettingsChildTab", "default-styles", "body-text"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "openFrontend" => [
                        "label" => "Go to frontend",
                        "arguments" => ["openFrontend"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                    "openBackend" => [
                        "label" => "Go to backend",
                        "arguments" => ["openBackend"],
                        "ctrl" => false,
                        "alt" => false,
                        "shift" => false,
                        "key" => "",
                    ],
                ]
            ],
            "conditionsEnhancer" => [
                "enabled" => true,
            ],
            "structureEnhancer" => [
                "enabled" => true,
                "width" => "",
                "compact" => true,
                "icons" => true,
                "openOnLoad" => false,
                "expandAll" => false,
            ],
            "contentEditorEnhancer" => [
                "enabled" => true,
            ],
            "advancedStylesReset" => [
                "enabled" => true,
            ],
            "disableEditLocking" => [
                "enabled" => false,
            ],
            "disableCompositeElements" => [
                "enabled" => false,
            ],
            "sandbox" => [
                "enabled" => false,
                "secret" => false,
            ],
            "cssCacheRegeneration" => [
                "enabled" => false,
            ],
            "dynamicClasses" => [
                "enabled" => false,
            ],
            "classLock" => [
                "enabled" => false,
            ],
            "preserveAdvancedTabs" => [
                "enabled" => false,
            ],
        ];
    }
}
