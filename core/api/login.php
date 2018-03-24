<?php
if (!defined('ABSPATH')) {
    die(-1);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$username = 'username';
$password = 'password';
 
$random1 = 'secret_key1';
$random2 = 'secret_key2';
 
$hash = md5($random1.$pass.$random2); 
 
$self = $_SERVER['REQUEST_URI'];