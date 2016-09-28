<?php

$gameList = $this->gamesList;

if ($gameList["listType"] == "allGames") {
    if (count($gameList["games"]) > 0) {
        
?><div id="gameListContainer"><?php // ========================================= GET ALL GAMES =========================================
        
        foreach($gameList["games"] as $key => $game) {
            if ((int)$game["activated"] == 1) {
?>

<div style="display: none;" class="game" id="<?= $game["id"] ?>">
    <div class="background"></div>
    <a class="fade" href="<?= URL ?>games/app/<?= $game["id"] ?>">
        

        <h1><?= $game["name"] ?></h1>
        <h2><?= join(" / ", explode(" ", $game["tags"])) ?></h2>
        <h3>Creator: <?= $game["author"] ?></h3>
        <h3>Released: <?= explode(" ", $game["created"])[0] ?></h3>
    </a>
</div>


<?php
            }
        }
        
?></div><?php

    }
} else {
    if ($gameList["game"] != false && count($gameList["game"]) > 0) {
        $game = $gameList["game"]; // ========================================= SINGLE GAME FOUND =========================================
?>


<div id="gameInfoContainer">
    <div class="background" style="background: url('<?= $game["background"] ?>') no-repeat left top;"></div>
    
    <h1><?= $game["name"] ?></h1>
    <h2><?= join(" / ", explode(" ", $game["tags"])) ?></h2>
    <h3>Created by: <p style="display: inline-block;"><?= $game["author"] ?></p></h3>
    <h3>Release date: <p style="display: inline-block;"><?= explode(" ", $game["created"])[0] ?></p></h3>
    <h4>Rating: <?= $game["rating"] ?></h4><?= print_r($game) ?>
    <h5><?= str_replace("\\r\\n", "<br>", $game["description"]) ?></h5>
</div>


<?php // ========================================= SINGLE GAME NOT FOUND =========================================
    } else { ?>


<div id="gameInfoContainer">
    <div class="background" style="background: url('<?= $game["background"] ?>') no-repeat left top;"></div>

    <h1 style="color: red;">GameID "<?= $gameList["gameid"] ?>" does not exist.</h1>
</div>


<?php }
} ?>

<script>

var listType = "<?= $gameList["listType"] ?>";

if (listType === "allGames") {
    function Update () {
        var prevWidth = 0;
        var i = 0;

        (function myLoop () {          
            setTimeout(function () {
                $.get("<?= URL ?>games/getgames/" + $(".game:nth-child(" + (i + 1) + ")").attr("id"), function (JSONdata) {
                    var data = JSON.parse(JSONdata);
                    
                    $(".game:nth-child(" + (i + 1) + ") .background").attr("style", "background: url('" + data.game.background + "') no-repeat left top;");
                    
                    $(".game:nth-child(" + (i + 1) + ")").fadeIn(400);
                    if (++i < $(".game").length) myLoop();
                });
            });
        })(); 
    }


    $(document).ready(function () {
        Update();
    });
} else {
    $("#content").css("background", "rgba(0, 0, 0, 0.75)");
}


</script>