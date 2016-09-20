<?php

$alphabet = "01234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

function randomString($length, $alphabet) {
    $string = "";

    for ($i = 0; $i < $length; $i++) {
        $string .= $alphabet[rand(0, 61)];
    }

    return $string;
}

$gameList = $this->gamesList;

if ($gameList["listType"] == "allGames") {
    if (count($gameList["games"]) > 0) {
        
?><div id="gameListContainer"><?php
        
        for ($i = 0; $i < 20; $i++) {
            foreach($gameList["games"] as $key => $game) {
                if ((int)$game["activated"] > 0) {
?>

<div style="display: none;" class="game">
    <div class="background" style="background: url('<?= $game["background"] ?>') no-repeat left top;"></div>
    <a class="fade" href="<?= URL ?>games/app/<?= $game["id"] ?>">
        

        <h1><?= randomString(rand(10, 50), $alphabet) /* $game["name"] */ ?></h1>
        <h2><?= join(" / ", explode(" ", $game["genre"])) ?></h2>
        <h3>Creator: <?= $game["author"] ?></h3>
        <h3>Released: <?= explode(" ", $game["created"])[0] ?></h3>
    </a>
</div>


<?php
                }
            }
        }
        
?></div><?php

    }
} else {
    if (count($gameList["game"]) > 0) {
        $game = $gameList["game"];
?>


<div id="gameInfoContainer">
    <h1><?= $game["name"] ?></h1>
    <h2><?= join(" / ", explode(" ", $game["genre"])) ?></h2>
    <h3>Created by: <?= $game["author"] ?></h3>
    <h3>Release date: <?= explode(" ", $game["created"])[0] ?></h3>
    <h4>Rating: <?= $game["rating"] ?></h4>
    <h5><?= $game["description"] ?></h5>
</div>


<?php
    }
}
?>

<script>

var listType = "<?= $gameList["listType"] ?>";

if (listType === "allGames") {
    var gamesList = <?= json_encode($gameList["games"]) ?>;
    
    console.log(gamesList);
    alert(gamesList[0]);

    function Update () {
        var prevWidth = 0;
        var i = 0;

        (function myLoop () {          
            setTimeout(function () {
                $(".game:nth-child(" + (i + 1) + ")").fadeIn(400);
                if (++i < $(".game").length) myLoop();
            }, 50);
        })(); 
    }


    $(document).ready(function () {
        Update();
    });
}


</script>