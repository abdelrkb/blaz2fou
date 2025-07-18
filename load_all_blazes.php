<?php
session_start(); // Nécessaire pour $_SESSION
include_once('connexion.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'] ?? null;

$sql = "SELECT * FROM blaze ORDER BY blazeDate DESC";
$result = $conn->query($sql);

$blazes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blaze = [];
        $blaze['id'] = $row['blazeId'];
        $blaze['blaze'] = $row['blazeBlaze'];
        $date = new DateTime($row['blazeDate']);
        $blaze['date'] = $date->format('Y-m-d H:i:s'); // Garde le format pour JS
        $blaze['note'] = $row['blazeNote'];

        // Récupérer le pseudo
        $query_user = "SELECT userPseudo FROM user WHERE userId = " . intval($row['userId']);
        $result_user = $conn->query($query_user);
        $row_user = $result_user->fetch_assoc();
        $blaze['user'] = $row_user['userPseudo'];

        // Récupérer la note de l'utilisateur connecté
        $userNote = null;
        if ($userId) {
            $stmt = $conn->prepare("SELECT noteVal FROM note WHERE blazeId = ? AND userId = ?");
            $stmt->bind_param("ii", $row['blazeId'], $userId);
            $stmt->execute();
            $stmt->bind_result($noteVal);
            if ($stmt->fetch()) {
                $userNote = $noteVal;
            }
            $stmt->close();
        }

        $blaze['user_note'] = $userNote;

        $blazes[] = $blaze;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($blazes);
