<?php

class Upload extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function index($params = NULL) {
        $this->view->messages = array();
        
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
            
            $uploadModel->UploadGame($_POST["gameName"], $_POST["gameGenre"], $_POST["gameAuthor"], $_POST["gameDesc"], base64_encode($_POST["gameBackground"]), $_POST["gameFile"]);

            
        }
    }
}