<?php
header('Content-Type: application/json');
require_once '../models/Database.php';

$conn = connectDatabase();

$query = "SELECT COUNT(*) as count FROM equipe";
$stmt = $conn->prepare($query);
$success = $stmt->execute();

if ($success) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['teamCount' => $result['count']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Une erreur est survenue lors de la vérification des équipes.']);
}
?>
