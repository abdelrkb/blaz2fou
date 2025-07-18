<?php
session_start();
header('Content-Type: application/json');
require_once 'connexion.php';

$userId = $_SESSION['user_id'] ?? null;
$blazeId = $_POST['blaze_id'] ?? null;
$note = $_POST['note'] ?? null;

if (!$userId || !$blazeId || !$note) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants.']);
    exit;
}

// Sécuriser les données
$userId = intval($userId);
$blazeId = intval($blazeId);
$note = intval($note);

// Vérifier si une note existe déjà
$sql = "SELECT noteId FROM note WHERE userId = ? AND blazeId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $blazeId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mise à jour de la note
    $sqlUpdate = "UPDATE note SET noteVal = ? WHERE userId = ? AND blazeId = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("iii", $note, $userId, $blazeId);
    $stmtUpdate->execute();
} else {
    // Insertion d'une nouvelle note
    $sqlInsert = "INSERT INTO note (noteVal, userId, blazeId) VALUES (?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iii", $note, $userId, $blazeId);
    $stmtInsert->execute();
}

// Recalculer la moyenne des notes
$sqlAvg = "SELECT AVG(noteVal) AS moyenne FROM note WHERE blazeId = ?";
$stmtAvg = $conn->prepare($sqlAvg);
$stmtAvg->bind_param("i", $blazeId);
$stmtAvg->execute();
$resultAvg = $stmtAvg->get_result()->fetch_assoc();
$moyenne = round(floatval($resultAvg['moyenne']), 1);

// Mettre à jour la moyenne dans la table blaze
$sqlUpdateBlaze = "UPDATE blaze SET blazeNote = ? WHERE blazeId = ?";
$stmtUpdateBlaze = $conn->prepare($sqlUpdateBlaze);
$stmtUpdateBlaze->bind_param("di", $moyenne, $blazeId);
$stmtUpdateBlaze->execute();

// Réponse
echo json_encode(['success' => true, 'new_average' => $moyenne]);
?>
