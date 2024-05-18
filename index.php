<?php
session_start();

include_once('connexion.php');

$toastMessage = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['blaze'])) {
    $blazeBlaze = $_POST['blaze'];
    $userId = '1'; // Ou une autre source pour l'userId
    $dateBlaze = date('Y-m-d');


    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérifier si le blaze existe déjà
    $stmt = $conn->prepare("SELECT * FROM blaze WHERE blazeBlaze = ?");
    $stmt->bind_param("s", $blazeBlaze);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['toastMessage'] = "Ce blaze existe déjà.";
    } else {
        // Le blaze n'existe pas, on peut l'insérer
        $stmt = $conn->prepare("INSERT INTO blaze (blazeBlaze, blazeDate, userId) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $blazeBlaze, $dateBlaze, $userId);
        if ($stmt->execute()) {
            $_SESSION['toastMessage'] = "Nouveau blaze ajouté avec succès.";
        } else {
            $_SESSION['toastMessage'] = "Erreur lors de l'ajout du blaze.";
        }
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();

    // Rediriger pour éviter le rechargement du formulaire
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['toastMessage'])) {
    $toastMessage = $_SESSION['toastMessage'];
    unset($_SESSION['toastMessage']);
}
include_once('head.php');
?>



<main class="container">
    <form action="index.php" method="post" class="my-4">
        <div class="form-group">
            <label for="blazeInput">Entre ton Blaze2Fou vite là !</label>
            <input type="text" id="blazeInput" name="blaze" class="form-control" placeholder="Entrez votre blaze de dingue">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
    
    <div id="toast" class="toast" style="position: absolute; top: 50%; right: 50%; transform: translateX(-50%);">
        <div class="toast-body"></div>
    </div>

    <h2>Les 10 derniers blazes</h2>
    <table id="blazeTable" class="table table-striped">
        <thead>
            <tr>
                <th>Blaze</th>
                <th>Date</th>
                <th>Note</th>
                <th>Blazeur</th>
            </tr>
        </thead>
        <tbody>
            <!-- Les données seront insérées ici par JavaScript -->
        </tbody>
    </table>

    <script>
// Fonction pour montrer un toast
function showToast(message) {
    var toast = $('#toast');
    toast.find('.toast-body').text(message);
    toast.toast({ delay: 3000 });
    toast.toast('show');
}

<?php if (!empty($toastMessage)): ?>
// Montrer le toast avec le message PHP si nécessaire
showToast("<?php echo $toastMessage; ?>");
<?php endif; ?>

// Fonction pour charger les blazes
function loadBlazes() {
    $.ajax({
        url: 'load_blazes.php',
        method: 'GET',
        dataType: 'json',
        success: function(blazes) {
            var tableBody = $('#blazeTable tbody');
            tableBody.empty();

            blazes.forEach(function(blaze) {
                var row = $('<tr></tr>');
                row.append('<td>' + blaze.blaze + '</td>');
                row.append('<td>' + blaze.date + '</td>');
                row.append('<td>' + blaze.note + '</td>');
                row.append('<td>' + blaze.user + '</td>');
                tableBody.append(row);
            });
        },
        error: function() {
            showToast('Erreur lors du chargement des blazes.');
        }
    });
}

// Charger les blazes au chargement de la page
$(document).ready(function() {
    loadBlazes();
});

// Recharger les blazes toutes les 10 secondes
setInterval(loadBlazes, 10000);
</script>

</main>

<script src="https://kit.fontawesome.com/f9983d149e.js" crossorigin="anonymous"></script></body>
</html>
