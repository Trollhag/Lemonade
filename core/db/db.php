<?php
namespace Lemonade\DB;
use Medoo\Medoo;

function try_connect($host, $port, $username, $password, $dbname) {
    $conn = new Medoo([
        'database_type' => 'mysql', 
        'server' => $host,
        'port' => $port,
        'username' => $username, 
        'password' => $password, 
        'database_name' => $dbname
    ]);
    // Check connection
    if (!$conn || ($conn->error() && !empty($conn->error()))) {
        return "Connection failed: " . implode(' ', $conn->error());
    }
    return $conn->info();
}
function connect() {
    if (defined("DB_PREFIX") && defined("DB_HOST") && defined("DB_PORT") && defined("DB_USER") && defined("DB_PASS") && defined("DB_NAME")) {
        global $Lemon;
        $Lemon->db = new Medoo([
            'database_type' => 'mysql', 
            'server' => DB_HOST,
            'port' => intval(DB_PORT),
            'username' => DB_USER, 
            'password' => DB_PASS, 
            'database_name' => DB_NAME,
            'prefix' => DB_PREFIX
        ]);
        // Check connection
        if (!$Lemon->db || ($Lemon->db->error() && !empty($Lemon->db->error()))) {
            $Lemon->db = false;
        }
        else {
            return true;
        }
    }
    return false;
}

function install() {
    global $Lemon;
    if ($Lemon->db === false) return false;
    $prefix = DB_PREFIX;
    
    // Users table
    $users = $Lemon->db->query(
        "CREATE TABLE IF NOT EXISTS <{$prefix}lemonade_users> (
            <ID> INT NOT NULL AUTO_INCREMENT, 
            <username> VARCHAR(50) NOT NULL, 
            <password> TEXT NOT NULL, 
            <email> VARCHAR(50) NOT NULL, 
            <role> INT NOT NULL DEFAULT 0, 
            <timestamp> TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
            PRIMARY KEY (<ID>), 
            UNIQUE (<username>), 
            UNIQUE (<email>)
        );"
    );

    // Posts table
    $Lemon->db->query(
        "CREATE TABLE IF NOT EXISTS <{$prefix}lemonade_posts> (
            <ID> INT NOT NULL AUTO_INCREMENT, 
            <type> TEXT NOT NULL, 
            <status> VARCHAR(50) NOT NULL, 
            <fields> TEXT NOT NULL, 
            <user> INT NOT NULL, 
            <timestamp> TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
            PRIMARY KEY (<ID>)
        );"
    );

    // Options table
    $Lemon->db->query(
        "CREATE TABLE IF NOT EXISTS <{$prefix}lemonade_options> (
            <ID> INT NOT NULL AUTO_INCREMENT, 
            <name> VARCHAR(50) NOT NULL, 
            <value> TEXT, 
            <autoload> INT NOT NULL DEFAULT 1, 
            PRIMARY KEY (<ID>),
            UNIQUE (<name>)
        );"
    );
}