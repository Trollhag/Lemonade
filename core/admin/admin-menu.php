<?php
namespace Lemonade;

class AdminMenu {
    protected static $menus = [];

    protected $name = "";
    protected $items = [];

    function __construct($name) {
        $this->name = $name;
        static::$menus[$name] = $this;
    }

    public function register($name, $route, $text, $icon = false) {
        $this->items[$name] = [
            "name"  => $name,
            "route" => $route,
            "text"  => $text,
            "icon"  => $icon,
        ];
    }

    public static function getItems($name) {
        if (array_key_exists($name, static::$menus))
            return static::$menus[$name]->items;

        return false;
    }

    public static function Setup() {
        API::register("adminmenu/([\w\-\_]+)", function($name) {
            if (User::isAdmin())
                return array_values(AdminMenu::getItems($name));
            
            http_response_code(403);
            return false;
        });
    }
}
