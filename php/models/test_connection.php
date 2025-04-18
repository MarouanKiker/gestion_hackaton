<?php
require_once 'Database.php';

$conn = connectDatabase();

if ($conn) {
    echo "Connexion réussie à la base de données.";
} else {
    echo "Échec de la connexion à la base de données. Veuillez vérifier les paramètres et le serveur.";
}
?>
