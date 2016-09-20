<?php

class GamesModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getGames() {
        return $this->db->Query("SELECT * FROM `games`", false);
    }

    public function getGameInfo($gameid) {
        return $this->db->Query("SELECT * FROM `games` WHERE `id` = $gameid");
    }
    
}