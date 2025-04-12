<?php
require_once '../models/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                echo json_encode(["success" => true, "message" => "Connexion réussie.", "user" => $user]);
            } else {
                echo json_encode(["success" => false, "message" => "Mot de passe incorrect."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Utilisateur non trouvé."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Tous les champs sont requis."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée."]);
}
?>