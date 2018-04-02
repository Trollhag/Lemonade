<?php
namespace Lemonade;

Options::register("site", true);
Options::autoload();

if (!defined("LEMONADE_I")) {
    Router::register("/", "Install", [ "installing" => true ]);
}

if (!User::$currentUser) { 
    Router::register("/lemonade/login", "login");
    Router::redirect("/lemonade/*", "/lemonade/login");
}
else {
    Router::register("/", "dashboard");
    Router::redirect("/lemonade/login", "/");
}

$Lemon->assets->enqueue("routes/js", "inline_js", "var routes = " . json_encode(Router::getRoutes()));
$Lemon->assets->enqueue('lemon/js', 'inline_js', "var lemon = " . json_encode([
    "status" => $Lemon->status,
    "options" => Options::_toFront()
]));

if (isAdmin()) {
    require ABSPATH . "/core/admin/setup.php";
}