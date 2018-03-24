<?php
namespace Lemonade\PostSetup;

class Page extends PostBase {
    function __construct() {
        parent::__construct();
        $this->init();
    }
    protected function init() {}
}