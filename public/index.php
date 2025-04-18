<?php
session_start();
// Vérifier si l'utilisateur est connecté
$loggedIn = isset($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hackathon Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- En-tête -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-6 shadow-lg">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold">Système de Gestion de Hackathon</h1>
            <p class="mt-2 text-lg">Simplifiez l'organisation et la gestion de votre hackathon</p>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container mx-auto mt-10 px-4">
        <?php if ($loggedIn): ?>
            <!-- Section de bienvenue et navigation pour utilisateur connecté -->
            <section class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Bienvenue sur la plateforme</h2>
                <p class="mt-4 text-gray-600">Explorez les différentes sections pour gérer les participants, les équipes, les projets, et bien plus encore.</p>
            </section>

            <!-- Cartes de navigation -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Espace Participant -->
                <a href="participant.html" class="block bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 016 16h12a4 4 0 01.879 1.804M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-800">Espace Participant</h3>
                    <p class="mt-2 text-gray-600">Inscrivez-vous et soumettez vos projets.</p>
                </a>

                <!-- Espace Administrateur -->
                <a href="admin.php" class="block bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 9.75h4.5m-2.25 0v6m-6.75 6.75h12a2.25 2.25 0 002.25-2.25V5.25A2.25 2.25 0 0016.5 3H7.5A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-800">Espace Administrateur</h3>
                    <p class="mt-2 text-gray-600">Gérez les utilisateurs, équipes et projets.</p>
                </a>

                <!-- Gestion des Équipes -->
                <a href="team.html" class="block bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4m10 0H7" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-800">Gestion des Équipes</h3>
                    <p class="mt-2 text-gray-600">Créez et gérez vos équipes.</p>
                </a>

                <!-- Gestion des Projets -->
                <a href="project.html" class="block bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l-4-4m0 0l4-4m-4 4h16" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-800">Gestion des Projets</h3>
                    <p class="mt-2 text-gray-600">Soumettez et évaluez les projets.</p>
                </a>

                <!-- Gestion des Ateliers -->
                <a href="workshop.html" class="block bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-xl transition">
                    <div class="text-purple-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zM12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-800">Gestion des Ateliers</h3>
                    <p class="mt-2 text-gray-600">Planifiez et participez aux ateliers.</p>
                </a>
            </section>

            <!-- Bouton de déconnexion -->
            <div class="text-center mt-10">
                <form method="post" action="logout.php">
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Se déconnecter</button>
                </form>
            </div>

        <?php else: ?>
            <!-- Section pour utilisateur non connecté -->
            <section class="max-w-md mx-auto bg-white p-6 rounded shadow">
                <h2 class="text-2xl font-bold mb-4 text-center">Bienvenue</h2>
                <p class="mb-6 text-center text-gray-700">Veuillez vous connecter ou vous inscrire pour accéder à la plateforme.</p>
                <div class="flex justify-center space-x-4">
                    <a href="login.html" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Se connecter</a>
                    <a href="register.html" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">S'inscrire</a>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- Pied de page -->
    <footer class="bg-gray-800 text-white py-4 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 Hackathon Management. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
