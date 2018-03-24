<?php

use Lemonade\DB;
use Lemonade\Users;
use Lemonade\Options;

if (!defined("LEMONADE_V")) {
    die();
}

if (file_exists(ABSPATH . "/core/config.php")) {
    http_response_code(403);
    die("Allready installed");
}
if (!file_exists(ABSPATH . '/core/config-sample.php')) {
    http_response_code(500);
    die('Missing required config sample file, contact your technical go to person and they\'ll fix it.');
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $test_db = filter_input(INPUT_POST, 'test_db', FILTER_VALIDATE_BOOLEAN);

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
    $data = [];
    $data['db_host']        = sanitize_post_input('db_host', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-\.]/');
    $data['db_port']        = sanitize_post_input('db_port', ['is_string'], ['trim', 'stripslashes'], '/[^0-9]/');
    $data['db_user']        = sanitize_post_input('db_user', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-_]/');
    $data['db_password']    = sanitize_post_input('db_password', ['is_string']);
    $data['db_name']        = sanitize_post_input('db_name', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-_]/');
    $data['db_prefix']      = sanitize_post_input('db_prefix', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-_]/');

    $data['title']          = sanitize_post_input('title', ['is_string'], ['trim']);
    $data['base_url']       = sanitize_post_input('base_url', ['is_string'], ['trim'], '/[^a-zA-Z0-9\/]/');

    $data['username']       = sanitize_post_input('username', ['is_string'], ['trim'], '/[^a-zA-Z0-9-_]/');
    $data['password']       = sanitize_post_input('password', ['is_string']);
    $data['email']          = sanitize_post_input('email', ['is_string'], ['trim']);
    if (!is_null($data['email'])) {
        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    }

    $db_test = DB\try_connect($data['db_host'], $data['db_port'], $data['db_user'], $data['db_password'], $data['db_name']);
    if (!is_array($db_test)) {
        die($db_test);
    }
    if (true == $test_db) {
        die(json_encode($db_test));
    }

    $errors = [];
    $invalid = false;
    foreach($data as $n=>$d) {
        if (!$d) {
            $invalid = true;
            $errors[$n] = 'Something looks wrong about this field';
        }
    }
    if ($invalid) {
        http_response_code(406);
        die(json_encode($errors));
    }

    define("LEMONADE_INSTALLING", true);
    define("SESSION_ID", mt_rand() . '_SID');
    define('DB_HOST', $data['db_host']);
    define('DB_PORT', intval($data['db_port']));
    define('DB_USER', $data['db_user']);
    define('DB_PASS', $data['db_pass']);
    define('DB_NAME', $data['db_name']);
    define('DB_PREFIX', $data['db_prefix']);
    $config = sprintf(file_get_contents(ABSPATH . "/core/config-sample.php"), [
        SESSION_ID,
        DB_HOST,
        DB_PORT,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PREFIX
    ]);
    $config = preg_replace('/\/\* Install begin|Install end \*\//i', '', $config);
    file_put_contents(ABSPATH . "/core/config.php", $config);
    
    if (!file_exists(ABSPATH . "/core/config.php")) {
        http_response_code(500);
        die("Failed to create config file.");
    }
    if (true === DB\connect()) {
        DB\install();
        // Adding SuperAdmin
        $result = Users\User::register($data['username'], $data['password'], $data['email'], -1);
        if (!is_int($result) || $result < 0) {
            http_response_code(500);
            die($result);
        }
    }
    else {
        http_response_code(500);
        die("Failed to connect to database or create tables.");
    }
    Options\Options::set("site", [
        "title" => $data["title"],
        "baseurl" => $data["baseurl"]
    ]);
    die(true);
}