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
		console.log(gamesArr.games);
        (function myLoop () {          
            setTimeout(function () {
				$(".game:nth-child(" + (i + 1) + ")").attr("id", gamesArr.games[i].id.toString());
                $.get("<?= URL ?>games/getgames/" + $(".game:nth-child(" + (i + 1) + ")").attr("id"), function (JSONdata) {
                    var data = JSON.parse(JSONdata);
                    
					$(".game:nth-child(" + (i + 1) + ")").children("a").attr("href", location.origin+"/games/app/"+data.game.id);
					$(".game:nth-child(" + (i + 1) + ")").children("a").children("h1").html(data.game.name);
					$(".game:nth-child(" + (i + 1) + ")").children("a").children("h2").html(data.game.tags.replace(" ", " / "));
					$(".game:nth-child(" + (i + 1) + ")").children("a").children("h3:nth-child(1)").html(data.game.author);
					$(".game:nth-child(" + (i + 1) + ")").children("a").children("h3:nth-child(2)").html(data.game.created);
                    $(".game:nth-child(" + (i + 1) + ") .background").attr("style", "background: url('" + data.game.background + "') no-repeat left top;");
                    
                    $(".game:nth-child(" + (i + 1) + ")").fadeIn(400);
                    if (++i < $(".game").length) myLoop();
                });
            });
        })(); 
    }


    $(document).ready(function () {
        $("#filterContainer #searchBar").keyup(function () {
            $.each(gamesArr.games, function (key, game) {
                var searchVal = $("#filterContainer #searchBar").val().toLowerCase().split(" ");
                
                for (var i in searchVal) {
                    var containsSearch = false;
                    
                    if (!/\S/.test(searchVal)) containsSearch = true;

                    if (game.name.toLowerCase().indexOf() !== -1 ||
                        game.tags.toLowerCase().split(" ").join(" / ").indexOf(searchVal) !== -1 ||
                        game.description.toLowerCase().indexOf(searchVal) !== -1) {
                        containsSearch = true;
                    }

                    if (!containsSearch) $(".game#" + game.id).attr("style", "display: none;");
                    else $(".game#" + game.id).attr("style", "");

                    console.log(containsSearch);
                }
            });
        });
        
        $("#filterContainer select").change(function () {
            switch ($(this).val()) {
                default:
                    console.log("Unknown ComboBox value \"" + $(this).val() + "\"");
                    break;
                case "0":
					// Sort A - Z
					
					// Sorting function
					function compare(a,b) {
						if (a.name.toLowerCase() < b.name.toLowerCase())
							return -1;
						if (a.name.toLowerCase() > b.name.toLowerCase())
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare);
                    break;
                case "1":
					// Sort Z - A
					
					// Sorting function
					function compare2(a,b) {
						if (a.name.toLowerCase() > b.name.toLowerCase())
							return -1;
						if (a.name.toLowerCase() < b.name.toLowerCase())
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare2);
                    break;
                case "2":
                    // Sort newest
					
					// Sorting function
					function compare3(a,b) {
						if (a.created.toLowerCase() < b.created.toLowerCase())
							return -1;
						if (a.created.toLowerCase() > b.created.toLowerCase())
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare3);
                    break;
                case "3":
					// Sort oldest
					
					// Sorting function
					function compare4(a,b) {
						if (a.created.toLowerCase() > b.created.toLowerCase())
							return -1;
						if (a.created.toLowerCase() < b.created.toLowerCase())
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare4);
                    break;
                case "4":
					// Most popular
					
					// Sorting function
					function compare5(a,b) {
						if (parseInt(a.playcount.toLowerCase()) > parseInt(b.playcount.toLowerCase()))
							return -1;
						if (parseInt(a.playcount.toLowerCase()) < parseInt(b.playcount.toLowerCase()))
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare5);
                    break;
                case "5":
					// Least popular
					
					// Sorting function
					function compare6(a,b) {
						if (parseInt(a.playcount.toLowerCase()) < parseInt(b.playcount.toLowerCase()))
							return -1;
						if (parseInt(a.playcount.toLowerCase()) > parseInt(b.playcount.toLowerCase()))
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare6);
                    break;
                case "6":
					// Highest rating
					
					// Sorting function
					function compare7(a,b) {
						if (a.rating > b.rating)
							return -1;
						if (a.rating < b.rating)
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare7);
                    break;
                case "7":
					// Lowest rating
					
					// Sorting function
					function compare8(a,b) {
						if (a.rating < b.rating)
							return -1;
						if (a.rating > b.rating)
							return 1;
						return 0;
					}
					
					gamesArr.games = gamesArr.games.sort(compare8);
                    break;
            }
			Update();
        });
    
        Update();
    });
}

</script>