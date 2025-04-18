<?php
ini_set('display_errors', 0);
error_reporting(0);

require_once '../models/Database.php';

session_start();

header('Content-Type: application/json');

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

try {
    $db = connectDatabase();
    if ($db === null) {
        jsonResponse(['success' => false, 'message' => 'Erreur de connexion à la base de données.'], 500);
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';

    if ($method === 'GET' && $action === 'list') {
        $query = "SELECT nom, prenom, email, telephone, idUtilisateur FROM participant";
        $result = $db->query($query);
        $participants = $result->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse($participants);
    }

    if ($method === 'DELETE' && $action === 'delete') {
        // For testing, disable admin check. Add back if needed.
        /*
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            jsonResponse(['success' => false, 'message' => 'Accès refusé.'], 403);
        }
        */
        $id = $_GET['id'] ?? null;
        if (!$id) {
            jsonResponse(['success' => false, 'message' => 'ID manquant pour suppression.'], 400);
        }
        $stmt = $db->prepare("DELETE FROM participant WHERE idUtilisateur = :id");
        $success = $stmt->execute([':id' => $id]);
        if ($success) {
            jsonResponse(['success' => true, 'message' => 'Participant supprimé avec succès.']);
        } else {
            jsonResponse(['success' => false, 'message' => 'Erreur lors de la suppression.'], 500);
        }
    }

    if ($method === 'POST') {
        $input = $_POST;

        $nom = $input['nom'] ?? '';
        $prenom = $input['prenom'] ?? '';
        $email = $input['email'] ?? '';
        $telephone = $input['telephone'] ?? '';
        $competences = $input['competences'] ?? '';
        $idEquipe = $input['idEquipe'] ?? '';
        $password = $input['password'] ?? '';

        if (!$nom || !$prenom || !$email || !$telephone || !$idEquipe || !$password) {
            jsonResponse(['success' => false, 'message' => 'Tous les champs requis ne sont pas remplis.'], 400);
        }

        if (!filter_var($idEquipe, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            jsonResponse(['success' => false, 'message' => 'Identifiant d\'équipe invalide.'], 400);
        }

        $stmt = $db->prepare("SELECT nom FROM equipe WHERE IdEquipe = :idEquipe");
        $stmt->execute([':idEquipe' => $idEquipe]);
        $teamName = $stmt->fetchColumn();
        if (!$teamName) {
            jsonResponse(['success' => false, 'message' => 'L\'équipe spécifiée n\'existe pas.'], 400);
        }

        $checkUserStmt = $db->prepare("SELECT idUtilisateur FROM utilisateur WHERE email = :email");
        $checkUserStmt->execute([':email' => $email]);
        $existingUserId = $checkUserStmt->fetchColumn();
        if ($existingUserId) {
            jsonResponse(['success' => false, 'message' => 'Un utilisateur avec cet email existe déjà.'], 409);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO utilisateur (nom, prenom, email, telephone, password) VALUES (:nom, :prenom, :email, :telephone, :password)");
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':password' => $hashedPassword
            ]);
            $idUtilisateur = $db->lastInsertId();

            $checkStmt = $db->prepare("SELECT COUNT(*) FROM participant WHERE idUtilisateur = :idUtilisateur");
            $checkStmt->execute([':idUtilisateur' => $idUtilisateur]);
            $exists = $checkStmt->fetchColumn();
            if ($exists) {
                $db->rollBack();
                jsonResponse(['success' => false, 'message' => 'Cet utilisateur est déjà enregistré en tant que participant.'], 409);
            }

            $stmt = $db->prepare("INSERT INTO participant (nom, prenom, email, competences, telephone, equipe, IdEquipe, idUtilisateur) VALUES (:nom, :prenom, :email, :competences, :telephone, :equipe, :idEquipe, :idUtilisateur)");
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':competences' => $competences,
                ':telephone' => $telephone,
                ':equipe' => $teamName,
                ':idEquipe' => $idEquipe,
                ':idUtilisateur' => $idUtilisateur
            ]);
            $db->commit();
            jsonResponse(['success' => true, 'message' => 'Participant créé avec succès.']);
        } catch (PDOException $e) {
            $db->rollBack();
            jsonResponse(['success' => false, 'message' => 'Erreur lors de la création du participant : ' . $e->getMessage()], 500);
        }
    }

    jsonResponse(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
} catch (Exception $ex) {
    jsonResponse(['success' => false, 'message' => 'Erreur interne du serveur.'], 500);
}
?>
