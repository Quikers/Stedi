<?php

class LoginModel extends Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function login($username, $password) {
        $result = $this->db->Query('SELECT `id`, `username`, `accountType`, `email`, `firstname`, `insertion`, `lastname` FROM ' . DB_NAME . '.users WHERE `username` = "' . $username . '" AND `password` = PASSWORD("' . $password . '")');
        
        print_r($result);
        
        if ($result != array()) {
            return $result;
        } else {
            return false;
        }
    }

}