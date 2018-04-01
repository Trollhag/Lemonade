<?php
namespace Lemonade;

function __session_start() {
    if (session_id() == '') {

        $session_name = SESSION_ID;   // Set a custom session name 
        // $secure = SECURE;
        // Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            
        }
        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], /*$secure*/ false, true);
        // Sets the session name to the one set above.
        session_name($session_name);
        session_start();            // Start the PHP session 
        session_regenerate_id();    // regenerated the session, delete the old one.
    } 
}

function loadAssets() {
    global $Lemon;
    $Lemon->assets->output();
}

function registerAsset($handle, $type, $data, $deps = []) {
    global $Lemon;
    $Lemon->assets->register($handle, $type, $data, $deps);
}

function enqueueAsset($handle, $type = null, $data = null, $deps = null) {
    global $Lemon;
    $Lemon->assets->enqueue($handle, $type, $data, $deps);
}

function isAdmin() {
    global $Lemon;
    return $Lemon->currentUser && ($Lemon->currentUser->role === -1 || $Lemon->currentUser->role === 2);
}

function slugify($title) {
    $title = $string;
    $title = preg_replace('~[^\\pL0-9_]+~u', '-', $title); // substitutes anything but letters, numbers and '_' with separator
    $title = trim($title, "-");
    $title = iconv("utf-8", "us-ascii//TRANSLIT", $title); // TRANSLIT does the whole job
    $title = strtolower($title);
    $title = preg_replace('~[^-a-z0-9_]+~', '', $title); // keep only letters, numbers, '_' and separator
    return $title;
}

function sanitize_post_input($name, $checks = [], $filters = [], $regex_filter = false) {
    $value = $_POST[$name];
    $valid = true;
    foreach ($checks as $c) {
        if (false == call_user_func($c, $value)) $valid = false;
    }
    if (!$valid) return null;
    foreach ($filters as $f) {
        $value = call_user_func($f, $value);
    }
    if ($regex_filter !== false) {
        $value = preg_replace($regex_filter, '', $value);
    }
    if (empty($value)) return null;
    return $value;
}