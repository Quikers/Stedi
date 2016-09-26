<?php

class Dashboard extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->loadModel("Games");
        $gamesModel = new GamesModel;
        $gameList = array();
        
        $gameList["listType"] = "allGames";
        $gameList["games"] = $gamesModel->getGames();
        
        if ($gameList["games"] != false) {
            foreach ($gameList["games"] as $key => $game) {
                $rating = 0;
                $ratings = $gamesModel->getGameRating($game["id"]);
                if (count($ratings) > 0) {
                    for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                    $rating /= count($ratings);
                } else {
                    $rating = "<p style=\"display: inline-block; color: lightblue\">N/A</p>";
                }

                $game["rating"] = $rating;

                $gameList["games"][$key] = $game;
            }
        } else { $gameList["gameid"] = $params[0]; }
        
        $this->view->gameList = $gameList;
        $this->view->title = "Dashboard";
        $this->view->render("dashboard/index");
    }

}