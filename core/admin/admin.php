<?php
namespace Lemonade;

if (!defined("LEMONADE_V")) {
    die();
}

if (isAdmin()) {
    require dirname(__FILE__) . "/admin-menu.php";
}