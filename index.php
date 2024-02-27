<?php
$host_name = 'localhost';
$database = 'dbs12634471';
$user_name = 'root';
$password = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['blazeBlaze'])) {
    $blazeBlaze = $_POST['blazeBlaze'];

    // Créer une connexion
    $conn = new mysqli($host_name, $user_name, $password, $database);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer la requête SQL
    $stmt = $conn->prepare("INSERT INTO blaze (blazeBlaze) VALUES (?)");
    $stmt->bind_param("s", $blazeBlaze);

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Web Page</title>
    <link rel="stylesheet" href="style/main.css">
</head>
<body>
    <div class="navbar">
        <!-- Replace with your actual logo and navigation items -->
        <div class="logo"><img src="image/logo.png" alt="logo"></div>
        <div class="nav-items">
            <a href="#"><img src="image/logo.png" alt="ajouter un blaze"></a>
            <a href="#"><img src="image/logo.png" alt="les blazes"></a>
            <a href="#"><img src="image/logo.png" alt="mon compte"></a>
        </div>
    </div>
    <div class="input-container">
        <!-- Form to handle the input -->
        <form id="myForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="inputText">Entrez le texte ici:</label>
            <input type="text" id="blazeInput" name="blazeBlaze">
            <input type="submit" value="Entrer texte">
        </form>
    </div>
</body>
</html>
