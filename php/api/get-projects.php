<?php
header('Content-Type: application/json');
require_once '../models/Database.php';


$conn = connectDatabase();

$query = "SELECT IdProjet AS id, titre AS title, description FROM Projet";
$stmt = $conn->prepare($query);
$success = $stmt->execute();

if ($success) {
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($projects);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Une erreur est survenue lors de la récupération des projets.']);
}
?>
