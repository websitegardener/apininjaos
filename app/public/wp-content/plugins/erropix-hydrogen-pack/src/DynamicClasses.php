<?php

namespace ERROPiX\HydrogenPack;

use Masterminds\HTML5;

class DynamicClasses
{
    use Utils;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        add_filter("do_shortcode_tag", [$this, "do_shortcode_tag"], 10, 3);
        add_action("wp", [$this, "add_advanced_tab"], 11);

        add_action("ct_toolbar_advanced_settings", [$this, "add_advanced_settings"], 11);
    }

    public function add_advanced_tab()
    {
        if (defined("SHOW_CT_BUILDER") && !defined("OXYGEN_IFRAME")) {
            global $oxygen_toolbar;
            $oxygen_toolbar->options['advanced']["dynamic-classes"] = [
                "heading"   => "Dynamic Classes",
                "tab_icon"  => "transform",
            ];
        }
    }

    public function add_advanced_settings()
    {
        include $this->get_template('toolbar/dynamic-classes');
    }

    public function do_shortcode_tag($output, $tag, $attr)
    {
        $ct_options = $attr["ct_options"] ?? null;

        if ($output && $ct_options) {
            $options = json_decode($ct_options, true);
            $dynmaic_classes = $options["original"]["dynamic-classes"] ?? null;

            if (is_array($dynmaic_classes) && !empty($dynmaic_classes)) {
                $rendered_classes = [];

                foreach ($dynmaic_classes as $class) {
                    $dynamic_data = $class["value"] ?? "";
                    $sanitize_data = $class["sanitize"] ?? false;

                    if ($dynamic_data) {
                        $dynamicData = new DynamicData($dynamic_data);
                        $class_string = $dynamicData->render();

                        if ($class_string && $sanitize_data) {
                            $class_string = sanitize_title($class_string);
                            $class_string = preg_replace("/^[\d-]+/", "", $class_string);
                        }

                        if ($class_string) {
                            $rendered_classes[] = $class_string;
                        }
                    }
                }

                if (!empty($rendered_classes)) {
                    $html = new HTML5();
                    $dom = $html->loadHTMLFragment($output);

                    if ($dom->childNodes->length) {
                        $node = $dom->childNodes->item(0);
                        $classStr = $node->getAttribute("class");

                        $classes = explode(" ", $classStr);
                        $classes = array_merge($classes, $rendered_classes);

                        $classStr = implode(" ", $classes);
                        $node->setAttribute("class", $classStr);

                        $output = $html->saveHTML($dom);
                    }
                }
            }
        }

        return $output;
    }
}
