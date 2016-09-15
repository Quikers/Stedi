<?php

class Error extends Controller {

    public $pageName = "";
    
    public function __construct($_pageName) {
        parent::__construct();
        session::init();
        
        $this->pageName = $_pageName;
    }
    
    public function index() {
        $this->view->title = $this->pageName;
        $this->view->render('error/index');
    }

}
