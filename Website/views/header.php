<!doctype html>
<html>
<head>
    <title><?=(isset($this->title)) ? $this->title : 'Stedi'; ?></title>
    <link rel="stylesheet" href="<?php echo URL; ?>public/css/default.css" />
</head>
<body>

<?php Session::init(); ?>
    
<div id="header">

    <nav>
        <ul>
            <li><a href="http://localhost/home">Home</a></li>
        </ul>
    </nav>
    
</div>
    
<div id="content">
    
    