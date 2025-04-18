<?php
session_start();
require_once '../models/Database.php';


header('Content-Type: application/json');

$db = connectDatabase();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($method == 'GET' && $action == 'list') {
    $query = "SELECT * FROM equipe";
    $result = $db->query($query);
    $teams = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($teams);
    exit;
}

if ($method == 'DELETE' && $action == 'delete') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
        echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
        exit;
    }
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID manquant pour suppression.']);
        exit;
    }
    $query = "DELETE FROM equipe WHERE IdEquipe = $id";
    $result = $db->exec($query);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Équipe supprimée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    }
    exit;
}

if ($method == 'POST') {
    $teamName = isset($_POST['teamName']) ? $_POST['teamName'] : null;
    $teamSize = isset($_POST['teamSize']) ? $_POST['teamSize'] : null;

    if (empty($teamName) || empty($teamSize)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }

    $query = "INSERT INTO equipe (nom, tailleMax) VALUES ('$teamName', $teamSize)";
    $result = $db->exec($query);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Équipe créée avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de l\'équipe.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Méthode non autorisée ou action inconnue.']);
?>
