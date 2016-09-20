<?php

class Upload extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    private function base64_encode_image ($filename=string,$filetype=string) {
        if ($filename) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
            return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
        }
    }
    
    public function index($params = NULL) {
        $this->view->messages = array();
        
        $this->loadModel("Upload");
        $uploadModel = new UploadModel();
        $uploadModel->bleh($this->base64_encode_image("D:/background.jpg", "jpg"));
        
        $messages = isset($_SESSION["messages"]) ? $_SESSION["messages"] : NULL;
        unset($_SESSION["messages"]);
        
        if ($messages != NULL && count($messages) > 0) {
            foreach ($messages as $message) {
                switch($message) {
                    default: $this->view->messages["generalError"] = "Something went wrong while uploading the game!<br>Please contact the administrator of this website about this error."; break;
                    case "backgroundExtNotSupported":
                        $this->view->messages["backgroundExtNotSupported"] = "Sorry, the background you tried to upload has the wrong file extension!<br>Only .PNG is allowed.";
                        break;
                    case "gameExtNotSupported":
                        $this->view->messages["gameExtNotSupported"] = "Sorry, the game you tried to upload has the wrong file extension!<br>Only .ZIP is allowed.";
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
            $gameBGSplit = explode(".", $_POST["gameBackground"]);
            if (strtolower($gameBGSplit[count($gameBGSplit) - 1]) != "png") {
                $_SESSION["messages"] = array();
                array_push($_SESSION["messages"], "backgroundExtNotSupported");
                header("Location:" . URL . "upload");
            }
            
            $image = getimagesize($_FILES[$_POST["gameFile"]]);
            $minimum = array(
                'width' => '1024',
                'height' => '576'
            );
            $maximum = array(
                'width' => '1280',
                'height' => '720'
            );
            $image_width = $image[0];
            $image_height = $image[1];

            $too_small = "Image dimensions are too small. Minimum size is {$minimum['width']} by {$minimum['height']} pixels. Uploaded image is $image_width by $image_height pixels.";
            $too_large = "Image dimensions are too large. Maximum size is {$maximum['width']} by {$maximum['height']} pixels. Uploaded image is $image_width by $image_height pixels.";

            if ( $image_width < $minimum['width'] || $image_height < $minimum['height'] ) {
                // add in the field 'error' of the $file array the message 
                $file['error'] = $too_small; 
                return $file;
            }
            elseif ( $image_width > $maximum['width'] || $image_height > $maximum['height'] ) {
                //add in the field 'error' of the $file array the message
                $file['error'] = $too_large; 
                return $file;
            }
            
            $uploadModel->UploadGame($_POST["gameName"], $_POST["gameGenre"], $_POST["gameAuthor"], $_POST["gameDesc"], base64_encode($_POST["gameBackground"]), $_POST["gameFile"]);

            
        }
    }
}