<?php

class GamesModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getGames() {
        return $this->db->Query("SELECT `id`, `userid`, `name`, `activated`, `created`, `genre`, `author`, `description` FROM `games`", false);
    }

    public function getGameInfo($gameid) {
        return $this->db->Query("SELECT * FROM `games` WHERE `id` = $gameid");
    }
    
    public function getGameRating($gameid) {
        return $this->db->Query("SELECT * FROM `ratings` WHERE `id` = $gameid");
    }
    
}