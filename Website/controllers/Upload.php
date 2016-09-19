<?php

class Upload extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->view->title = "Upload";
        $this->view->render("upload/index");
    }

}