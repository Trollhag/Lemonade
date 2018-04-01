<?php
namespace Lemonade;

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
        dbConnect();        
        if (!defined("LEMONADE_I")) {
            $this->currentUser = new User(['role' => -1]);
        }
        else {
            $this->currentUser = User::currentUser();
        }
        $this->status['installed'] = defined("LEMONADE_I");
        $this->status['currentUser'] = $this->currentUser;

        $this->AdminMenu = new AdminMenu("lemonade-adminmenu");
        $this->assets = new Assets();
        $this->options = new Options();
        $this->router = new Router();
    }
}
new Lemon();

API::register('admin', function() {
    return isAdmin();
});