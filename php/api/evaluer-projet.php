<?php
// filepath: hackathon-management/php/api/evaluer-projet.php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the project data from the request
    $projectData = json_decode(file_get_contents('php://input'), true);

    // Validate the input data
    if (isset($projectData['projectId']) && isset($projectData['evaluationCriteria'])) {
        $projectId = $projectData['projectId'];
        $evaluationCriteria = $projectData['evaluationCriteria'];

        // Here you would typically call the Java microservice to evaluate the project
        // For example:
        // $evaluationResult = callJavaEvaluationService($projectId, $evaluationCriteria);

        // Mock evaluation result for demonstration
        $evaluationResult = [
            'projectId' => $projectId,
            'score' => rand(1, 100), // Random score for demonstration
            'comments' => 'Evaluation completed successfully.'
        ];

        // Return the evaluation result as JSON
        echo json_encode($evaluationResult);
    } else {
        // Return an error response if validation fails
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data.']);
    }
} else {
    // Return an error response for unsupported request methods
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
?>