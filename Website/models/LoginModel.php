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
    
    public function GetLastInsertedUser() {
        return $this->db->Query("SELECT (`id`) FROM `users` ORDER BY `id` DESC LIMIT 1");
    }
    
    public function Register($email, $username, $password) {
        try {
            $lastID = $this->GetLastInsertedUser()["id"];
            $this->db->Query('INSERT INTO `users`(`email`, `username`, `password`, `accountType`) VALUES ("' . $email . '", "' . $username . '", PASSWORD("' . $password . '"), 1)', true, false, true);
            $newID = $this->GetLastInsertedUser()["id"];
        } catch(Exception $ex) { return -1; }
        
        return $lastID != $newID ? $newID : 0;
    }

}