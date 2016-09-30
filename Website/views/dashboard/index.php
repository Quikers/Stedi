<div id="tableWrapper" style="position: absolute; top: 75px; bottom: 75px; left: 100px; right: 100px;">
    <table id="table"  class="display" cellspacing="0" width="100%">
        <thead id="header">
            <?= $_SESSION["user"]["accountType"] == 0 ? "<th id=\"created\">Controls</th>" : "" ?>
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
        order: [[ 1, "desc" ]],
        pageLength: 15,
        "fnInitComplete": function(oSettings, json) {
            var api = this.api();
            for (var i = 0; i < jsonObject.games.length; i++) {
                $.get("<?= URL ?>games/getgames/-1/" + jsonObject.games[i].id, function (data) {
                    data = JSON.parse(data);
                    
                    var newRow = api.row.add([<?= $_SESSION["user"]["accountType"] == 0 ?
                        '( data.game.activated == 0 ? "<a class=\"tableControlLink1\" href=\"<?= URL ?>games/approve/" + data.game.id + "\">APPROVE</a><br>"+'
                        . '"<a class=\"tableControlLink1\" href=\"<?= URL ?>games/delete/" + data.game.id + "\">REJECT</a><br>"+'
                        . '"<a class=\"tableControlLink1\" href=\"<?= URL ?>games/delete/" + data.game.id + "\">DELETE</a>" : "<a class=\"tableControlLink2\" href=\"<?= URL ?>games/delete/" + data.game.id + "\">DELETE</a>")'
                        . ',' : "" ?>
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.name + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.tags + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.author + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.created + "</a>",
                        "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + ( data.game.activated === "0" ? "Not activated" : ( data.game.activated === "1" ? "Activated" : ( data.game.activated === "2" ? "Missing game files" : "" ) ) ) + "</a>"
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