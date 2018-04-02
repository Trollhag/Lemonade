<?php
namespace Lemonade;

class Lemon {
    public static $db;
    public $router;
    public $options;
    public $status = [];
    public $isAdmin = false;
    public $post_types = [];
    public $routes = [];
    function __construct() {
        global $Lemon;
        $Lemon = $this;
             
        if (!defined("LEMONADE_I")) {
            $this->currentUser = new User(['role' => -1]);
        }
        else {
            $this->currentUser = User::currentUser();
        }
        $this->status['installed'] = defined("LEMONADE_I");
        $this->status['currentUser'] = $this->currentUser;

        if (User::isAdmin()) {
            $this->AdminMenu = new AdminMenu("lemonade-adminmenu");
        }
        $this->assets = new Assets();
        $this->options = new Options();
        $this->router = new Router();
        User::Setup();
        AdminMenu::Setup();
    }
}