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
        $this->items[$name] = func_get_args();
    }

    public static function getItems($name) {
        if (array_key_exists($name, static::$items))
            return static::$items[$name]->items;

        return false;
    }
}

API::register("adminmenu/([\w\-\_]+)", function($name) {
    if (isAdmin())
        return AdminMenu::getItems($name);

    
    http_response_code(403);
    return false;
});
