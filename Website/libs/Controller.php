<?php

class Controller {

    function __construct() {
        $this->view = new View();
    }
    
    public function loadModel($name, $modelPath = '../models/') {
        
        $path = $modelPath . $name.'Model.php';
        
        if (file_exists($path)) {
            require $path;
            
            $name .= 'Model';
            $this->model = new $name();
        }        
    }

}