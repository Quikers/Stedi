<div id="tableWrapper" style="position: absolute; top: 75px; left: 100px; right: 100px;">
    <table id="table"  class="display" cellspacing="0" width="100%">
        <thead id="header">
            <th id="name">Name</th>
            <th id="genre">Genre</th>
            <th id="author">Created by</th>
            <th id="created">Released on</th>
            <th id="created">Activated</th>
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
        "fnInitComplete": function(oSettings, json) {
            var api = this.api();
            for (var i = 0; i < jsonObject.games.length; i++) {
                $.get("<?= URL ?>games/getgames/-1/" + jsonObject.games[i].id, function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    
                    api.row.add([
                        data.game.name,
                        data.game.genre,
                        data.game.author,
                        data.game.created,
                        data.game.activated
                    ]);
                    
                    api.draw();
                });
            }
        }
    });
});


</script>