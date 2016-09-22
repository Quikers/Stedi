<?php

class Login extends Controller {

    function __construct() {
        
    }

    public function index($params = NULL) {
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

    public function register() {
        $this->loadModel("Login");
        $loginModel = new LoginModel();
        
        $result = $loginModel->Register($_POST["email"], $_POST["username"], $_POST["password"]);
        
        if ($result != false) {
            
        } else {
            echo "<html><body><pre>";
            print_r($result);
            echo "<br>";
            print_r($_POST);
            echo "</pre></body></html>";
        }
        
        $this->index(array("message" => "<p style=\"color: lightgreen\">Successfully registered!</p>"));
    }
}