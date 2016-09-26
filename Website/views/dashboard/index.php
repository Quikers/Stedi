<div id="tableWrapper" style="position: absolute; top: 75px; bottom: 75px; left: 100px; right: 100px;">
    <table id="table"  class="display" cellspacing="0" width="100%">
        <thead id="header">
            <th id="name">Name</th>
            <th id="genre">Genre</th>
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
        pageLength: 15,
        "fnInitComplete": function(oSettings, json) {
            var api = this.api();
            for (var i = 0; i < jsonObject.games.length; i++) {
                $.get("<?= URL ?>games/getgames/-1/" + jsonObject.games[i].id, function (data) {
                    data = JSON.parse(data);
                    
                    for (var i = 0; i < 60; i++) {
                        var newRow = api.row.add([
                            "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.name + "</a>",
                            "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.genre + "</a>",
                            "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.author + "</a>",
                            "<a class=\"tableLink\" href=\"<?= URL ?>games/app/" + data.game.id + "\">" + data.game.created + "</a>",
                            data.game.activated
                        ]).draw().node();

                        $(newRow).attr("id", data.game.id);
                    }
                });
            }
        },
        "createdRow": function( row, data, dataIndex ) {
            $(row).children("td:not(td:last-of-type())").attr("style", "padding: 0;");
            
            $(row).children("td").bind({
                mouseenter: function () {
                    console.log($(this).siblings());
                    $(this).siblings().children().bind("mouseenter");
                },
                mouseleave: function () {
                    console.log("Mouse Leave");
                    $(this).siblings().children().unbind("mouseenter");
                }
            });
        }
    });
});


</script>