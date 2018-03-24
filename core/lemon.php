<?php
namespace Lemonade\Lemon;
use Lemonade\Users\User;
use Lemonade\DB;

class Lemon {
    public $db;
    public $router;
    public $options;
    public $status = [];
    public $isAdmin = false;
    public $post_types = [];
    public $routes = [];
    function __construct() {
        global $Lemon;
        $Lemon = $this;
        DB\Connect();        
        if (!defined("LEMONADE_I")) {
            $this->currentUser = new User(['role' => -1]);
        }
        else {
            $this->currentUser = User::currentUser();
        }
        $this->status['installed'] = defined("LEMONADE_I");
        $this->status['currentUser'] = $this->currentUser;
    }
}
new Lemon();