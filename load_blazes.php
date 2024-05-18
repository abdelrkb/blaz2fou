<?php
include_once('connexion.php');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les 10 derniers blazes
$sql = "SELECT * FROM blaze ORDER BY blazeDate DESC LIMIT 10";
$result = $conn->query($sql);

$blazes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $blaze = [];
        $blaze['blaze'] = $row['blazeBlaze'];
        $date = new DateTime($row['blazeDate']);
        $blaze['date'] = $date->format('d/m/Y');
        $blaze['note'] = $row['blazeNote'];
        $query_user = "SELECT userPseudo FROM user WHERE userId = ".$row['userId'] ;
        $result_user = $conn->query($query_user);
        $row_user = $result_user->fetch_assoc();
        $blaze['user'] = $row_user['userPseudo'];
        $blazes[] = $blaze;

    }
}

// Fermer la connexion
$conn->close();

// Retourner les données en JSON
header('Content-Type: application/json');
echo json_encode($blazes);
?>
