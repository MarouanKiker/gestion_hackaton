-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 15 avr. 2025 à 01:44
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hackaton`
--

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'participant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `nom`, `prenom`, `email`, `telephone`, `password`, `role`) VALUES
(24, 'kiker', 'marouan', 'marouankiker@gmail.com', '0766838963', '$2y$10$cge1eqDE9hJIzTLGKJrI4OyRVF0syw4KUPfGv54kowmpIU1EtdhT.', 'admin'),
(28, 'test', 'walid', 'h138@gmail.com', '213344', '$2y$10$xu0MQNFIlriCS0zf31rVF.rgVnR0.5FhqxrDeCSmTJJI9ZAapQAU.', 'admin'),
(31, 'testtt', 'jdkf', 'diwf@fs.com', '23232', '$2y$10$S0UF29P3xbBe0aRzXJeN4uOHE3IFI5VKv2UQGwQh.f26VQHAOpqIW', 'participant'),
(32, 'aaaaa', 'bbbb', 'aa@bb.com', '1111', '$2y$10$.eOffqYeiWwS9f4zfYBei.6AWYHZcOGoHwErcS8q8ktFEy9dx/UWC', 'participant'),
(33, 'kiker', 'marouan', 'marouankiker@gmail.com', '0766838963', '', 'participant'),
(34, 'kiker', 'marouan', 'marouankiker@gmail.com', '0766838963', '', 'participant'),
(35, 'j', 'j', 'js@gmail.com', '111', '', 'participant');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
