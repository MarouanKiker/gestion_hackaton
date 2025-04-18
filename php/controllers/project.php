<?php
require_once '../models/Database.php';


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $idEquipe = $_POST['idEquipe'] ?? '';

    if (!$titre || !$description || !$idEquipe) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }

    if (!filter_var($idEquipe, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        echo json_encode(['success' => false, 'message' => 'Identifiant d\'équipe invalide.']);
        exit;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("SELECT COUNT(*) FROM equipe WHERE IdEquipe = :idEquipe");
    $stmt->execute([':idEquipe' => $idEquipe]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'L\'équipe spécifiée n\'existe pas.']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO Projet (titre, description, dateSoumission, status, IdEquipe) VALUES (:titre, :description, NOW(), 'submitted', :idEquipe)");
    $success = $stmt->execute([
        ':titre' => $titre,
        ':description' => $description,
        ':idEquipe' => $idEquipe
    ]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Projet créé avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du projet.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
?>
