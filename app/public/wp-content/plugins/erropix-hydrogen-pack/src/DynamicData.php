<?php

namespace ERROPiX\HydrogenPack;

class DynamicData
{
    /**
     * Dynmaic data string
     * @var string
     */
    private $data = "";

    /**
     * Class constructor.
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Render dynamic data shortcode
     * @return string|null
     */
    public function render()
    {
        $output = "";

        if (strpos($this->data, 'dyndata_') !== false) {
            $output = preg_replace_callback("#dyndata_([A-Za-z0-9+/]+={0,2})#", [$this, "render_pattern"], $this->data);
        }

        return $output;
    }

    /**
     * Render encoded shortcode string
     * @param array $matches encoded shortcode string
     * @return string 
     */
    public function render_pattern(array $matches)
    {
        $shortcode = base64_decode($matches[1]);
        $shortcode = $this->sign_shortcode($shortcode);
        return @do_shortcode($shortcode);
    }

    /**
     * Sign dynamic data shortcodes before rendering
     */
    private function sign_shortcode($shortcode)
    {
        global $oxygen_signature;

        // Replace escaped characters
        $shortcode = str_replace('\"', '"', $shortcode);

        // Parse shortcode
        $pattern = get_shortcode_regex(['oxygen']);
        if (preg_match("/$pattern/", $shortcode, $matches)) {
            // Parse attributes
            $tag = $matches[2];
            $atts = trim($matches[3]);
            $args = shortcode_parse_atts($atts);

            // Generate signature attribute
            $signature = $oxygen_signature->generate_signature_shortcode_string($tag, $args, null);

            // Recreate shortcode string
            $shortcode = "[$tag $signature $atts]";
        }

        return $shortcode;
    }
}
