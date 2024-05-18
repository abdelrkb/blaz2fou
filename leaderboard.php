<?php
include_once('head.php');
?>

<div class="container">
    <h2>Liste de tous les Blazes</h2>
    <div class="table-responsive">
        <table id="blazeTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Blaze</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#blazeTable').DataTable({
        "ajax": {
            "url": "load_all_blazes.php",
            "dataSrc": ""
        },
        "columns": [
            { "data": "blaze" },
            { "data": "date" },
            { "data": "note" },
            { "data": "user" }
        ],
        "language": {
            "url": "files/French.json" // Assurez-vous que ce chemin est correct
        }
    });
});

</script>

</body>
</html>
