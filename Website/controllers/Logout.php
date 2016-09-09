<?php

class Logout extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        Session::destroy();
        
        header("Location:" . URL . "home");
    }

}