<?php

class Session
{
    
    public static function init()
    {
        @session_start();
        if (!isset($_SESSION["loggedIn"])) {
            $_SESSION["loggedIn"] = false;
        }
    }
    
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }
    
    public static function destroy()
    {
        Session::init();
        
        unset($_SESSION["user"]);
        unset($_SESSION["loggedIn"]);
        unset($_SESSION["loginFail"]);
        
        unset($_SESSION);
        
        session_destroy();
    }
    
}