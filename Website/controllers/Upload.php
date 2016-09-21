<?php

class Upload extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    private function base64_encode_image ($filename=string,$filetype=string) {
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
            return "data:image/$filetype;base64," . base64_encode($imgbinary);
        }
    }
    
    public function formatPostFiles($vector) { 
        $result = array(); 
        foreach($vector as $key1 => $value1) {
            foreach($value1 as $key2 => $value2)  {
                $result[$key2][$key1] = $value2; 
            }
        }
        return $result; 
    } 
    
    public function index($params = NULL) {
        $this->view->messages = array();
        
        // Handle error messages
        $messages = isset($_SESSION["messages"]) ? $_SESSION["messages"] : NULL;
        unset($_SESSION["messages"]);
        
        if ($messages != NULL && count($messages) > 0) {
            foreach ($messages as $message) {
                switch($message) {
                    default: $this->view->messages["generalError"] = "Something went wrong while uploading the game!<br>Please contact the administrator of this website about this error."; break;
                    case "backgroundExtNotSupported":
                        $this->view->messages["backgroundExtNotSupported"] = "The background you tried to upload has the wrong file extension!<br>Please refer to the instructions on this page.";
                        break;
                    case "gameExtNotSupported":
                        $this->view->messages["gameExtNotSupported"] = "The game you tried to upload has the wrong file extension!<br>Only .ZIP is allowed.";
                        break;
                    case "backgroundTooSmall":
                        $this->view->messages["backgroundTooSmall"] = "The background you tried to upload is too small in size!<br>Please refer to the instructions on this page.";
                        break;
                    case "backgroundTooLarge":
                        $this->view->messages["backgroundTooLarge"] = "The background you tried to upload is too large in size!<br>Please refer to the instructions on this page.";
                        break;
                    case "gameCreated":
                        $this->view->messages["gameCreated"] = "Your game has been successfully uploaded!";
                        break;
                }
            }
        }
        
        $this->view->title = "Upload";
        $this->view->render("upload/index");
    }
    
    public function upload() {
        $this->loadModel("Upload");
        $uploadModel = new UploadModel();
        
        if (isset($_POST["submit"])) {
            $supportedExts = array("jpg", "jpeg", "png", "bmp");
            
            echo "<html><body><pre>";
            print_r($_POST);
            echo "<br>";
            print_r($_FILES);
            echo "<br></pre></body></html>";
            
            $continue = false;
            $gameBGSplit = explode(".", $_FILES["gameBackground"]["name"]);
            foreach ($supportedExts as $key => $ext) { if (strtolower($gameBGSplit[count($gameBGSplit) - 1]) == $ext) { $continue = true; } }
            if (!$continue) {
                $_SESSION["messages"] = array();
                array_push($_SESSION["messages"], "backgroundExtNotSupported");
                header("Location:" . URL . "upload");
            }
            
            return;
            
            $image = getimagesize($_FILES[$_POST["gameFile"]["tmp_name"]]);
            $minimum = array(
                'width' => '1024',
                'height' => '576'
            );
            $maximum = array(
                'width' => '1920',
                'height' => '1080'
            );
            $image_width = $image[0];
            $image_height = $image[1];
            
            if ( $image_width < $minimum['width'] || $image_height < $minimum['height'] ) {
                // add in the field 'error' of the $file array the message 
                $_SESSION["messages"] = array();
                array_push($_SESSION["messages"], "backgroundTooSmall");
                header("Location:" . URL . "upload");
            } else if ( $image_width > $maximum['width'] || $image_height > $maximum['height'] ) {
                //add in the field 'error' of the $file array the message
                $_SESSION["messages"] = array();
                array_push($_SESSION["messages"], "backgroundTooLarge");
                header("Location:" . URL . "upload");
            } else {
                $extension = explode(".", $_FILES["gameBackground"]["name"]);
                $uploadModel->UploadGame(
                    $_POST["gameName"], 
                    $_POST["gameGenre"], 
                    $_POST["gameAuthor"], 
                    $_POST["gameDesc"], 
                    $this->base64_encode_image(
                        $_FILES["gameBackground"]["tmp_name"], 
                        $extension[count($extension) - 1]
                    ), 
                    $_FILES["gameFile"]
                );
            }
            
            
        }
    }
}