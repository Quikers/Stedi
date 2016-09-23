<?php

class Home extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        if (!$_SESSION["loggedIn"]) {
            if (isset($_SESSION["login"])) {
                $login = $_SESSION["login"];
                unset($_SESSION["login"]);
                $this->view->login = $login;
            }
            if (isset($_SESSION["message"])) {
                $message = $_SESSION["message"];
                unset($_SESSION["message"]);

                $this->view->message = $message;
            }
            
            $this->view->title = "Home";
            $this->view->render("home/index");
        } else {
            header("Location:" . URL . "games");
        }
    }

}