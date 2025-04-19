<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../models/Database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email == '' || $password == '') {
        $response['message'] = "Email et mot de passe requis.";
        echo json_encode($response);
        exit;
    }

    try {
        $db = connectDatabase();

        $query = "SELECT * FROM Utilisateur WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['idUtilisateur'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => isset($user['role']) ? $user['role'] : 'participant'
            ];
            $response['success'] = true;
            $response['message'] = "Connexion réussie.";
        } else {
            $response['message'] = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $response['message'] = "Erreur serveur lors de la connexion : " . $e->getMessage();
    }
} else {
    $response['message'] = "Méthode non autorisée.";
}

echo json_encode($response);
?>
