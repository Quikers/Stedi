<?php

class GamesModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getGames() {
        return $this->db->query("SELECT * FROM `games`", false);
    }

    public function getGameInfo($gameid) {
        return $this->db->query("SELECT * FROM `games` WHERE `id` = $gameid");
    }
    
}