<?php
header('Content-Type: application/json');
require_once '../models/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT id, username, email, created_at FROM users";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while fetching users.', 'details' => $e->getMessage()]);
}
?>