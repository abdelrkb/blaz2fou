<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Bouton Ajouter</title>
  <style>
    .form-container {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 50px;
    }

    #inputField {
      display: none;
      padding: 8px;
      font-size: 16px;
    }

    #ajouterBtn, #validerBtn {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }

    #validerBtn {
      display: none;
    }
  </style>
</head>
<body>

  <div id="zone">
    <button id="ajouterBtn">Ajouter</button>
  </div>

  <form class="form-container" id="formulaire">
    <input type="text" id="inputField" placeholder="Input" name="input">
    <button type="submit" id="validerBtn">✔</button>
  </form>

  <script>
    const ajouterBtn = document.getElementById('ajouterBtn');
    const inputField = document.getElementById('inputField');
    const validerBtn = document.getElementById('validerBtn');
    const formulaire = document.getElementById('formulaire');

    formulaire.style.display = 'none';

    ajouterBtn.addEventListener('click', () => {
      ajouterBtn.style.display = 'none';
      formulaire.style.display = 'flex';
      inputField.style.display = 'inline-block';
      validerBtn.style.display = 'inline-block';
      inputField.focus();
    });
  </script>

</body>
</html>
