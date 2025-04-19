<?php
session_start();
require_once '../models/Database.php';


$db = connectDatabase();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($method == 'GET' && $action == 'list') {
    $query = "SELECT * FROM equipe";
    $result = $db->query($query);
    $teams = $result->fetchAll(PDO::FETCH_ASSOC);
    foreach ($teams as $team) {
        echo "Equipe: " . $team['nom'] . ", Taille max: " . $team['tailleMax'] . "\n";
    }
    return;
}

if ($method == 'DELETE' && $action == 'delete') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
        echo "Accès refusé.";
        return;
    }
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$id) {
        echo "ID manquant pour suppression.";
        return;
    }
    $query = "DELETE FROM equipe WHERE IdEquipe = $id";
    $result = $db->exec($query);
    if ($result) {
        echo "Équipe supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression.";
    }
    return;
}

if ($method == 'POST') {
    $teamName = isset($_POST['teamName']) ? $_POST['teamName'] : null;
    $teamSize = isset($_POST['teamSize']) ? $_POST['teamSize'] : null;

    if (empty($teamName) || empty($teamSize)) {
        echo "Tous les champs sont requis.";
        return;
    }

    $query = "INSERT INTO equipe (nom, tailleMax) VALUES ('$teamName', $teamSize)";
    $result = $db->exec($query);
    if ($result) {
        echo "Équipe créée avec succès.";
    } else {
        echo "Erreur lors de la création de l'équipe.";
    }
    return;
}

echo "Méthode non autorisée ou action inconnue.";
?>
