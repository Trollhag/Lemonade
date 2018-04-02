<?php
namespace Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

if (User::isAdmin()) {
    require dirname(__FILE__) . "/admin-menu.php";
}