<?php
session_start();
include_once('connexion.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userPseudo = $_POST['username'];
    $userMdp = $_POST['mdp'];

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Vérifier les informations d'identification
    $stmt = $conn->prepare("SELECT * FROM user WHERE userPseudo = ?");
    $stmt->bind_param("s", $userPseudo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($userMdp, $row['userMdp'])) {
            
            $_SESSION['user_id'] = $row['userId'];
            $_SESSION['user_pseudo'] = $row['userPseudo'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Pseudo ou mot de passe incorrect.";
            header("Location: signin.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Pseudo ou mot de passe incorrect.";
        header("Location: signin.php");
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
    <title>Connexion</title>
    <link rel="stylesheet" href="style/main.css">
</head>
<body class="formu">
<div class="login-container">
    <h2>Se Connecter</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form class="login-form" action="signin.php" method="post">
        <div class="form-group">
            <input type="text" name="username" placeholder="Pseudo" required>
        </div>
        <div class="form-group">
            <input type="password" name="mdp" placeholder="Mot de passe" required>
        </div>
        <button type="submit">Connexion</button>
        <div class="form-footer">
            <a href="#">Vous avez oublié votre mot de passe ?</a>
            <a href="signup.php">Vous n'avez pas de compte ? Créez en un.</a>
        </div>
    </form>
</div>
</body>
</html>


