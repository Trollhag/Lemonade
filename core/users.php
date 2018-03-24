<?php 
namespace Lemonade\Users;
use Lemonade;

/*
User roles:
-1: Super admin - All rights
 0: Subscriber - Zero admin rights, custom application stuff
 1: Editor - Partial admin rights
 2: Admin - Partial admin rights
*/

class User {
    public $role; 
    function __construct($user) {
        $this->ID = $user['ID'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->role = $user['role'];
        $this->memberSince = $user['timestamp'];
    }

    public static function validate($username) {
        return $username === static::sanitize($username);
    }

    public static function sanitize($username) {
        return preg_replace('/[^a-zA-Z-_]/', '', $username);
    }

    public static function checkUser($username, $hash) {
        global $Lemon;
        if (!static::validate($username)) return false;
        $user_hash = $Lemon->db->get('lemonade_users', 
            'password',
            ['username' => $username]
        );
        $errors = $Lemon->db->error();
        if ($errors && !empty($errors)) return false;
        if (!$user_hash || empty($user_hash)) return false;
        return $hash === hash('sha512', $user_hash . $_SERVER['HTTP_USER_AGENT']);
    }
    
    public static function getUser($user) {
        global $Lemon;
        if (is_int($user)) {
            $where = ['ID' => $user];
        }
        else if (static::validate($user)) {
            $where = ['username' => $user];
        }
        else return false;
        $_user = $Lemon->db->get('lemonade_users', 
            ['ID', 'username', 'email', 'role', 'timestamp'],
            $where
        );
        $errors = $Lemon->db->error();
        if ($errors && !empty($errors)) return false;
        if (!$_user || empty($_user)) return false;
        return new static($_user);
    }
    
    public static function register($username, $password, $email, $role = 0) {
        global $Lemon;
        $where = [
            'OR' => [
                'username'  => $username,
                'email'     => $email
            ]
        ];
        // Trying to set SuperAdmin
        if ($role === -1) {
            $where['OR']['role'] = -1;
        }

        $exists = $Lemon->db->select('lemonade_users', ['username', 'email', 'role'], $where);
        
        if ($exists && !empty($exists)) {
            $username_unused = true;
            $email_unused = true;
            $superadmin_exists = false;
            foreach ($exists as $user) {
                if ($username === $user['username']) $username_unused = false;
                if ($email === $user['email']) $email_unused = false;
                if ($role === -1 && $user['role'] === -1) $superadmin_exists = true; 
            }
            
            if (!$username_unused) {
                return -2;
            }
            else if (!$email_unused) {
                return -3;
            }
            else if ($role === -1 && $superadmin_exists) {
                $role = 2;
            }
        }
        $Lemon->db->insert('lemonade_users', [
            'username'  => $username,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
            'email'     => $email,
            'role'      => $role
        ]);
        $errors = $Lemon->db->error();
        if ($errors && !empty($errors)) return -1; // Returns -1 on failure
        return $Lemon->db->id(); // Returns new user ID
    }
    
    public static function currentUser() {
        global $Lemon;
        
        if (session_status() == PHP_SESSION_NONE) {
            Lemonade\__session_start();
        }
        if (isset($Lemon->currentUser)) return $Lemon->currentUser;
        if (isset($_SESSION['username']) && isset($_SESSION['hash'])) {
            $isUser = false;
            if (static::validate($_SESSION['username'])) {
                $isUser = static::checkUser($_SESSION['username'], $_SESSION['hash']);
            }
            if ($isUser) {
                return static::getUser($_SESSION['username']);
            }
            else {
                unset($_SESSION['username']);
                unset($_SESSION['hash']);
            }
        }
        return false;
    }
}