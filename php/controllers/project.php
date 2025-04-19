<?php
require_once '../models/Database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $idEquipe = isset($_POST['idEquipe']) ? $_POST['idEquipe'] : '';

    if ($titre == '' || $description == '' || $idEquipe == '') {
        echo "Tous les champs sont requis.";
        return;
    }

    if (!filter_var($idEquipe, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        echo "Identifiant d'équipe invalide.";
        return;
    }

    $db = connectDatabase();

    $stmt = $db->prepare("SELECT COUNT(*) FROM equipe WHERE IdEquipe = :idEquipe");
    $stmt->execute([':idEquipe' => $idEquipe]);
    if ($stmt->fetchColumn() == 0) {
        echo "L'équipe spécifiée n'existe pas.";
        return;
    }

    $stmt = $db->prepare("INSERT INTO Projet (titre, description, dateSoumission, status, IdEquipe) VALUES (:titre, :description, NOW(), 'submitted', :idEquipe)");
    $success = $stmt->execute([
        ':titre' => $titre,
        ':description' => $description,
        ':idEquipe' => $idEquipe
    ]);

    if ($success) {
        echo "Projet créé avec succès.";
    } else {
        echo "Erreur lors de la création du projet.";
    }
    return;
}

echo "Méthode non autorisée.";
?>
