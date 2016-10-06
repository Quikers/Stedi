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
    
    public function approve($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        
        $gamesModel->Approve($params[0]);
        
        header("Location:" . URL . "dashboard");
    }
    
    public function delete($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        
        $gamesModel->Delete($params[0]);
        
        header("Location:" . URL . "dashboard");
    }
    
    public function deactivate($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        
        $gamesModel->Deactivate($params[0]);
        
        header("Location:" . URL . "dashboard");
    }
    
    public function apps($params = NULL) {
        $this->loadModel("Games");
        $gamesModel = new GamesModel();
        $gameList = array();
        
        $gameList["listType"] = "allGames";
        $gameList["games"] = $gamesModel->GetGames();
        
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
            $gameList["game"] = $gamesModel->GetGameInfo($params[0]);
            
            if ($gameList["game"] != false) {
                $rating = 0;
                $ratings = $gamesModel->GetGameRating($gameList["game"]["id"]);
                if (count($ratings) > 0) {
                    for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                    $rating = round((int)$rating / count($ratings));
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
        
        if ($params[0] != -1) {
            $gameList["listType"] = $params != NULL ? "singleGame" : "allGames";
            if ($params != NULL) {
                $gameList["game"] = $gamesModel->GetGameInfo($params[0]);
                
                $rating = 0;
                $ratings = $gamesModel->GetGameRating($gameList["game"]["id"]);
                if (count($ratings) > 0) {
                    for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                    $rating = round((int)$rating / count($ratings));
                } else {
                    $rating = "<p style=\"display: inline-block; color: lightblue\">This game has not been rated yet.</p>";
                }

                $gameList["game"]["rating"] = $rating;
            } else {
                $gameList["game"] = $gamesModel->GetGames();
                
                foreach($gameList["game"] as $key => $game) {
                    $rating = 0;
                    $ratings = $gamesModel->GetGameRating($game["id"]);
                    if (count($ratings) > 0) {
                        for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                        $rating = round((int)$rating / count($ratings));
                    } else {
                        $rating = "<p style=\"display: inline-block; color: lightblue\">This game has not been rated yet.</p>";
                    }

                    $game["rating"] = $rating;
                    
                    $gameList["game"][$key] = $game;
                }
            }
            
        } else {
            $gameList["game"] = $gamesModel->GetGameInfo($params[1], false);
            
            $rating = 0;
            $ratings = $gamesModel->GetGameRating($gameList["game"]["id"]);
            if (count($ratings) > 0) {
                for ($i = 0; $i < count($ratings); $i++) { $rating += $ratings[$i]["rating"]; }
                $rating = round((int)$rating / count($ratings));
            } else {
                $rating = "<p style=\"display: inline-block; color: lightblue\">This game has not been rated yet.</p>";
            }

            $gameList["game"]["rating"] = $rating;
        }
        
        echo json_encode($gameList);
    }

}