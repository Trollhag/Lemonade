<?php
namespace Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

class Options {
    private static $tablename = "lemonade_options";
    public $options = [];

    function __construct() {
    }

    public static function register($name, $autoload = false, $front = true) {
        global $Lemon;
        if (array_key_exists($name, $Lemon->options->options)) {
            return false;
        }
        $Lemon->options->options[$name] = [
            "autoload" => $autoload,
            "front" => $front
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
            $option["value[JSON]"] = $value;
            $Lemon->db->insert(static::$tablename, $option);
        }
        else {
            if ($option["value"] !== $value) {
                $Lemon->db->update(static::$tablename, ["value[JSON]" => $value], ["name" => $name]);
            }
        }
        $error = $Lemon->db->error();
        if ($error && !empty($error)) return $error;
        return $value;
    }
    public static function load($name) {
        global $Lemon;
        $option = $Lemon->db->get(static::$tablename, ["ID", "value[JSON]", "autoload"], ["name" => $name]);
        if ($option) {
            $Lemon->options->options[$name] = $option;
            return $option;
        }
        else {
            return false;
        }
    }
    public static function autoload() {
        global $Lemon;
        foreach ($Lemon->options->options as $name=>$option) {
            if (!array_key_exists("ID", $option) && $option["autoload"]) {
                static::load($name);
            }
        }
    }
    public static function _toFront() {
        global $Lemon;
        $return = [];
        foreach ($Lemon->options->options as $name=>$option) {
            if ($option["autoload"] && $option["front"]) {
                $return[$name] = static::_clean($option);
            }
        }
        return $return;
    }
    protected static function _clean($value) {
        if (array_key_exists("value", $value) && is_array($value["value"])) {
            $value["value"] = static::_clean($value["value"]);
        }
        return $value;
    }
}