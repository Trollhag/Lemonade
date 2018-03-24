<?php
namespace Lemonade\Lemon;
use Lemonade\Users;
use Lemonade\DB;

class Lemon {
    public $status = [];
    public $isAdmin = false;
    public $post_types = [];
    public $routes = [];
    function __construct() {
        DB\Connect();        
        if (!defined("LEMONADE_I")) {
            $this->currentUser = new Users\User(['role' => -1]);
        }
        else {
            $this->currentUser = Users\User::currentUser();
        }
        $this->status['installed'] = defined("LEMONADE_I");
        $this->status['currentUser'] = $this->currentUser;
    }
}
$Lemon = new Lemon();