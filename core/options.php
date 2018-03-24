<?php
namespace Lemonade\Options;

if (!defined("LEMONADE_V")) {
    die();
}

class Options {
    private static $tablename = "lemonade_options";
    protected $options = [];

    function __construct() {
        global $Lemon;
        $Lemon->options = $this;

        static::register("site", true);
    }

    public static function register($name, $autoload = false) {
        global $Lemon;
        if (array_key_exists($name, $Lemon->options->options)) {
            return false;
        }
        $Lemon->options->options[$name] = [
            'autoload' => $autoload
        ];
    }
    public static function get($name) {
        global $Lemon;
        if (!array_key_exists($name, $Lemon->options->options)) {
            return null;
        }
        if (!array_key_exists("ID", $Lemon->options->options[$name])) {
            $option = static::load($name);
            if ($option) {
                return $option["value"];
            }
        }
        return $Lemon->options->options[$name]["value"];
        
    }
    public static function set($name, $value) {
        global $Lemon;
        if (!array_key_exists($name, $Lemon->options->options)) {
            return null;
        }
        $option = static::load($name);
        if (!$option) {
            $option = $Lemon->options->options[$name];
            $option["name"] = $name;
            $option["value"] = $value;
            $Lemon->db->insert(static::$tablename, $option);
        }
        else {
            if ($option["value"] !== $value) {
                $Lemon->db->update(static::$tablename, ["value" => $value], ["name" => $name]);
            }
        }
        $error = $Lemon->db->error();
        if ($error && !empty($error)) return $error;
        return $value;
    }
    public static function load($name) {
        global $Lemon;
        $option = $Lemon->db->get(static::$tablename, ["ID", "value", "autoload"], ["name" => $name]);
        if ($option) {
            $Lemon->options->options[$name] = $option;
            return $option;
        }
        else {
            return false;
        }
    }
}
new Options();