<?php

class Games extends Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        if ((int)$_SESSION["user"]["accountType"] == 0) {
            header("Location:" . URL . "games/approval/");
        } else {
            header("Location:" . URL . "games/apps/");
        }
    }
    
    public function approval($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        $gameList = array();
        
        $gameList["listType"] = "allGames";
        $gameList["games"] = $gamesModel->getNonApprovedGames();
        
        $this->view->gamesList = $gameList;
        $this->view->title = "Games";
        $this->view->render("games/index");
    }
    
    public function apps($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        $gameList = array();
        
        $gameList["listType"] = "allGames";
        $gameList["games"] = $gamesModel->getGames();
        
        $this->view->gamesList = $gameList;
        $this->view->title = "Games";
        $this->view->render("games/index");
    }
    
    public function app($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        $gameList = array();
        
        $gameList["listType"] = "singleGame";
        $gameList["game"] = $gamesModel->getGameInfo($params); 
        $gameList["game"]["rating"] = "4.5 / 5 TEST"; 
        
        $this->view->gamesList = $gameList;
        $this->view->title = "Games";
        $this->view->render("games/index");
    }

}