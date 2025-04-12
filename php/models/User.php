<?php
require_once 'Database.php';

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createUser($nom, $prenom, $email, $telephone) {
        $query = "INSERT INTO utilisateur (nom, prenom, email, telephone) VALUES (:nom, :prenom, :email, :telephone)";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone
            ]);
            return ["success" => true, "message" => "Utilisateur créé avec succès."];
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage(), 3, "c:/xampp/php/logs/error.log");
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
?>