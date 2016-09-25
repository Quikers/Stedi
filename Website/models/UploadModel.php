<?php

class UploadModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function UploadGame($gameName, $gameGenre, $gameAuthor, $gameDesc, $gameBackground, $gameFile) {
        $this->db->Query("INSERT INTO `games`(`userid`, `name`, `activated`, `genre`, `author`, `description`, `background`) VALUES (" . $_SESSION["user"]["id"] . ", \"$gameName\", 0, \"$gameGenre\", \"$gameAuthor\", \"$gameDesc\", \"$gameBackground\")", true, true);
        $result = $this->db->Query("SELECT (`id`) FROM `games` ORDER BY `id` DESC LIMIT 1");
        
        if (isset($result["id"])) {
            $target_dir = GAME_DIR . "/Games/" . $result["id"];
            $target_file = $target_dir . basename($gameFile["name"]);
            $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // Check if file already exists
            if (file_exists($target_file)) {
                return "Game already exists.<br>if you wish to update your game please remove it first.";
            }
            // Allow certain file formats
            if($fileType != "zip") {
                return "Only ZIP files are allowed.";
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $this->db->Query("DELETE FROM `games` WHERE id=" . $result["id"]);
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($gameFile["tmp_name"], $target_file)) {
                    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
    
}