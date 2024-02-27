<link rel="stylesheet" href="style/main.css">

<?php


echo '
<title>Créer un compte </title>
</head>
<body>
<div class="login-container">
    <h2>Créer un compte </h2>
    <form class="login-form">
        <div class="form-group">
            <input type="text" id="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" id="mdp" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
        <input type="password" id="mdpconf" placeholder="Confirmez Mot de passe" required>
        </div>
        <div class="form-group">
        <input type="date" id="daten" placeholder="Date de Naissance" required>
        </div>
        <div class="form-group">
        <input type="email" id="email" placeholder="Adresse mail" required>
        </div>
        <div class="form-group">
        <input type="email" id="emailconf" placeholder="Confirmez Adresse mail" required>
        </div>
        <button type="submit">Créer un compte</button>
        <div class="form-footer">
            <a href="signin.php">Retour</a>
        </div>
    </form>
</div>
</body>
</html>
';
?>