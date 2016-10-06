<?php

class GamesModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function getGames($getOnlyID = false, $getBackground = false, $userid = NULL) {
        $columns = (!$getOnlyID ? ", `userid`, `name`, `activated`, `created`, `tags`, `author`, `description`, `playcount`, `timeplayed`" : "") . ($getBackground ? ", `background`" : "");
        $userID = ($userid != NULL ? " WHERE `userid`=$userid" : "" );
        
        return $this->db->Query("SELECT `id`$columns FROM `games`$userID", false);
    }

    public function GetGameInfo($gameid, $getBackground = true) {
        $columns = ($getBackground ? ", `background`" : "");
        $result = $this->db->Query("SELECT `id`, `userid`, `name`, `activated`, `created`, `tags`, `author`, `description`, `playcount`, `timeplayed`$columns FROM `games` WHERE `id` = $gameid");
        
        return $result != array() ? $result : false;
    }
    
    public function GetGameRating($gameid) {
        return $this->db->Query("SELECT * FROM `ratings` WHERE `gameid` = $gameid", false);
    }
    
    private function GetGameUserID($gameid) {
        return $this->db->Query("SELECT `userid` FROM `games` WHERE `id`=$gameid")["userid"];
    }
    
    public function Approve($gameid) {
        if ((int)$_SESSION["user"]["accountType"] == 0) {
            return $this->db->Query("UPDATE `games` SET `activated`=1 WHERE `id` = $gameid");
        }
    }
    
    public function Delete($gameid) {
        $gameuserid = $this->GetGameUserID($gameid);
        
        if ((int)$_SESSION["user"]["accountType"] == 0 || $_SESSION["user"]["id"] == $gameuserid) {
            $this->db->Query("DELETE FROM `ratings` WHERE `gameid` = $gameid");
            return $this->db->Query("DELETE FROM `games` WHERE `id` = $gameid");
        }
    }
    
    public function Deactivate($gameid) {
        $gameuserid = $this->GetGameUserID($gameid);
        
        if ((int)$_SESSION["user"]["accountType"] == 0 || $_SESSION["user"]["id"] == $gameuserid) {
            $this->db->Query("UPDATE `games` SET `activated`=3 WHERE `id` = $gameid");
        }
    }
    
}