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
        $userID = ((int)$_SESSION["user"]["accountType"] == 0 ? NULL : $_SESSION["user"]["id"]);
        $gameList["games"] = $gamesModel->getGames(false, false, $userID);
        
        if ($gameList["games"] != false) {
            foreach ($gameList["games"] as $key => $game) {
                if ((int)$_SESSION["user"]["accountType"] != 0) {
                    if ($game["userid"] == $_SESSION["user"]["id"]) {
                        $rating = 0;
                        $ratings = $gamesModel->getGameRating($game["id"]);
                        if (count($ratings) > 0) {
                            for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                            $rating = round((int)$rating / count($ratings));
                        } else {
                            $rating = "<p style=\"display: inline-block; color: lightblue\">This game has not been rated yet.</p>";
                        }

                        $gameList["game"]["rating"] = $rating;

                        $gameList["games"][$key] = $game;
                    } else {
                        unset($gameList[$key]);
                    }
                } else {
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
            }
        }
        
        $this->view->gameList = $gameList;
        $this->view->title = "Dashboard";
        $this->view->render("dashboard/index");
    }

}