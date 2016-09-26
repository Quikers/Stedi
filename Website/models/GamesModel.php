<?php

class GamesModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getGames($getOnlyID = false, $getBackground = false) {
        $columns = (!$getOnlyID ? ", `userid`, `name`, `activated`, `created`, `genre`, `author`, `description`" : "") . ($getBackground ? ", `background`" : "");
        return $this->db->Query("SELECT `id`$columns FROM `games`", false);
    }

    public function getGameInfo($gameid, $getBackground = true) {
        $columns = ($getBackground ? ", `background`" : "");
        $result = $this->db->Query("SELECT `id`, `userid`, `name`, `activated`, `created`, `genre`, `author`, `description`$columns FROM `games` WHERE `id` = $gameid");
        
        return $result != array() ? $result : false;
    }
    
    public function getGameRating($gameid) {
        return $this->db->Query("SELECT * FROM `ratings` WHERE `id` = $gameid");
    }
    
}