<?php
namespace Lemonade\Setup;

$Lemon->router->register("/", "Install", [ "installing" => true ]);
$Lemon->assets->enqueue("routes/js", "inline_js", "var routes = " . json_encode($Lemon->router->getRoutes()));