<?php 
namespace Lemonade;

/*
User roles:
-1: Super admin - All rights
 0: Subscriber - Zero admin rights, custom application stuff
 1: Editor - Partial admin rights
 2: Admin - Partial admin rights
*/

class User {
    private static $_cache = [];
    private static $_idMap = [];
    public static $currentUser = false;
    function __construct($user) {
        $this->ID = $user['ID'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->role = static::parseRole($user['role']);
        $this->memberSince = $user['timestamp'];
    }

    private static function cache($user) {
        if (!is_a($user, __CLASS__)) return;
        static::$_cache[$user->ID] = $user;
        static::$_cache[$user->email] = $user;
        static::$_cache[$user->username] = $user;
    }

    public static function isAdmin($user = false) {
        if (!$user) $user = static::currentUser();
        if (!is_a($user, __CLASS__)) {
            $user = static::get($user);
        }
        if (is_a($user, __CLASS__)) {
            return $user->role === -1 || $user->role === 2;
        }
        return false;
    }

    protected static function parseRole($role) {
        $r = intval($role);
        // Type jugle comparrison, in case some string or non-string/int would be parsed incorrectly
        if ($r != $role || (!is_int($role) && !is_string($role))) return 0;
        return $r;
    }

    public static function validate($username) {
        return $username === static::sanitize($username);
    }

    public static function sanitize($username) {
        return preg_replace('/[^a-zA-Z0-9-_]/', '', $username);
    }

    public static function checkToken($username, $hash) {
        if (!static::validate($username)) return false;
        $user_hash = Lemon::$db->get('lemonade_users', 
            'password',
            ['username' => $username]
        );
        if (Lemon::$db->hasError() || !$user_hash || empty($user_hash) || !is_string($user_hash)) return false;
        return $hash === hash('sha512', $user_hash . $_SERVER['HTTP_USER_AGENT']);
    }

    public static function login($username, $password) {
        if (!static::validate($username)) return -1;
        $user_hash = Lemon::$db->get('lemonade_users', 
            'password',
            [
                "OR" => [
                    'username' => $username,
                    'email' => $username
                ]
            ]
        );
        if (Lemon::$db->hasError()) return false;
        if (!$user_hash || empty($user_hash)) return -2;
        if (password_verify($password, $user_hash)) {
            __session_start();
            $_SESSION['username'] = $username;
            $_SESSION['hash'] = hash('sha512', $user_hash . $_SERVER['HTTP_USER_AGENT']);
            return true;
        }
        return -3;
    }
    
    public static function get($user) {
        // TODO: Rewrite validation
        // - Check if user is cached
        if (is_int($user)) {
            $where = ['ID' => $user];
        }
        else if (static::validate($user)) {
            $where = [
                "OR" => [
                    'username' => $user,
                    'email' => $user
                ]
            ];
        }
        else return false;
        $_user = Lemon::$db->get('lemonade_users', 
            ['ID', 'username', 'email', 'role', 'timestamp'],
            $where
        );
        if (Lemon::$db->hasError() || !$_user || empty($_user)) return false;
        $_user = new static($_user);
        static::cache($_user);
        return $_user;
    }
    
    public static function register($username, $password, $email, $role = 0) {
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

        $exists = Lemon::$db->select('lemonade_users', ['username', 'email', 'role'], $where);
        
        if (Lemon::$db->hasError()) return -1;
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
        Lemon::$db->insert('lemonade_users', [
            'username'  => $username,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
            'email'     => $email,
            'role'      => $role
        ]);
        if (Lemon::$db->hasError()) return -1;
        return Lemon::$db->id(); // Returns new user ID
    }
    
    public static function currentUser() {        
        if (static::$currentUser) return static::$currentUser;
        __session_start();
        if (isset($_SESSION['username']) && isset($_SESSION['hash'])) {
            $isUser = false;
            if (static::validate($_SESSION['username'])) {
                $isUser = static::checkToken($_SESSION['username'], $_SESSION['hash']);
            }
            if ($isUser) {
                static::$currentUser = static::get($_SESSION['username']);
                return static::$currentUser;
            }
            else {
                unset($_SESSION['username']);
                unset($_SESSION['hash']);
            }
        }
        return false;
    }

    public static function Setup() {
        global $Lemon;
        if (User::isAdmin()) {
            $Lemon->AdminMenu->register('users', '/lemonade/users', 'Users');
            Router::register('/lemonade/users', 'lemonade-list', [
                "type" => "users",
                "posts" => Lemon::$db->select('lemonade_users', ['ID', 'Username'])
            ]);
            Router::register('/lemonade/users/:ID', 'lemonade-user');
        }
    }
}
API::register('admin', function() {
    return User::isAdmin();
});
API::register('users/(\d+)', function($id) {
    if (User::isAdmin() && $id) {
        return User::get($id);
    }
});