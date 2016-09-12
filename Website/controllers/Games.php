<?php

class Games extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        $gameList = array();
        
        if ($params == NULL || count($params) == 0) {
            $gameList["listType"] = "allGames";
            $gameList["games"] = $gamesModel->getGames();
        } else {
            $gameList["listType"] = "singleGame";
            $gameList["game"] = $gamesModel->getGameInfo($params);
        }
        
        $this->view->gamesList = $gameList;
        $this->view->title = "Games";
        $this->view->render("games/index");
    }

}