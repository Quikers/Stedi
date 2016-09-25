<?php

class Games extends Controller {
    
    function __construct() {
        parent::__construct();
        
        // If user is not logged in return to home screen
        if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) { header("Location:" . URL . "home"); }
    }
    
    public function index() {
        header("Location:" . URL . "games/apps/");
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
        if ($params != NULL) {
            $gameList["game"] = $gamesModel->getGameInfo($params[0]);
            
            if ($gameList["game"] != false) {
                $rating = 0;
                $ratings = $gamesModel->getGameRating($gameList["game"]["id"]);
                if (count($ratings) > 0) {
                    for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                    $rating /= count($ratings);
                } else {
                    $rating = "<p style=\"display: inline-block; color: lightblue\">This game has not been rated yet.</p>";
                }

                $gameList["game"]["rating"] = $rating;
            } else { $gameList["gameid"] = $params[0]; }
        } else {
            header("Location:" . URL . "games/apps");
        }
        
        $this->view->gamesList = $gameList;
        $this->view->title = "Games";
        $this->view->render("games/index");
    }
    
    public function getgames($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        $gameList = array();
        
        $gameList["listType"] = $params != NULL ? "singleGame" : "allGames";
        $gameList["game"] = $params != NULL ? $gamesModel->getGameInfo($params[0]) : $gamesModel->getGames(); 
        $params != NULL ?: $gameList["game"]["rating"] = "4.5 / 5 TEST"; 
        
        echo json_encode($gameList);
    }

}