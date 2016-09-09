<?php

class LoginModel extends Model {

    public $db = NULL;
    
    function __construct() {
        parent::__construct();
    }
    
    public function login($username, $password) {
        $result = $this->db->query('SELECT `id`, `username`, `email`, `firstname`, `insertion`, `lastname` FROM ' . DB_NAME . '.users WHERE `username` = "' . $username . '" AND `password` = PASSWORD("' . $password . '")');
        
        if ($result != array()) {
            return $result;
        } else {
            return false;
        }
    }

}