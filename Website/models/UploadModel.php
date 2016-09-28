<?php

class UploadModel extends Model {

    function __construct() {
        parent::__construct();
    }
    
    public function InsertGame($gameName, $gametags, $gameAuthor, $gameDesc, $gameBackground) {
        return $this->db->Query("INSERT INTO `games`(`userid`, `name`, `activated`, `tags`, `author`, `description`, `background`) VALUES (" . $_SESSION["user"]["id"] . ", \"$gameName\", 0, \"$gametags\", \"$gameAuthor\", \"$gameDesc\", \"$gameBackground\")", true, true);
    }
    
    public function DeleteGameById($gameid) {
        $this->db->Query("DELETE FROM `games` WHERE id=" . $gameid);
    }
    
    public function GetLastInsertedGame() {
        return $this->db->Query("SELECT (`id`) FROM `games` ORDER BY `id` DESC LIMIT 1");
    }
    
    public static function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..'));
        
        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
        }
        
        return rmdir($dir); 
    } 

    public function UploadGame($gameName, $gametags, $gameAuthor, $gameDesc, $gameBackground, $gameFile) {
        $this->InsertGame($gameName, $gametags, $gameAuthor, $gameDesc, $gameBackground);
        $result = $this->GetLastInsertedGame();
        
        if (isset($result["id"])) {
            $target_dir = GAME_DIR . "/Games/" . $result["id"];
            $target_file = $target_dir . basename($gameFile["name"]);
            $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $_SESSION["messages"] = array();
            
            if (file_exists($target_file)) { // Check if file already exists
                $this->DeleteGameById($result["id"]);
                
                array_push($_SESSION["messages"], "gameAlreadyExists");
            } else if($fileType != "zip") { // Allow only ZIP files
                $this->DeleteGameById($result["id"]);
                
                array_push($_SESSION["messages"], "gameExtNotSupported");
            } else { // if everything is ok, try to unzip and upload the file
                $zip = new ZipArchive;
                if ($zip->open($gameFile["tmp_name"])) {
                    $zip->extractTo(GAME_DIR . "/Games/" . $result["id"]);
                    $zip->close();
                    
                    if (file_exists( GAME_DIR . "/Games/" . $result["id"] . "/game.exe")) {
                        array_push($_SESSION["messages"], "gameCreated");
                    } else {
                        $this->DeleteGameById($result["id"]);
                        $this->delTree( GAME_DIR . "/Games/" . $result["id"]);
                        
                        array_push($_SESSION["messages"], "gameExeMissing");
                    }
                } else {
                    $this->DeleteGameById($result["id"]);
                    
                    array_push($_SESSION["messages"], "gameZipExtractError");
                }
            }
        }
    }
    
}