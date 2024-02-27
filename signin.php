<link rel="stylesheet" href="style/main.css">

<?php


echo '
<title>Connexion </title>
</head>
<body>
<div class="login-container">
    <h2>Se Connecter</h2>
    <form class="login-form" action="signin.php" method="post">
        <div class="form-group">
            <input type="text" id="username" placeholder="Pseudo" required>
        </div>
        <div class="form-group">
            <input type="password" id="mdp" placeholder="Mot de passe" required>
        </div>
        <button type="submit">Connexion</button>
        <div class="form-footer">
            <a href="#">Vous avez oublié votre mot de passe ?</a>
            <a href="signup.php">Vous n\'avez pas de compte ? Créez en un.</a>
        </div>
    </form>
</div>
</body>
</html>
';
?>