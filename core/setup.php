<?php
namespace Lemonade;

Options::autoload();

if (!defined("LEMONADE_I")) {
    Router::register("/", "Install", [ "installing" => true ]);
}
$Lemon->assets->enqueue("routes/js", "inline_js", "var routes = " . json_encode(Router::getRoutes()));
$Lemon->assets->enqueue('lemon/js', 'inline_js', "var lemon = " . json_encode([
    "status" => $Lemon->status,
    "options" => Options::_toFront()
]));