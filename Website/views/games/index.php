<?php

$gameList = $this->gamesList;

if ($gameList["listType"] == "allGames") { ?>

<div id="filterContainer">
    <h3>Filter games</h3>
    <input type="text" id="searchBar" placeholder="Search games"><br>
    <select id="category">
        <option value="0">A - Z</option>
        <option value="1">Z - A</option>
        <option value="2" selected="selected">Newest</option>
        <option value="3">Oldest</option>
        <option value="4">Most popular</option>
        <option value="5">Least popular</option>
        <option value="6">Highest rating</option>
        <option value="7">Lowest rating</option>
    </select>
</div>

<?php 
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
    <div class="background cover"></div>
    
    <h1><?= $game["name"] ?></h1>
    <h2><?= join(" / ", explode(" ", $game["tags"])) ?></h2>
    <h3>Created by: <p style="display: inline-block;"><?= $game["author"] ?></p></h3>
    <h3>Release date: <p style="display: inline-block;"><?= explode(" ", $game["created"])[0] ?></p></h3>
    <h4>Rating: <?= $game["rating"] ?></h4>
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
var gamesArr = <?= json_encode($gameList) ?>;

if (listType === "allGames") {
    function Update () {
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
        
        console.log(gamesArr);
        
        var gameNameArr = [];
        
        $.each(gamesArr.games, function (gameKey, game) {
            $.each(game, function (key, property) {
                if (key !== "id") {
                    switch(key) {
                        default:
                            console.log("");
                            break;
                    }
                    gameNameArr[game.id] = property;
                }
            });
        });
        
        $("#filterContainer #searchBar").keydown(function () {
            
        });
        
        $("#filterContainer select").change(function () {
            switch ($(this).val()) {
                default:
                    console.log("Unknown ComboBox value \"" + $(this).val() + "\"");
                    break;
                case "0":
                    break;
                case "1":
                    break;
                case "2":
                    var dateArr = [];
                    $.each(gamesArr.games, function(arrKey, game) {
                        $.each(game, function(key, property) {
                            
                        });
                    });
                    break;
                case "3":
                    break;
                case "4":
                    break;
                case "5":
                    break;
                case "6":
                    break;
                case "7":
                    break;
            }
        });
    
        Update();
    });
}

</script>