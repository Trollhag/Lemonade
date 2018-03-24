<?php
namespace Lemonade\API;
use Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

function is_request() {
    $uri = array_values(array_filter(explode('/', strtolower($_SERVER["REQUEST_URI"]))));

    if (count($uri) >= 2 && $uri[0] === 'api') {
        switch ($uri[1]) {
            case 'install':
                require dirname(__FILE__) . "/install.php";
                break;
            case 'login':
                require dirname(__FILE__) . "/login.php";
                break;
            case 'admin':
                header("Content-type: application/json; charset=utf-8");
                die(json_encode(Lemonade\isAdmin()));
                break;
        }
        die();
    }
}
is_request();