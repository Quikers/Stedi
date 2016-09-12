<!doctype html>
<html>
<head>
    <title><?=(isset($this->title)) ? $this->title : 'Stedi'; ?></title>
    <!-- <link rel="stylesheet" href="<?= URL; ?>public/css/bootstrap.min.css" /> -->
    <!-- <link rel="stylesheet" href="<?= URL; ?>public/css/bootstrap-theme.min.css" /> -->
    
    <link rel="stylesheet" href="<?= URL; ?>public/css/default.css" />
</head>
<body>

    <?php Session::init(); ?>
    
    <script src="<?= URL; ?>public/js/jquery.js"></script>
    <!-- <script src="<?= URL; ?>public/js/bootstrap.min.js"></script> -->
    
    <div id="header">
        
        <div id="nav">
            <?php if ($_SESSION["loggedIn"] != true) { ?>
                <div class="li <?= $this->title == "Home" ? "active" : "" ?>"><a href="<?= URL ?>home">Home</a></div>
            <?php } else { ?>
                <div class="li <?= $this->title == "Logout" ? "active" : "" ?>"><a href="<?= URL ?>logout">Logout</a></div>
                <div class="li <?= $this->title == "Games" ? "active" : "" ?>"><a href="<?= URL ?>games">Games</a></div>
                <div class="li <?= $this->title == "Upload Your Own Game" ? "active" : "" ?>"><a href="<?= URL ?>upload">Upload Your Own Game</a></div>
            <?php } ?>
        </div>

    </div>

    <div id="content">
    
    