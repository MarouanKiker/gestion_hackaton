<?php
require_once '../models/Database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : 'utilisateur';

    if ($nom == '' || $prenom == '' || $email == '' || $telephone == '' || $password == '') {
        $response['message'] = "Tous les champs sont requis.";
        echo json_encode($response);
        exit;
    }

    try {
        $db = connectDatabase();
        $db = connectDatabase();
if ($db === null) {
    $response['message'] = "Database connection failed.";
    echo json_encode($response);
    exit;
}


        $checkQuery = "SELECT idUtilisateur FROM Utilisateur WHERE email = :email";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->fetch()) {
            $response['message'] = "Cet email est déjà utilisé.";
            echo json_encode($response);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO Utilisateur (nom, prenom, email, telephone, password, role) VALUES (:nom, :prenom, :email, :telephone, :password, :role)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        $idUtilisateur = $db->lastInsertId();

        if ($role === 'participant') {
            $query = "INSERT INTO Participant (nom, prenom, email, telephone, idUtilisateur) VALUES (:nom, :prenom, :email, :telephone, :idUtilisateur)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':idUtilisateur' => $idUtilisateur,
            ]);
        } elseif ($role === 'admin') {
            $query = "INSERT INTO Admin (nom, prenom, email, telephone, idUtilisateur) VALUES (:nom, :prenom, :email, :telephone, :idUtilisateur)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':idUtilisateur' => $idUtilisateur,
            ]);
        } elseif ($role === 'jury') {
            $specialite = isset($_POST['specialite']) ? $_POST['specialite'] : '';
            $query = "INSERT INTO Jury (nom, prenom, email, telephone, Specialite, idUtilisateur) VALUES (:nom, :prenom, :email, :telephone, :specialite, :idUtilisateur)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':specialite' => $specialite,
                ':idUtilisateur' => $idUtilisateur,
            ]);
        } elseif ($role === 'mentor') {
            $domaineExpretise = isset($_POST['domaineExpretise']) ? $_POST['domaineExpretise'] : '';
            $query = "INSERT INTO Mentor (nom, prenom, email, telephone, domaineExpretise, idUtilisateur) VALUES (:nom, :prenom, :email, :telephone, :domaineExpretise, :idUtilisateur)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':domaineExpretise' => $domaineExpretise,
                ':idUtilisateur' => $idUtilisateur,
            ]);
        }

        $response['success'] = true;
        $response['message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
    } catch (PDOException $e) {
        $response['message'] = "Erreur serveur lors de l'inscription : " . $e->getMessage();
    }
} else {
    $response['message'] = "Méthode non autorisée.";
}

echo json_encode($response);
?>
