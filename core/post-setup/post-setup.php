<?php
namespace Lemonade;

class PostBase {
    public static $fields = [];
    function __construct() {

    }
    public static function addField($name, $class) {
        if (class_exists($class)) {
            self::$fields[$name] = $class;
        }
    }
    public static function removeField($name) {
        if (array_key_exists($name, self::$fields)) {
            unset(self::$fields[$name]);
        }
    }
}

require_once dirname(__FILE__) . "/page.php";