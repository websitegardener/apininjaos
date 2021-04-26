<?php

namespace ERROPiX\HydrogenPack;

/**
 * Trait Utils
 * @package ERROPiX\HydrogenPack
 */
trait Utils
{
    /**
     * @param string|null $path
     *
     * @return string
     */
    public function url($path = null)
    {
        $url = EPXHYDRO_URL . $path;
        if ($path) {
            $file = $this->path($path);
            if (is_file($file)) {
                $mtime = filemtime($file);
                $url = add_query_arg('mt', $mtime, $url);
            }
        }

        return $url;
    }

    /**
     * @param string|null $path
     *
     * @return string
     */
    public function path($path = null)
    {
        $base = EPXHYDRO_DIR;
        if (DIRECTORY_SEPARATOR !== '/') {
            $base = str_replace('\\', '/', $base);
        }
        return $base . trim($path);
    }

    /**
     * @param string $template
     */
    public function get_template($template)
    {
        $path = $this->path("templates/{$template}.php");

        if (is_readable($path)) {
            return $path;
        }
    }

    /**
     * @param mixed $value
     * 
     * @return string
     */
    public function encode($value)
    {
        $json = json_encode($value);
        $hash = base64_encode($json);
        return $hash;
    }

    /**
     * @param string $hash
     * 
     * @return mixed
     */
    public function decode($hash)
    {
        $json = base64_decode($hash);
        $value = json_decode($json, true);
        return $value;
    }

    /**
     * @param string|integer $key
     * @param mixed|null     $alt
     *
     * @return mixed|null
     */
    public function GET($key, $alt = null)
    {
        return $this->array_get($_GET, $key, $alt);
    }

    /**
     * @param string|integer $key
     * @param mixed|null     $alt
     *
     * @return mixed|null
     */
    public function POST($key, $alt = null)
    {
        return $this->array_get($_POST, $key, $alt);
    }

    /**
     * @param array          $array
     * @param string|integer $key
     * @param mixed|null     $alt
     *
     * @return mixed|null
     */
    public function array_get(array $array, $key, $alt = null)
    {
        if (isset($array[$key])) {
            return $array[$key];
        } else {
            if (strpos($key, '.')) {
                $keys = explode('.', $key);
                foreach ($keys as $k) {
                    if (is_array($array) && key_exists($k, $array)) {
                        $array = $array[$k];
                    } else {
                        return $alt;
                    }
                }
                return $array;
            }
        }

        return $alt;
    }

    /**
     * @param array          $array
     * @param string|integer $path
     * @param mixed|null     $value
     */
    public function array_set(array &$array, $path, $value)
    {
        $keys = explode('.', $path);

        $temp = &$array;
        foreach ($keys as $key) {
            if (!array_key_exists($key, $temp)) {
                $temp[$key] = null;
            }
            $temp = &$temp[$key];
        }

        $temp = $value;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    function array_extend(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_extend($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
