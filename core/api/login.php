<?php
use Lemonade\API\API;
use Lemonade\Users\User;

if (!defined('LEMONADE_V')) {
    die();
}

API::register('login', function() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $current = User::currentUser();
        if ($current !== false) {
            http_response_code(400);
            return 'Allready logged in';
        }

        $username = Lemonade\sanitize_post_input('username', ['is_string'], ['trim']);
        $password = Lemonade\sanitize_post_input('password', ['is_string']);

        if (!User::validate($username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return "Invalid username or email";
        }
        $valid = User::login($username, $password);
        if ($valid === false) {
            http_response_code(400);
            return "Username and password dont match";
        }
        if (is_int($valid) && $valid < 0) {
            http_response_code(400);
            return strval($valid);
        }
        return true;
    }
    http_response_code(400);
});