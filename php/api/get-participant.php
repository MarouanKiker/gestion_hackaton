<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../models/Database.php';

$conn = connectDatabase();

if ($conn === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
    exit;
}

try {
    $query = "SELECT nom, prenom, email, telephone, competences, equipe, IdEquipe, idUtilisateur FROM participant";
    $stmt = $conn->prepare($query);
    $success = $stmt->execute();

    if ($success) {
        $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($participants);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Une erreur est survenue lors de la récupération des participants.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur PDO: ' . $e->getMessage()]);
}
?>
