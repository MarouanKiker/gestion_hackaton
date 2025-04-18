<?php
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectData = json_decode(file_get_contents('php://input'), true);

    if (isset($projectData['projectId']) && isset($projectData['evaluationCriteria'])) {
        $projectId = $projectData['projectId'];
        $evaluationCriteria = $projectData['evaluationCriteria'];

        // Mock evaluation result for demonstration
        $evaluationResult = [
            'projectId' => $projectId,
            'score' => rand(1, 100),
            'comments' => 'Evaluation completed successfully.'
        ];

        echo json_encode($evaluationResult);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
}
?>
