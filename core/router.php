<?php
namespace Lemonade\Router;
use Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

class Router {
    protected $routes = [];

    function __construct() {
        global $Lemon;
        $Lemon->router = $this;
    }

            'component' => $component,
            'meta' => $data
        ];
    }
    public function registerAdminRoute($route, $component, $data = []) {
        global $Lemon;
        if (defined("LEMONADE_I") && $Lemon->isAdmin) {
            $this->routes[$route] = [
                'component' => $component,
                'meta' => $data
            ];
        }
    }
    public static function getRoutes() {
        $routes = [];
        foreach ($this->routes as $route=>$data) {
            $routes[] = array_merge([
                'path' => $route
            ], $data);
        }
        return $routes;
    }
}
new Router();
