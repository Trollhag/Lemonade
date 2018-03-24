<?php
define('LEMONADE_V', '0.0.0');

if (!file_exists(dirname(__FILE__) . "/config.php")) {
    require_once dirname(__FILE__) . "/config-sample.php";
}
else {
    require_once dirname(__FILE__) . "/config.php";
}

ini_set('display_errors', 'Off');
if (defined('DEBUG') && DEBUG === true) {
    ini_set('display_errors', 'On');
}

function vueson_debug_log($message) {
    if (defined('DEBUG_LOG') && DEBUG_LOG === true && defined('DEBUGLOGFILE')) {
        $datetime = date('U Y-m-d HH:mm:ss');
        error_log("[{$datetime}] {$message}\n", 3, DEBUGLOGFILE);
    }
}

require_once ABSPATH . "/vendor/autoload.php";
require_once ABSPATH . "/core/db/db.php";
require_once ABSPATH . "/core/helpers.php";
require_once ABSPATH . "/core/users.php";
require_once ABSPATH . "/core/lemon.php";
require_once ABSPATH . "/core/options.php";
require_once ABSPATH . "/core/post-setup/post-setup.php";
require_once ABSPATH . "/core/router.php";
require_once ABSPATH . "/core/api/api.php";
require_once ABSPATH . "/core/assets.php";
require_once ABSPATH . "/core/setup.php";