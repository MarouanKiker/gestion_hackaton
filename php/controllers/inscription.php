<?php
require_once '../models/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($telephone)) {
        $user = new User();
        $result = $user->createUser($nom, $prenom, $email, $telephone);

        if ($result['success']) {
            echo "Utilisateur créé avec succès.";
        } else {
            echo "Erreur : " . $result['message'];
        }
    } else {
        echo "Tous les champs sont requis.";
    }
} else {
    // Redirection pour les requêtes GET
    header("Location: ../../public/test_inscription.html");
    exit();
}
?>