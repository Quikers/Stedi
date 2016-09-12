<?php

$gameList = $this->gamesList;

if ($gameList["listType"] == "allGames") {
    if (count($gameList["games"]) > 0) {
        foreach($gameList["games"] as $key => $game) {
            echo "<div class=\"game\" id=\"" . $game["id"] . "\">";
            echo "<a href=\"" . URL . "games/index/" . $game["id"] . "\">" . $game["name"] . "</a>";
            echo "</div>";
        }
    }
} else {
    if (count($gameList["game"]) > 0) {
        $game = $gameList["game"];
        
        echo "<div class=\"game\" id=\"" . $game["id"] . "\">";
        echo "<a href=\"" . URL . "games/index/" . $game["id"] . "\">" . $game["name"] . "</a>";
        echo "</div>";
    }
}

?>