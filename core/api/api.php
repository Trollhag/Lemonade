<?php
namespace Lemonade\API;
use Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

class API {
    private static $routes = [];

    public static function requestUri() {
        if (!static::isRequest()) return false;
        $uri = array_values(array_filter(explode('/', strtolower($_SERVER["REQUEST_URI"]))));
        return implode('/', array_slice($uri, 1));
    }

    protected static function isRequest() {
        $uri = array_values(array_filter(explode('/', strtolower($_SERVER["REQUEST_URI"]))));
        return count($uri) >= 2 && $uri[0] === 'api';
    }

    public static function register($url, $callback) {
        if (!is_string($url)) return false;
        static::$routes[$url] = $callback;
        if (static::requestUri() === strtolower($url)) {
            static::_request($url);
        }
    }

    protected static function _request($url) {
        http_response_code(400);
        $return = null;
        if (array_key_exists($url, static::$routes)) {
            http_response_code(200);
            $return = static::request($url);
            header("Content-Type: application/json; charset=utf-8");
        }
        die(json_encode($return));
    }

    public function request($url) {
        $return = null;
        if (array_key_exists($url, static::$routes)) {
            ob_start();
            $return = call_user_func(static::$routes[$url]);
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