<?php
require_once '../models/Database.php';


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'participant';

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }

    $db = connectDatabase();

    $checkQuery = "SELECT idUtilisateur FROM Utilisateur WHERE email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute([':email' => $email]);
    if ($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
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
        $query = "INSERT INTO participant (nom, prenom, email, telephone, idUtilisateur) VALUES (:nom, :prenom, :email, :telephone, :idUtilisateur)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':idUtilisateur' => $idUtilisateur,
        ]);
    } elseif ($role === 'admin') {
        $query = "INSERT INTO admin (nom, prenom, email, telephone, idUtilisateur) VALUES (:nom, :prenom, :email, :telephone, :idUtilisateur)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':idUtilisateur' => $idUtilisateur,
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Inscription réussie. Vous pouvez maintenant vous connecter.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>
