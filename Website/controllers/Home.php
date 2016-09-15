<?php

class Home extends Controller {

    function __construct() {
        parent::__construct();
        session::init();
    }
    
    public function index() {
        if (!$_SESSION["loggedIn"]) {
            $this->view->title = "Home";
            $this->view->render("home/index");
        } else {
            header("Location" . URL . "games");
        }
    }

}