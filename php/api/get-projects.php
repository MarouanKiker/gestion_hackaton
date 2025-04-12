<?php
header('Content-Type: application/json');
require_once '../models/Database.php';

try {
    // Connect to the database
    $db = new Database();
    $conn = $db->getConnection();

    // Query to fetch projects
    $query = "SELECT id, title, description FROM projects";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch all projects as an associative array
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the projects as JSON
    echo json_encode($projects);
} catch (Exception $e) {
    // Return an error response if something goes wrong
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while fetching projects.', 'details' => $e->getMessage()]);
}
?>