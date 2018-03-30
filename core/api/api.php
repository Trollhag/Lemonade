<?php
namespace Lemonade\API;
use Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

class API {
    private static $routes = [];

    protected static function cleanSplit($uri) {
        return array_values(
            array_filter(
                explode('/', strtolower($uri))
            )
        );
    }

    public static function requestUri() {
        $uri = static::cleanSplit($_SERVER["REQUEST_URI"]);
        return array_slice($uri, 1);
    }

    protected static function isRequest() {
        $uri = static::cleanSplit($_SERVER["REQUEST_URI"]);
        return count($uri) >= 2 && $uri[0] === 'api';
    }

    public static function register($url, $callback) {
        if (!static::isRequest()) return;
        if (!is_string($url)) return false;
        static::$routes[$url] = $callback;
        $a = static::requestMatch($url);
        if (is_array($a)) {
            static::_request($url, $a);
        }
    }

    protected static function requestMatch($route) {
        $uri = static::requestUri();
        $r = static::cleanSplit($route);
        $args = [];
        foreach ($uri as $i=>$u) {
            $matches = [];
            if (preg_match("/^".$r[$i]."$/", $u, $matches)) {
                if (count($matches) > 1) $args[] = $matches[1];
            }
            else return null;
        }
        return $args;
    }

    protected static function _request($url, $arguments) {
        http_response_code(400);
        $return = null;
        if (array_key_exists($url, static::$routes)) {
            http_response_code(200);
            $return = static::request($url, $arguments);
            header("Content-Type: application/json; charset=utf-8");
        }
        die(json_encode($return));
    }

    public function request($url, $arguments) {
        $return = null;
        if (array_key_exists($url, static::$routes)) {
            ob_start();
            $return = call_user_func_array(static::$routes[$url], $arguments);
            ob_end_clean(); // Discard any and all outputs
        }
        return $return;
    }
}

API::register('admin', function() {
    return Lemonade\isAdmin();
});

if (!defined("LEMONADE_I")) {
    require dirname(__FILE__) . "/install.php";
}
require dirname(__FILE__) . "/login.php";