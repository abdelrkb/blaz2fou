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
            { 
                "data": "note",
                "render" : function(data, type, row) {
                    return `<div class='bouton-note'>${data}/10 <button class="btn-rate" data-id="${row.id}" data-note="${row.user_note}"><i class="fa-solid fa-pen"></i></button><div>`;
                }
            },
            { "data": "user" }
        ],
        "language": {
            "url": "files/French.json" // Assurez-vous que ce chemin est correct
        }, 
        "autoWidth": false,  // Ajoutez cette ligne
        "responsive": true   // Ajoutez cette ligne
    });

    $('#blazeTable tbody').on('click', '.btn-rate', function() {
        var blazeId = $(this).data('id');
        var userNote = $(this).data('note');
        var currentNoteText = userNote > 0 ? `Vous avez donné une note de ${userNote}.` : 'Vous n\'avez pas encore noté ce blaze.';

        Swal.fire({
            title: 'Donnez votre note',
            text: currentNoteText,
            input: 'number',
            inputAttributes: {
                min: 1,
                max: 5,
                step: 1
            },
            inputValue: userNote,
            showCancelButton: true,
            confirmButtonText: 'Valider',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('submit_note.php', {
                    blaze_id: blazeId,
                    note: result.value
                }, function(response) {
                    if(response.success) {
                        Swal.fire('Merci!', 'Votre note a été enregistrée.', 'success');
                        $('#blazeTable').DataTable().ajax.reload();
                    }
                }, 'json');
            }
        });
    });
});

</script>

</body>
<script src="https://kit.fontawesome.com/f9983d149e.js" crossorigin="anonymous"></script></body>
</html>
