<?php

class LoginModel extends Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function Login($username, $password) {
        $result = $this->db->Query('SELECT `id`, `username`, `accountType`, `email` FROM ' . DB_NAME . '.users WHERE `username` = "' . $username . '" AND `password` = PASSWORD("' . $password . '")');
        
        if ($result != array()) {
            return $result;
        } else {
            return false;
        }
    }
    
    public function Register($email, $username, $password) {
        $result = $this->db->Query('INSERT INTO `users`(`email`, `username`, `password`, `accountType`) VALUES ("' . $email . '", "' . $username . '", PASSWORD("' . $password . '"), 1)');
        
        if ($result != array()) {
            return $result;
        } else {
            return false;
        }
    }

}