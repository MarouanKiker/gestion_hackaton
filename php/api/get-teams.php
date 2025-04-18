<?php
header('Content-Type: application/json');
require_once '../models/Database.php';

$conn = connectDatabase();

if ($conn === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
    exit;
}

try {
    $query = "SELECT IdEquipe, nom FROM Equipe";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($teams);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la récupération des équipes.']);
}
?>
