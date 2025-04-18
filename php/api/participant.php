<?php
header('Content-Type: application/json');
require_once '../models/Database.php';

$conn = connectDatabase();

if ($conn === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    if ($method === 'GET' && $action === 'list') {
        $query = "SELECT IdParticipant, nom, prenom, email, telephone, competences, equipe, IdEquipe FROM Participant";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($participants);
        exit;
    }

    if ($method === 'DELETE' && $action === 'delete') {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID manquant pour suppression.']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM Participant WHERE IdParticipant = :id");
        $success = $stmt->execute([':id' => $id]);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Participant supprimé avec succès.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression.']);
        }
        exit;
    }

    if ($method === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $competences = $_POST['competences'] ?? '';
        $idEquipe = $_POST['idEquipe'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$nom || !$prenom || !$email || !$telephone || !$idEquipe || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Tous les champs requis ne sont pas remplis.']);
            exit;
        }

        if (!filter_var($idEquipe, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            http_response_code(400);
            echo json_encode(['error' => 'Identifiant d\'équipe invalide.']);
            exit;
        }

        // Check if team exists
        $stmt = $conn->prepare("SELECT nom FROM Equipe WHERE IdEquipe = :idEquipe");
        $stmt->execute([':idEquipe' => $idEquipe]);
        $teamName = $stmt->fetchColumn();
        if (!$teamName) {
            http_response_code(400);
            echo json_encode(['error' => 'L\'équipe spécifiée n\'existe pas.']);
            exit;
        }

        // Check if user email exists in Utilisateur
        $stmt = $conn->prepare("SELECT idUtilisateur FROM Utilisateur WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $existingUserId = $stmt->fetchColumn();
        if ($existingUserId) {
            http_response_code(409);
            echo json_encode(['error' => 'Un utilisateur avec cet email existe déjà.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn->beginTransaction();

            // Insert into Utilisateur
            $stmt = $conn->prepare("INSERT INTO Utilisateur (nom, prenom, email, telephone, password, role) VALUES (:nom, :prenom, :email, :telephone, :password, 'participant')");
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':password' => $hashedPassword
            ]);
            $idUtilisateur = $conn->lastInsertId();

            // Insert into Participant
            $stmt = $conn->prepare("INSERT INTO Participant (nom, prenom, email, equipe, competences, telephone, IdEquipe, idUtilisateur) VALUES (:nom, :prenom, :email, :equipe, :competences, :telephone, :idEquipe, :idUtilisateur)");
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':equipe' => $teamName,
                ':competences' => $competences,
                ':telephone' => $telephone,
                ':idEquipe' => $idEquipe,
                ':idUtilisateur' => $idUtilisateur
            ]);

            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Participant créé avec succès.']);
        } catch (PDOException $e) {
            $conn->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création du participant : ' . $e->getMessage()]);
        }
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur interne du serveur.']);
}
?>
