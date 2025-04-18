<?php
ob_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Debug: capture any unexpected output before JSON response
register_shutdown_function(function() {
    $output = ob_get_contents();
    if ($output && strpos($output, '{') !== 0) {
        error_log("Unexpected output before JSON response: " . $output);
    }
});

require_once '../models/Database.php';
// require_once '../models/Session.php';
require_once '../models/Participant.php';

session_start();

header('Content-Type: application/json');

$db = connectDatabase();

if ($db === null) {
    http_response_code(500);
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($method === 'GET' && $action === 'list') {
    try {
        $query = "SELECT nom, prenom, email, telephone, idUtilisateur FROM participant";
        $result = $db->query($query);
        $participants = $result->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        ob_end_clean();
        echo json_encode($participants);
    } catch (Exception $e) {
        http_response_code(500);
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erreur lors du chargement des participants.']);
    }
    exit;
}

if ($method === 'DELETE' && $action === 'delete') {
    try {
        // Temporarily disable admin role check for testing
        /*
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
            exit;
        }
        */
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'ID manquant pour suppression.']);
            exit;
        }
        $stmt = $db->prepare("DELETE FROM participant WHERE idUtilisateur = :id");
        $success = $stmt->execute([':id' => $id]);
        if ($success) {
            ob_end_clean();
            echo json_encode(['success' => true, 'message' => 'Participant supprimé avec succès.']);
        } else {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
    } catch (Exception $e) {
        error_log("Error in DELETE participant: " . $e->getMessage());
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erreur interne lors de la suppression.']);
    }
    exit;
}

try {
    if ($method === 'POST') {
        // Debug: log POST data
        error_log("POST data: " . print_r($_POST, true));

        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $competences = $_POST['competences'] ?? '';
        $idEquipe = $_POST['idEquipe'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$nom || !$prenom || !$email || !$telephone || !$idEquipe || !$password) {
            http_response_code(400);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Tous les champs requis ne sont pas remplis.']);
            exit;
        }

        if (!filter_var($idEquipe, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            http_response_code(400);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Identifiant d\'équipe invalide.']);
            exit;
        }

        $stmt = $db->prepare("SELECT nom FROM equipe WHERE IdEquipe = :idEquipe");
        $stmt->execute([':idEquipe' => $idEquipe]);
        $teamName = $stmt->fetchColumn();
        if (!$teamName) {
            http_response_code(400);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'L\'équipe spécifiée n\'existe pas.']);
            exit;
        }

        // Check if utilisateur with same email already exists
        $checkUserStmt = $db->prepare("SELECT idUtilisateur FROM utilisateur WHERE email = :email");
        $checkUserStmt->execute([':email' => $email]);
        $existingUserId = $checkUserStmt->fetchColumn();
        if ($existingUserId) {
            http_response_code(409);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Un utilisateur avec cet email existe déjà.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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
        } catch (PDOException $e) {
            http_response_code(500);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage()]);
            exit;
        }

        // Check if utilisateur already exists in participant to avoid unique constraint violation
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM participant WHERE idUtilisateur = :idUtilisateur");
        $checkStmt->execute([':idUtilisateur' => $idUtilisateur]);
        $exists = $checkStmt->fetchColumn();

        if ($exists) {
            http_response_code(409);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Cet utilisateur est déjà enregistré en tant que participant.']);
            exit;
        }

        $stmt = $db->prepare("INSERT INTO participant (nom, prenom, email, competences, telephone, equipe, IdEquipe, idUtilisateur) VALUES (:nom, :prenom, :email, :competences, :telephone, :equipe, :idEquipe, :idUtilisateur)");
        try {
            $success = $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':competences' => $competences,
                ':telephone' => $telephone,
                ':equipe' => $teamName,
                ':idEquipe' => $idEquipe,
                ':idUtilisateur' => $idUtilisateur
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du participant : ' . $e->getMessage(), 'code' => $e->getCode()]);
            exit;
        }

        if ($success) {
            ob_end_clean();
            echo json_encode(['success' => true, 'message' => 'Participant créé avec succès.']);
        } else {
            http_response_code(500);
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du participant.']);
        }
        exit;
    }
} catch (Exception $ex) {
    http_response_code(500);
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
    exit;
}

http_response_code(405);
ob_end_clean();
echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
?>
