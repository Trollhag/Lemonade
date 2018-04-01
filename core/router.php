<?php
namespace Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

class Router {
    protected $routes = [];

    function __construct() {
    }

    public static function register($route, $component, $data = []) {
        global $Lemon;
        $Lemon->router->routes[$route] = [
            'component' => $component,
            'meta' => $data
        ];
    }
    public static function redirect($route, $redirect) {
        global $Lemon;
        $Lemon->router->routes[$route] = [
            "redirect" => $redirect
        ];
    }
    public static function getRoutes() {
        global $Lemon;
        $routes = [];
        foreach ($Lemon->router->routes as $route=>$data) {
            $routes[] = array_merge([
                'path' => $route
            ], $data);
        }
        return $routes;
    }
}
