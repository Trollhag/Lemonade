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
    if (defined("DB_PREFIX") && defined("DB_HOST") && defined("DB_USER") && defined("DB_PASS") && defined("DB_NAME")) {
        global $Lemon;
        $conn = new Medoo([
            'database_type' => 'mysql', 
            'server' => DB_HOST,
            'port' => 3306,
            'username' => DB_USER, 
            'password' => DB_PASS, 
            'database_name' => DB_NAME,
            'prefix' => DB_PREFIX
        ]);
        // Check connection
        if (mysqli_connect_error()) {
            $Lemon->db = false;
        }
        else {
            $Lemon->db = $conn;
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
    $Lemon->db->query("CREATE TABLE IF NOT EXISTS `{$prefix}lemonade_users` ( `ID` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR NOT NULL , `password` TEXT NOT NULL , `email` VARCHAR NOT NULL , `role` INT NOT NULL DEFAULT '0' , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`ID`), UNIQUE (`username` ), UNIQUE (`email`)) ENGINE = InnoDB;");

    // Posts table
    $Lemon->db->query("CREATE TABLE IF NOT EXISTS `lemonade`.`{$prefix}lemonade_posts` ( `ID` INT NOT NULL AUTO_INCREMENT , `type` VARCHAR NOT NULL, `status` VARCHAR NOT NULL , `fields` TEXT NOT NULL , `user` INT NOT NULL, `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`ID`)) ENGINE = InnoDB;");

    // Options table
    $Lemon->db->query("CREATE TABLE IF NOT EXISTS `lemonade`.`{$prefix}lemonade_options` ( `ID` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR NOT NULL , `value` TEXT , `autoload` INT NOT NULL DEFAULT 1 , PRIMARY KEY (`ID`)) , UNIQUE (`name`) ENGINE = InnoDB;");
}