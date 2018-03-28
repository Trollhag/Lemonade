<?php
use Lemonade\Users\User;

if (!defined('LEMONADE_V')) {
    die(-1);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $current = User::currentUser();
    if ($current !== false) {
        http_response_code(400);
        die('Allready logged in');
    }

    $username = Lemonade\sanitize_post_input('username', ['is_string'], ['trim']);
    $password = Lemonade\sanitize_post_input('password', ['is_string']);

    if (!User::validate($username) && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        die("Invalid username or email");
    }
    $valid = User::login($username, $password);
    if ($valid === false) {
        http_response_code(400);
        die("Username and password dont match");
    }
    if (is_int($valid) && $valid < 0) {
        http_response_code(400);
        die(strval($valid));
    }
    die();
}