<?php

namespace ERROPiX\HydrogenPack;

/**
 * Class UI
 * @package ERROPiX\HydrogenPack
 */
class UI
{
    public function nameToId($name)
    {
        $name = trim($name, '[]');
        $name = str_replace('[', '_', $name);
        $name = str_replace(']', '', $name);
        return strtolower($name);
    }

    public function input($name, $value, $type = 'text', $attrs = false)
    {
        $id = $this->nameToId($name);

        $extra_attrs = "";
        if (is_array($attrs)) {
            foreach ($attrs as $attr_name => $attr_value) {
                $extra_attrs .= " $attr_name='$attr_value'";
            }
        }

        $html = "<input type='$type' id='$id' name='$name' value='$value'$extra_attrs>";

        return $html;
    }

    public function checkbox($name, $value, $labelOn = "Enabled", $labelOff = "Disabled", $readonly = false)
    {
        $tag = 'label';
        $attributes = [''];
        $classes = ['checkbox'];

        if ($value) {
            $attributes[] = 'checked';
        }

        if ($readonly) {
            $tag = 'div';
            $attributes[] = 'tabindex="-1"';
            $classes[] = 'checkbox-readonly';
        }


        $attributes = implode(' ', $attributes);
        $classes = implode(' ', $classes);

        $id = $this->nameToId($name);

        $html = "";
        $html .= "<$tag class='$classes'>";
        $html .=    "<input type='hidden' name='$name' value='false'>";
        $html .=    "<input type='checkbox' id='$id' name='$name' value='true'$attributes>";
        $html .=    "<div class='checkbox-inner'>";
        $html .=        "<div class='checkbox-status'></div>";
        $html .= $labelOn ? "<span class='checkbox-label-on'>$labelOn</span>" : "";
        $html .= $labelOn ? "<span class='checkbox-label-off'>$labelOff</span>" : "";
        $html .=    "</div>";
        $html .= "</$tag>";

        return $html;
    }

    public function options($choices, $value = null)
    {
        $html = '';
        if (is_array($choices)) {
            foreach ($choices as $key => $item) {
                if (is_array($item)) {
                    $inner_options = $this->options($item, $value);
                    $html .= sprintf('<optgroup label="%s">%s</optgroup>', $key, $inner_options);
                } else {
                    $matched = false;
                    if (is_array($value)) {
                        $matched = in_array($key, $value);
                    } else {
                        if (is_int($key) && is_string($value) && is_numeric($value)) {
                            $value = intval($value);
                        }

                        $matched = $key === $value;
                    }
                    $selected = $matched ? ' selected' : '';

                    $html .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $item);
                }
            }
        }

        return $html;
    }

    public function popper($content, $position = 'right')
    {
        $html = "";
        $html .= "<a href='#' data-popper='$position'>?</a>";
        $html .= "<div class='popper' style='display:none;'>";
        $html .=    "<div data-popper-arrow></div>";
        $html .=    "<p>$content</p>";
        $html .= "</div>";

        return $html;
    }
}
