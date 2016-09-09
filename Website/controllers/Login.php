<?php

class Login extends Controller {

    function __construct() {
        
    }

    public function index() {
        $this->loadModel("Login");
        $loginModel = new LoginModel();
        
        $result = $loginModel->login($_POST["username"], $_POST["password"]);
        
        if ($result != false) {
            Session::init();
            
            $_SESSION["user"] = $result;
            $_SESSION["loggedIn"] = true;
            
            header("Location:" . URL . "games");
        } else {
            $_SESSION["loggedIn"] = false;
            $_SESSION["loginFail"] = true;
            
            header("Location:" . URL . "home");
        }
    }
}