<?php
use Lemonade\API\API;
use Lemonade\DB;
use Lemonade\Users;
use Lemonade\Options;

if (!defined("LEMONADE_V")) {
    die();
}

API::register('install', function() {
    if (file_exists(ABSPATH . "/core/config.php")) {
        http_response_code(403);
        return "Allready installed";
    }
    if (!file_exists(ABSPATH . '/core/config-sample.php')) {
        http_response_code(500);
        return 'Missing required config sample file';
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $test_db = filter_input(INPUT_POST, 'test_db', FILTER_VALIDATE_BOOLEAN);

        $data = [];
        $data['db_host']        = Lemonade\sanitize_post_input('db_host', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-\.]/');
        $data['db_port']        = Lemonade\sanitize_post_input('db_port', ['is_string'], ['trim', 'stripslashes'], '/[^0-9]/');
        $data['db_user']        = Lemonade\sanitize_post_input('db_user', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-_]/');
        $data['db_password']    = Lemonade\sanitize_post_input('db_password', ['is_string']);
        $data['db_name']        = Lemonade\sanitize_post_input('db_name', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-_]/');
        $data['db_prefix']      = Lemonade\sanitize_post_input('db_prefix', ['is_string'], ['trim', 'stripslashes'], '/[^a-zA-Z0-9-_]/');

        $data['title']          = Lemonade\sanitize_post_input('title', ['is_string'], ['trim']);
        $data['base_url']       = Lemonade\sanitize_post_input('base_url', ['is_string'], ['trim'], '/[^a-zA-Z0-9\/]/');

        $data['username']       = Lemonade\sanitize_post_input('username', ['is_string'], ['trim'], '/[^a-zA-Z0-9-_]/');
        $data['password']       = Lemonade\sanitize_post_input('password', ['is_string']);
        $data['email']          = Lemonade\sanitize_post_input('email', ['is_string'], ['trim']);
        if (!is_null($data['email'])) {
            $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        }

        $db_test = DB\try_connect($data['db_host'], $data['db_port'], $data['db_user'], $data['db_password'], $data['db_name']);
        if (true == $test_db || !is_array($db_test)) {
            return $db_test;
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
            http_response_code(400);
            return $errors;
        }

        define("LEMONADE_INSTALLING", true);
        define("SESSION_ID", mt_rand() . '_SID');
        define('DB_HOST', $data['db_host']);
        define('DB_PORT', intval($data['db_port']));
        define('DB_USER', $data['db_user']);
        define('DB_PASS', $data['db_password']);
        define('DB_NAME', $data['db_name']);
        define('DB_PREFIX', $data['db_prefix']);
        
        if (true === DB\connect()) {
            DB\install();
            // Adding SuperAdmin
            $result = Users\User::register($data['username'], $data['password'], $data['email'], -1);
            if (!is_int($result)) {
                return $result;
            }
        }
        else {
            http_response_code(500);
            return "Failed to connect to database or create tables.";
        }
        Options\Options::set("site", [
            "title" => $data["title"],
            "base_url" => $data["base_url"]
        ]);

        $config = file_get_contents(ABSPATH . "/core/config-sample.php");
        $config = sprintf(
            $config,
            SESSION_ID,
            DB_HOST,
            DB_PORT,
            DB_USER,
            DB_PASS,
            DB_NAME,
            DB_PREFIX
        );
        $config = preg_replace('/\/\* Install begin|Install end \*\//i', '', $config);
        file_put_contents(ABSPATH . "/core/config.php", $config);
        if (!file_exists(ABSPATH . "/core/config.php")) {
            http_response_code(500);
            return "Failed to create config file.";
        }

        return true;
    }
    http_response_code(500);
});