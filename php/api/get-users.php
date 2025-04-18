<?php
header('Content-Type: application/json');
require_once '../models/Database.php';

$conn = connectDatabase();

$query = "SELECT idUtilisateur, nom, prenom, email, telephone FROM Utilisateur";
$stmt = $conn->prepare($query);
$success = $stmt->execute();

if ($success) {
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Une erreur est survenue lors de la récupération des utilisateurs.']);
}
?>
