<!doctype html>
<html>
<head>
    <title><?=(isset($this->title)) ? "Stedi | " . $this->title : 'Stedi'; ?></title>
    <link rel="icon" href="<?= URL; ?>favicon.png" />
    
    <!-- <link rel="stylesheet" href="<?= URL; ?>public/css/bootstrap/bootstrap.min.css" /> -->
    <!-- <link rel="stylesheet" href="<?= URL; ?>public/css/bootstrap/bootstrap-theme.min.css" /> -->
    <link rel="stylesheet" href="<?= URL; ?>public/css/datatables/datatables.css" />
    
    <link rel="stylesheet" href="<?= URL; ?>public/css/default.css" />
</head>
<body style="display: none;">

    <?php Session::init(); ?>
    
    <script src="<?= URL; ?>public/js/jquery.js"></script>
    <!-- <script src="<?= URL; ?>public/js/bootstrap/bootstrap.min.js"></script> -->
    <script src="<?= URL; ?>public/js/datatables/datatables.js"></script>
    
    <div id="header">
        
        <div id="nav">
            <?php if ($_SESSION["loggedIn"] != true) { ?>
                <div class="li <?= $this->title == "Home" ? "active" : "" ?>"><a href="<?= URL ?>home">Home</a></div>
            <?php } else { ?>
                <div class="li"><a href="<?= URL ?>logout">Logout</a></div>
                <div class="li <?= $this->title == "Dashboard" ? "active" : "" ?>"><a href="<?= URL ?>dashboard">Dashboard</a></div>
                <div class="li <?= $this->title == "Games" ? "active" : "" ?>"><a href="<?= URL ?>games">Games</a></div>
                <div class="li <?= $this->title == "Upload" ? "active" : "" ?>"><a href="<?= URL ?>upload">Upload Your Game</a></div>
            <?php } ?>
        </div>

    </div>

    <div id="content">
    
    