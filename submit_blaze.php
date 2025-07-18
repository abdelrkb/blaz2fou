<?php
session_start();
include_once('connexion.php');

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => "non_connecté"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['blaze'])) {
    $blazeBlaze = trim($_POST['blaze']);
    $userId = $_SESSION['user_id'];
    $dateBlaze = date('Y-m-d H:i:s');

    // Vérifier la connexion
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => "Erreur de connexion"]);
        exit();
    }

    // Liste des mots interdits
    $forbidden_word = [];
    $query = 'SELECT fb_name FROM forbidden_blaze';
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $forbidden_word[] = $row['fb_name'];
    }

    foreach ($forbidden_word as $word) {
        if (stripos($blazeBlaze, $word) !== false) {
            echo json_encode(['success' => false, 'error' => "Mot interdit détecté."]);
            exit();
        }
    }

    // Vérifier doublon
    $stmt = $conn->prepare("SELECT * FROM blaze WHERE blazeBlaze = ?");
    $stmt->bind_param("s", $blazeBlaze);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => "Ce blaze existe déjà."]);
        exit();
    }

    // Insertion si OK
    $stmt = $conn->prepare("INSERT INTO blaze (blazeBlaze, blazeDate, userId) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $blazeBlaze, $dateBlaze, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => "Erreur à l’insertion"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => "Requête invalide"]);
}
?>
