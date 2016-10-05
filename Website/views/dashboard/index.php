<div id="tableWrapper" style="position: absolute; top: 75px; bottom: 75px; left: 100px; right: 100px;">
    <table id="table"  class="display" cellspacing="0" width="100%">
        <thead id="header">
            <th id="created">Controls</th>
            <th id="name">Name</th>
            <th id="tags">tags</th>
            <th id="author">Created by</th>
            <th id="created">Released on</th>
            <th id="created">Activation</th>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    

$(document).ready(function () {
    var jsonObject = <?= json_encode($this->gameList) ?>;
    
    var table = $("#table").dataTable({
        dom: "Bfrtip",
        language: {
            search: "",
            searchPlaceholder: "Search games"
        },
        order: [[ 4, "desc" ]],
        pageLength: 15,
        "fnInitComplete": function(oSettings, json) {
            var api = this.api();
            for (var i = 0; i < jsonObject.games.length; i++) {
                $.get("<?= URL ?>games/getgames/-1/" + jsonObject.games[i].id, function (data) {
                    data = JSON.parse(data);
                    
                    var newRow = api.row.add([<?= $_SESSION["user"]["accountType"] == 0 ?
                        '( data.game.activated == 0 ? ' // If game is not activated
                            . '"<a class=\"tableControlLink2 colorGreen\" href=\"' . URL . 'games/approve/" + data.game.id + "\">APPROVE</a><br>" +'
                            . '"<a class=\"tableControlLink2 colorRed\" href=\"' . URL . 'games/delete/" + data.game.id + "\">REJECT</a><br>"'
                        . ' : '
                            . '( data.game.activated == 1 ? ' // Else if game activated
                                . '"<a class=\"tableControlLink2 colorOrange\" href=\"' . URL . 'games/deactivate/" + data.game.id + "\">DEACTIVATE</a>" +'
                                . '"<a class=\"tableControlLink2 colorRed\" href=\"' . URL . 'games/delete/" + data.game.id + "\">DELETE</a>"'
                            . ' : ' // Else
                                . '( data.game.activated == 2 ? ' // Else if game activated
                                    . '"<a class=\"tableControlLink3 colorRed\" href=\"' . URL . 'games/delete/" + data.game.id + "\">DELETE</a>"'
                                . ' : ' // Else
                                    . '"<a class=\"tableControlLink2 colorGreen\" href=\"' . URL . 'games/approve/" + data.game.id + "\">ACTIVATE</a>" +'
                                    . '"<a class=\"tableControlLink2 colorRed\" href=\"' . URL . 'games/delete/" + data.game.id + "\">DELETE</a>"'
                        . '))) ,' 
                    : 
                        '"<a class=\"tableControlLink3 colorRed\" href=\"' . URL . 'games/delete/" + data.game.id + "\">DELETE</a>",'
                    ?>
                                                
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.name + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.tags + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.author + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.created.split(" ")[0] + "</a>",
                        "<a class=\"tableLink color" + 
                            ( data.game.activated === "0" ? 
                                "Blue" 
                            : 
                                ( data.game.activated === "1" ? 
                                    "Green" 
                                :
                                    ( data.game.activated === "2" ?
                                        "Orange"
                                    :
                                        ( data.game.activated === "3" ?
                                            "Red"
                                        : 
                                            ""
                                        )
                                    )
                                )
                            ) + 
                            " href=\"<?= URL ?>games/app/" + data.game.id + "\">" + ( data.game.activated === "0" ? "Not activated" : ( data.game.activated === "1" ? "Activated" : ( data.game.activated === "2" ? "Missing game files" : ( data.game.activated === "3" ? "Deactivated" : "" ) ) ) ) + "</a>"
                    ]).draw().node();

                    $(newRow).attr("id", data.game.id);
                });
            }
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).children("td").attr("style", "padding: 0;");
        }
    });
});


</script>