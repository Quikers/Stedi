<!doctype html>
<html>
<head>
    <title><?=(isset($this->title)) ? $this->title : 'Stedi'; ?></title>
    <!-- <link rel="stylesheet" href="<?php echo URL; ?>public/css/bootstrap.min.css" /> -->
    <!-- <link rel="stylesheet" href="<?php echo URL; ?>public/css/bootstrap-theme.min.css" /> -->
    
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
</head>
<body>

    <?php Session::init(); print_r($_SESSION); ?>
    
    <script src="<?php echo URL; ?>public/js/jquery.js"></script>
    <!-- <script src="<?php echo URL; ?>public/js/bootstrap.min.js"></script> -->
    
    <div id="header">
        
        <div id="nav">
            <?php if ($_SESSION["loggedIn"] != true) { ?>
                <div class="li <?= $this->title == "Home" ? "active" : "" ?>"><a href="http://localhost/home">Home</a></div>
            <?php } else { ?>
                <div class="li <?= $this->title == "Logout" ? "active" : "" ?>"><a href="http://localhost/logout">Logout</a></div>
                <div class="li <?= $this->title == "Home" ? "active" : "" ?>"><a href="http://localhost/home">Home</a></div>
                <div class="li <?= $this->title == "Games" ? "active" : "" ?>"><a href="http://localhost/games">Games</a></div>
                <div class="li <?= $this->title == "Upload Your Own Game" ? "active" : "" ?>"><a href="http://localhost/upload">Upload Your Own Game</a></div>
            <?php } ?>
        </div>

    </div>

    <div id="content">
    
    