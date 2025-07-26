<?php
include_once('connexion.php');

// Récupère la période depuis l'URL (par défaut "day")
$period = isset($_GET['period']) ? $_GET['period'] : 'day';

$whereClause = '';
switch ($period) {
    case 'day':
        $whereClause = "WHERE DATE(blazeDate) = CURDATE()";
        break;
    case 'month':
        $whereClause = "WHERE MONTH(blazeDate) = MONTH(CURDATE()) AND YEAR(blazeDate) = YEAR(CURDATE())";
        break;
    case 'year':
        $whereClause = "WHERE YEAR(blazeDate) = YEAR(CURDATE())";
        break;
    case 'all':
    default:
        $whereClause = ""; // Pas de filtre
        break;
}

$sql = "SELECT blazeBlaze, blazeNote FROM blaze $whereClause ORDER BY blazeNote DESC LIMIT 5";
$result = $conn->query($sql);

$top = [];
while ($row = $result->fetch_assoc()) {
    $top[] = $row;
}

header('Content-Type: application/json');
echo json_encode($top);
