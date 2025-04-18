<?php
session_start();
require_once '../php/models/Database.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}

try {
    $db = connectDatabase();

    // Fetch participants
    $stmt = $db->query("SELECT p.nom, p.prenom, p.email, p.telephone, u.idUtilisateur FROM participant p JOIN utilisateur u ON p.idUtilisateur = u.idUtilisateur");
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch teams
    $stmt = $db->query("SELECT IdEquipe, nom, tailleMax FROM equipe");
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug output
    if (empty($teams)) {
        $teamsError = "Aucune équipe trouvée dans la base de données.";
    } else {
        $teamsError = null;
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Espace Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center font-sans">
    <header class="bg-green-600 text-white w-full p-4 text-center">
        <h1 class="text-2xl font-bold">Espace Administrateur</h1>
        <a href="index.php" class="text-white hover:underline mt-2 inline-block">Retour à l'accueil</a>
    </header>
    <main class="w-full max-w-4xl bg-white shadow-lg rounded-lg p-8 mt-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Gestion des Participants</h2>
        <table class="table-auto w-full bg-white shadow rounded mb-6">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Prénom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Téléphone</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="participantsTable">
                <?php foreach ($participants as $participant): ?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($participant['nom']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($participant['prenom']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($participant['email']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($participant['telephone']) ?></td>
                    <td class="border px-4 py-2 text-center">
                        <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="deleteParticipant(<?= $participant['idUtilisateur'] ?>)">Supprimer</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-xl font-semibold text-gray-800 mb-4 mt-8">Gestion des Équipes</h2>
        <?php if ($teamsError): ?>
            <p class="text-red-600 font-bold mb-4"><?= htmlspecialchars($teamsError) ?></p>
        <?php endif; ?>
        <table class="table-auto w-full bg-white shadow rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Taille Max</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="equipesTable">
                <?php foreach ($teams as $team): ?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($team['nom']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($team['tailleMax']) ?></td>
                    <td class="border px-4 py-2 text-center">
                        <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="deleteTeam(<?= $team['IdEquipe'] ?>)">Supprimer</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <script src="scripts.js"></script>
</body>
</html>
