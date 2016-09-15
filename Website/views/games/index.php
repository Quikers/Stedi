<?php

$gameList = $this->gamesList;

if ($gameList["listType"] == "allGames") {
    if (count($gameList["games"]) > 0) {
        
?><div id="gameListContainer"><center><?php

        foreach($gameList["games"] as $key => $game) {
?>

<div style="display: none;" class="game">
    <a class="fade" href="<?= URL ?>games/app/<?= $game["id"] ?>">
        <h1><?= $game["name"] ?></h1>
        <h2><?= join(" / ", explode(" ", $game["genre"])) ?></h2>
        <h3>Creator: <?= $game["author"] ?></h3>
        <h3>Released: <?= explode(" ", $game["created"])[0] ?></h3>
    </a>
</div>


<?php
        }
        
?></center></div><?php

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

function Update () {
    var prevWidth = 0;
    var i = 0;
    
    (function myLoop () {          
        setTimeout(function () {   
            $(".game:nth-child(" + (i + 1) + ")").fadeIn(500);
            alert($(".game:nth-child(" + (i + 1) + ")").width());
            // ANIMATION HERE (remember half of width.
            if (++i < $(".game").length) myLoop();
        }, 200);
    })(); 
}


$(document).ready(function () {
    Update();
});


</script>