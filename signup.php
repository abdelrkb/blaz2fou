<?php
session_start();
include_once('connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userPseudo = $_POST['username'];
    $userNom = ''; // Vous pouvez ajouter un champ pour le nom si nécessaire
    $userPrenom = ''; // Vous pouvez ajouter un champ pour le prénom si nécessaire
    $userDateN = $_POST['daten'];
    $userMdp = password_hash($_POST['mdp'], PASSWORD_BCRYPT); // Hacher le mot de passe pour plus de sécurité
    $userMail = $_POST['email'];
    $userToken = rand(100000, 999999); // Générer un token aléatoire
    $userValid = 0; // 0 pour non validé par défaut

    // Vérifier si les mots de passe correspondent
    if ($_POST['mdp'] != $_POST['mdpconf']) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: signup.php");
        exit();
    }

    // Vérifier si les emails correspondent
    if ($_POST['email'] != $_POST['emailconf']) {
        $_SESSION['error'] = "Les emails ne correspondent pas.";
        header("Location: signup.php");
        exit();
    }

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérifier si le pseudo ou l'email existe déjà
    $stmt = $conn->prepare("SELECT * FROM user WHERE userPseudo = ? OR userMail = ?");
    $stmt->bind_param("ss", $userPseudo, $userMail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Le pseudo ou l'email existe déjà.";
        header("Location: signup.php");
        exit();
    }

    // Insérer le nouvel utilisateur
    $stmt = $conn->prepare("INSERT INTO user (userPseudo, userNom, userPrenom, userDateN, userMdp, userMail, userToken, userValid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $userPseudo, $userNom, $userPrenom, $userDateN, $userMdp, $userMail, $userToken, $userValid);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Compte créé avec succès. Veuillez vérifier votre email pour valider votre compte.";
        // Envoyer un email de validation ici si nécessaire
        header("Location: signin.php");
        exit();
    } else {
        $_SESSION['error'] = "Erreur lors de la création du compte.";
        header("Location: signup.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="style/connexion.css">
</head>
<body class="formu">
<div class="login-container">
    <h2>Créer un compte</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form class="login-form" action="signup.php" method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="mdp" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <input type="password" name="mdpconf" placeholder="Confirmez Mot de passe" required>
        </div>
        <div class="form-group">
            <input type="date" name="daten" placeholder="Date de Naissance" required>
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder="Adresse mail" required>
        </div>
        <div class="form-group">
            <input type="email" name="emailconf" placeholder="Confirmez Adresse mail" required>
        </div>
        <button type="submit">Créer un compte</button>
        <div class="form-footer">
            <a href="signin.php">Retour</a>
        </div>
    </form>
</div>
</body>
</html>


