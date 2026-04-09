-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 08 avr. 2026 à 09:11
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `iris_examen1`
--

-- --------------------------------------------------------

--
-- Structure de la table `banque_questions`
--

CREATE TABLE `banque_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `enonce` text NOT NULL,
  `points_par_defaut` int(11) NOT NULL DEFAULT 1,
  `niveau_difficulte` enum('facile','moyen','difficile') NOT NULL DEFAULT 'moyen',
  `reponse_correcte` text DEFAULT NULL,
  `points` decimal(5,2) NOT NULL DEFAULT 1.00,
  `explication` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `est_active` tinyint(1) NOT NULL DEFAULT 1,
  `nb_utilisations` int(11) NOT NULL DEFAULT 0,
  `taux_reussite` decimal(5,2) DEFAULT NULL,
  `difficulte` enum('facile','moyen','difficile') NOT NULL DEFAULT 'moyen',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banque_questions`
--

INSERT INTO `banque_questions` (`id`, `enseignant_id`, `matiere_id`, `type`, `enonce`, `points_par_defaut`, `niveau_difficulte`, `reponse_correcte`, `points`, `explication`, `tags`, `est_active`, `nb_utilisations`, `taux_reussite`, `difficulte`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 35, 2, 'texte_libre', 'Qu\'est ce qu\'une base de donnée', 1, 'moyen', NULL, 10.00, NULL, NULL, 1, 0, NULL, 'moyen', '2025-11-13 23:44:25', '2025-11-13 23:44:25', NULL),
(2, 35, 2, 'texte_libre', 'Qu\'est ce qu\'une base de donnée en Python', 1, 'moyen', NULL, 10.00, NULL, NULL, 1, 0, NULL, 'moyen', '2025-11-13 23:44:31', '2025-11-13 23:44:47', NULL),
(3, 35, 4, 'texte_libre', 'Qu\'est ce qu\'un algorithme', 1, 'moyen', NULL, 5.00, NULL, NULL, 1, 0, NULL, 'facile', '2025-11-13 23:45:43', '2025-11-13 23:45:43', NULL),
(5, 35, 4, 'choix_multiple', 'Citez les différentes langages de programmation de logiciel', 1, 'moyen', NULL, 1.00, NULL, NULL, 1, 0, NULL, 'moyen', '2025-11-17 09:01:09', '2025-11-17 09:01:09', NULL),
(6, 35, 4, 'choix_unique', 'Lequel de ces langage permet de créer une base de donnée', 1, 'moyen', NULL, 1.00, NULL, NULL, 1, 0, NULL, 'moyen', '2025-11-17 09:02:52', '2025-11-17 09:02:52', NULL),
(7, 35, 4, 'vrai_faux', 'Python est un langages de programmation algorithmique ?', 1, 'moyen', NULL, 1.00, NULL, NULL, 1, 0, NULL, 'facile', '2025-11-17 09:05:23', '2025-11-17 09:05:23', NULL),
(8, 35, 2, 'reponse_courte', 'Définis moi le sigles BDD', 1, 'moyen', NULL, 1.00, NULL, NULL, 0, 0, NULL, 'moyen', '2025-11-17 09:06:27', '2026-02-12 16:05:21', NULL),
(9, 52, 2, 'choix_unique', 'C\'est quoi une BDD', 1, 'moyen', NULL, 1.00, NULL, NULL, 1, 0, NULL, 'facile', '2026-03-09 19:45:56', '2026-03-09 19:45:56', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `niveau` varchar(255) NOT NULL,
  `annee_scolaire` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `effectif_max` int(11) NOT NULL DEFAULT 30,
  `effectif_actuel` int(11) NOT NULL DEFAULT 0,
  `accepte_alternance` tinyint(1) NOT NULL DEFAULT 1,
  `accepte_initial` tinyint(1) NOT NULL DEFAULT 1,
  `accepte_formation_continue` tinyint(1) NOT NULL DEFAULT 0,
  `nb_etudiants_initial` int(11) NOT NULL DEFAULT 0,
  `nb_etudiants_alternance` int(11) NOT NULL DEFAULT 0,
  `nb_etudiants_formation_continue` int(11) NOT NULL DEFAULT 0,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` enum('active','inactive','archivee') NOT NULL DEFAULT 'active',
  `cree_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `nom`, `code`, `niveau`, `annee_scolaire`, `description`, `effectif_max`, `effectif_actuel`, `accepte_alternance`, `accepte_initial`, `accepte_formation_continue`, `nb_etudiants_initial`, `nb_etudiants_alternance`, `nb_etudiants_formation_continue`, `date_debut`, `date_fin`, `statut`, `cree_par`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BTS SIO SLAM 1', 'BTS-SIO1-2025', 'Bac+2', '2024-2025', 'Classe de formation informatique IRIS', 30, 27, 1, 1, 0, 15, 12, 0, '2024-09-02', '2025-06-30', 'active', 2, '2025-11-13 13:23:29', '2026-02-12 15:42:51', NULL),
(2, 'BTS SIO SLAM 2', 'BTS-SIO2-2025', 'Bac+2', '2024-2025', 'Classe de formation informatique IRIS', 30, 9, 1, 1, 0, 9, 0, 0, '2024-09-02', '2025-06-30', 'active', 2, '2025-11-13 13:23:29', '2026-03-09 19:39:36', NULL),
(3, 'Licence Pro Dev Web', 'LP-DEV-2025', 'Bac+3', '2024-2025', 'Classe de formation informatique IRIS', 25, 25, 1, 1, 0, 16, 9, 0, '2024-09-02', '2025-06-30', 'active', 2, '2025-11-13 13:23:29', '2025-11-17 10:23:12', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `classe_etudiant`
--

CREATE TABLE `classe_etudiant` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `etudiant_id` bigint(20) UNSIGNED NOT NULL,
  `regime` enum('initial','alternance','formation_continue') NOT NULL DEFAULT 'initial',
  `date_inscription` date NOT NULL,
  `date_desinscription` date DEFAULT NULL,
  `entreprise` varchar(255) DEFAULT NULL,
  `adresse_entreprise` text DEFAULT NULL,
  `ville_entreprise` varchar(255) DEFAULT NULL,
  `code_postal_entreprise` varchar(255) DEFAULT NULL,
  `tuteur_entreprise` varchar(255) DEFAULT NULL,
  `poste_tuteur` varchar(255) DEFAULT NULL,
  `email_tuteur` varchar(255) DEFAULT NULL,
  `telephone_tuteur` varchar(255) DEFAULT NULL,
  `rythme_alternance` varchar(255) DEFAULT NULL,
  `statut` enum('inscrit','desinscrit','diplome','abandonne','en_attente') NOT NULL DEFAULT 'inscrit',
  `inscrit_par` bigint(20) UNSIGNED DEFAULT NULL,
  `desinscrit_par` bigint(20) UNSIGNED DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `contrat_alternance` varchar(255) DEFAULT NULL,
  `convention_stage` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `classe_etudiant`
--

INSERT INTO `classe_etudiant` (`id`, `classe_id`, `etudiant_id`, `regime`, `date_inscription`, `date_desinscription`, `entreprise`, `adresse_entreprise`, `ville_entreprise`, `code_postal_entreprise`, `tuteur_entreprise`, `poste_tuteur`, `email_tuteur`, `telephone_tuteur`, `rythme_alternance`, `statut`, `inscrit_par`, `desinscrit_par`, `commentaire`, `contrat_alternance`, `convention_stage`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 'alternance', '2024-09-02', NULL, 'Google', '8 rue de londress', 'paris', '75009', 'Marie Dubois', NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 18:36:15'),
(3, 1, 11, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(4, 1, 12, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(5, 1, 13, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(6, 1, 14, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(7, 1, 15, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(8, 1, 16, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(9, 1, 17, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(10, 1, 18, 'initial', '2024-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(11, 1, 19, 'alternance', '2024-09-02', NULL, 'Orange', NULL, NULL, NULL, 'Michel DURAND', NULL, 'm.durand@orange.fr', '+33 1 39 48 35 82', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(12, 1, 20, 'alternance', '2024-09-02', NULL, 'Capgemini', NULL, NULL, NULL, 'Sophie LAURENT', NULL, 's.laurent@capgemini.com', '+33 1 98 11 52 20', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(13, 1, 21, 'alternance', '2024-09-02', NULL, 'Atos', NULL, NULL, NULL, 'Pierre BLANC', NULL, 'p.blanc@atos.net', '+33 1 97 12 37 75', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(14, 1, 22, 'alternance', '2024-09-02', NULL, 'Sopra Steria', NULL, NULL, NULL, 'Marie PETIT', NULL, 'm.petit@soprasteria.com', '+33 1 40 81 33 36', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(15, 1, 23, 'alternance', '2024-09-02', NULL, 'Thales', NULL, NULL, NULL, 'Jean THOMAS', NULL, 'j.thomas@thalesgroup.com', '+33 1 19 51 19 97', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(16, 1, 24, 'alternance', '2024-09-02', NULL, 'IBM', NULL, NULL, NULL, 'Anne ROBERT', NULL, 'a.robert@ibm.com', '+33 1 61 73 27 11', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(17, 1, 25, 'alternance', '2024-09-02', NULL, 'Accenture', NULL, NULL, NULL, 'Luc RICHARD', NULL, 'l.richard@accenture.com', '+33 1 52 77 88 86', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(18, 1, 26, 'alternance', '2024-09-02', NULL, 'Deloitte', NULL, NULL, NULL, 'Claire DUBOIS', NULL, 'c.dubois@deloitte.fr', '+33 1 52 62 61 20', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(19, 1, 27, 'alternance', '2024-09-02', NULL, 'CGI', NULL, NULL, NULL, 'Paul MARTIN', NULL, 'p.martin@cgi.com', '+33 1 83 36 35 30', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(20, 1, 28, 'alternance', '2024-09-02', NULL, 'Altran', NULL, NULL, NULL, 'Nathalie BERNARD', NULL, 'n.bernard@altran.com', '+33 1 69 94 52 96', '3 semaines / 1 semaine', 'inscrit', 2, NULL, NULL, NULL, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(21, 1, 29, 'initial', '2025-11-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-13 17:52:43', '2025-11-13 17:52:43'),
(22, 1, 30, 'alternance', '2025-11-13', NULL, 'Orange', '1 Avenue du Général de Gaulle', 'paris', '75008', 'Sophie Leroy', 'Responsable SI', 'sophie.leroy@orange.fr', '01 23 45 67 89', '3 semaines / 1 semaine', 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-13 17:56:21', '2025-11-13 17:56:21'),
(23, 1, 32, 'initial', '2025-11-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-13 18:34:13', '2025-11-13 18:34:13'),
(24, 1, 33, 'initial', '2025-11-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-13 18:34:13', '2025-11-13 18:34:13'),
(25, 1, 31, 'initial', '2025-11-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-13 18:34:13', '2025-11-13 18:34:13'),
(26, 3, 27, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(27, 3, 23, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(28, 3, 29, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(29, 3, 19, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(30, 3, 12, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(31, 3, 28, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(32, 3, 32, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(33, 3, 33, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(34, 3, 15, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(35, 3, 9, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(36, 3, 30, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(37, 3, 21, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(38, 3, 20, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:56', '2025-11-17 10:22:56'),
(39, 3, 31, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:57', '2025-11-17 10:22:57'),
(40, 3, 26, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:57', '2025-11-17 10:22:57'),
(41, 3, 17, 'initial', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:22:57', '2025-11-17 10:22:57'),
(42, 3, 24, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(43, 3, 13, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(44, 3, 25, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(45, 3, 14, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(46, 3, 16, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(47, 3, 11, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(48, 3, 22, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(49, 3, 10, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(50, 3, 18, 'alternance', '2025-11-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(51, 1, 36, 'initial', '2025-11-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2025-11-21 07:50:07', '2025-11-21 07:50:07'),
(52, 1, 44, 'initial', '2026-02-09', NULL, 'Orange', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-02-09 09:52:14', '2026-02-12 15:42:51'),
(53, 1, 39, 'initial', '2026-02-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-02-09 09:54:15', '2026-02-09 09:54:15'),
(54, 2, 36, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(55, 2, 39, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(56, 2, 42, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(57, 2, 24, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(58, 2, 13, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(59, 2, 25, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(60, 2, 27, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(61, 2, 22, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(62, 2, 9, 'initial', '2026-03-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'inscrit', 1, NULL, NULL, NULL, NULL, '2026-03-09 19:39:36', '2026-03-09 19:39:36');

-- --------------------------------------------------------

--
-- Structure de la table `copies_etudiants`
--

CREATE TABLE `copies_etudiants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_examen_id` bigint(20) UNSIGNED NOT NULL,
  `etudiant_id` bigint(20) UNSIGNED NOT NULL,
  `examen_id` bigint(20) UNSIGNED NOT NULL,
  `fichier_copie_path` varchar(255) NOT NULL,
  `fichier_copie_nom_original` varchar(255) NOT NULL,
  `fichier_copie_taille` bigint(20) NOT NULL,
  `note_obtenue` decimal(5,2) DEFAULT NULL,
  `commentaire_correcteur` text DEFAULT NULL,
  `date_soumission` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_correction` timestamp NULL DEFAULT NULL,
  `correcteur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('soumis','en_correction','corrige') NOT NULL DEFAULT 'soumis',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `critere_corrections`
--

CREATE TABLE `critere_corrections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `points_max` decimal(5,2) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `date_embauche` date NOT NULL DEFAULT '2025-11-15',
  `statut` enum('actif','conge','suspendu','retraite') NOT NULL DEFAULT 'actif',
  `grade` varchar(255) DEFAULT NULL,
  `specialite` varchar(255) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `nationalite` varchar(255) NOT NULL DEFAULT 'Française',
  `telephone` varchar(255) DEFAULT NULL,
  `telephone_bureau` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `pays` varchar(255) NOT NULL DEFAULT 'France',
  `bureau` varchar(255) DEFAULT NULL,
  `departement` varchar(255) DEFAULT NULL,
  `diplome_plus_eleve` varchar(255) DEFAULT NULL,
  `etablissement_diplome` varchar(255) DEFAULT NULL,
  `annee_diplome` year(4) DEFAULT NULL,
  `biographie` text DEFAULT NULL,
  `domaines_expertise` text DEFAULT NULL,
  `publications` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id`, `utilisateur_id`, `date_embauche`, `statut`, `grade`, `specialite`, `date_naissance`, `sexe`, `lieu_naissance`, `nationalite`, `telephone`, `telephone_bureau`, `adresse`, `ville`, `code_postal`, `pays`, `bureau`, `departement`, `diplome_plus_eleve`, `etablissement_diplome`, `annee_diplome`, `biographie`, `domaines_expertise`, `publications`, `photo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, '2025-11-13', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(2, 5, '2025-11-13', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(3, 6, '2025-11-13', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(4, 7, '2025-11-13', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(5, 8, '2025-11-13', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(6, 35, '2025-11-13', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(7, 51, '2025-11-15', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 10:47:54', '2026-02-09 10:47:54', NULL),
(8, 45, '2025-11-15', 'actif', NULL, 'Développeuse web', NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 11:42:25', '2026-02-09 11:42:25', NULL),
(9, 52, '2025-11-15', 'actif', NULL, NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 19:41:03', '2026-03-09 19:41:03', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `enseignant_classe`
--

CREATE TABLE `enseignant_classe` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `affecte_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enseignant_classe`
--

INSERT INTO `enseignant_classe` (`id`, `enseignant_id`, `classe_id`, `matiere_id`, `affecte_par`, `created_at`, `updated_at`) VALUES
(1, 35, 1, 4, NULL, '2025-11-13 22:00:06', '2025-11-13 22:00:06'),
(2, 35, 1, 2, NULL, '2025-11-13 22:00:34', '2025-11-13 22:00:34'),
(3, 35, 3, 3, NULL, '2025-11-18 15:55:07', '2025-11-18 15:55:07'),
(4, 52, 2, 2, NULL, '2026-03-09 19:41:51', '2026-03-09 19:41:51');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED DEFAULT NULL,
  `matricule` varchar(255) NOT NULL,
  `date_inscription` date NOT NULL DEFAULT '2025-11-15',
  `statut` enum('actif','suspendu','diplome','abandonne') NOT NULL DEFAULT 'actif',
  `regime` enum('initial','alternance','formation_continue') NOT NULL DEFAULT 'initial',
  `entreprise` varchar(255) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `nationalite` varchar(255) NOT NULL DEFAULT 'Française',
  `telephone` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `pays` varchar(255) NOT NULL DEFAULT 'France',
  `contact_urgence_nom` varchar(255) DEFAULT NULL,
  `contact_urgence_telephone` varchar(255) DEFAULT NULL,
  `contact_urgence_relation` varchar(255) DEFAULT NULL,
  `nom_pere` varchar(255) DEFAULT NULL,
  `profession_pere` varchar(255) DEFAULT NULL,
  `telephone_pere` varchar(255) DEFAULT NULL,
  `nom_mere` varchar(255) DEFAULT NULL,
  `profession_mere` varchar(255) DEFAULT NULL,
  `telephone_mere` varchar(255) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `utilisateur_id`, `classe_id`, `matricule`, `date_inscription`, `statut`, `regime`, `entreprise`, `date_naissance`, `sexe`, `lieu_naissance`, `nationalite`, `telephone`, `adresse`, `ville`, `code_postal`, `pays`, `contact_urgence_nom`, `contact_urgence_telephone`, `contact_urgence_relation`, `nom_pere`, `profession_pere`, `telephone_pere`, `nom_mere`, `profession_mere`, `telephone_mere`, `observations`, `photo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 9, 1, 'ETU000009', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(2, 10, 1, 'ETU000010', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(3, 11, 1, 'ETU000011', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(4, 12, 1, 'ETU000012', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(5, 13, 1, 'ETU000013', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(6, 14, 1, 'ETU000014', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(7, 15, 1, 'ETU000015', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(8, 16, 1, 'ETU000016', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(9, 17, 1, 'ETU000017', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(10, 18, 1, 'ETU000018', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(11, 19, 1, 'ETU000019', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(12, 20, 1, 'ETU000020', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(13, 21, 1, 'ETU000021', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(14, 22, 1, 'ETU000022', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(15, 23, 1, 'ETU000023', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(16, 24, 1, 'ETU000024', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(17, 25, 1, 'ETU000025', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(18, 26, 1, 'ETU000026', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(19, 27, 1, 'ETU000027', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(20, 28, 1, 'ETU000028', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(21, 29, 1, 'ETU000029', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(22, 30, 1, 'ETU000030', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(23, 31, 1, 'ETU000031', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(24, 32, 1, 'ETU000032', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(25, 33, 1, 'ETU000033', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(26, 34, 1, 'ETU000034', '2025-11-13', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-15 17:09:59', '2025-11-15 17:09:59', NULL),
(27, 36, 1, 'ETU20250035', '2025-11-21', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 08:00:59', '2025-11-21 08:00:59', NULL),
(28, 37, 1, 'ETU20250036', '2025-11-21', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 08:35:26', '2025-11-21 08:35:26', NULL),
(29, 38, 1, 'ETU20250037', '2025-11-21', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 10:51:54', '2025-11-21 10:51:54', NULL),
(30, 41, 1, 'ETU20250038', '2025-12-17', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 19:02:20', '2025-12-17 19:02:20', NULL),
(31, 42, 1, 'ETU20260001', '2026-02-08', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-08 09:34:50', '2026-02-08 09:34:50', NULL),
(32, 43, 1, 'ETU20260002', '2026-02-08', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-08 09:57:25', '2026-02-08 09:57:25', NULL),
(33, 44, 1, 'ETU20260003', '2026-02-09', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 08:54:03', '2026-02-12 15:45:18', NULL),
(34, 53, 2, 'ETU20260004', '2026-03-09', 'actif', 'initial', NULL, NULL, NULL, NULL, 'Française', NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-09 19:48:54', '2026-03-09 19:48:54', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `examens`
--

CREATE TABLE `examens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `enseignant_id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `classe_id` bigint(20) UNSIGNED NOT NULL,
  `type_examen` enum('en_ligne','document') NOT NULL DEFAULT 'en_ligne',
  `fichier_sujet_path` varchar(255) DEFAULT NULL,
  `duree_minutes` int(11) NOT NULL,
  `ordre_questions_aleatoire` tinyint(1) NOT NULL DEFAULT 0,
  `ordre_reponses_aleatoire` tinyint(1) NOT NULL DEFAULT 0,
  `note_totale` decimal(5,2) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `melanger_questions` tinyint(1) NOT NULL DEFAULT 0,
  `melanger_reponses` tinyint(1) NOT NULL DEFAULT 0,
  `afficher_resultats_immediatement` tinyint(1) NOT NULL DEFAULT 0,
  `nombre_tentatives_max` int(11) NOT NULL DEFAULT 1,
  `autoriser_retour_arriere` tinyint(1) NOT NULL DEFAULT 1,
  `seuil_reussite` int(11) NOT NULL DEFAULT 50,
  `statut` enum('brouillon','publie','en_cours','termine','archive') NOT NULL DEFAULT 'brouillon',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `examens`
--

INSERT INTO `examens` (`id`, `titre`, `description`, `instructions`, `enseignant_id`, `matiere_id`, `classe_id`, `type_examen`, `fichier_sujet_path`, `duree_minutes`, `ordre_questions_aleatoire`, `ordre_reponses_aleatoire`, `note_totale`, `date_debut`, `date_fin`, `melanger_questions`, `melanger_reponses`, `afficher_resultats_immediatement`, `nombre_tentatives_max`, `autoriser_retour_arriere`, `seuil_reussite`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Base de Donnée', 'Décrivez moi ce qu\'ai une base de donnée et a quoi elle sert', NULL, 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-11-15 20:52:37', '2025-11-15 23:02:37', 0, 1, 1, 1, 1, 85, 'publie', '2025-11-14 11:11:42', '2025-11-15 18:17:22'),
(2, 'Base de Donnée', 'Travaillez', NULL, 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-11-15 20:52:37', '2025-11-15 23:02:37', 0, 0, 0, 1, 1, 85, 'publie', '2025-11-14 12:01:38', '2025-11-15 18:10:25'),
(3, 'Base de Donnée', 'Examen de test pour valider le système', NULL, 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-11-15 20:52:37', '2025-11-15 23:02:37', 0, 0, 1, 1, 1, 50, 'publie', '2025-11-15 18:02:15', '2025-11-15 18:09:20'),
(4, 'Algorithme', 'c\'est un examens donc donner tout vos possible', NULL, 35, 4, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-11-15 20:52:37', '2025-11-15 23:02:37', 0, 0, 1, 1, 1, 50, 'publie', '2025-11-15 18:33:58', '2025-11-15 18:36:33'),
(8, 'Algorithme', 'Prenez votre temps pour traitez cet examen', NULL, 35, 4, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-11-17 09:58:00', '2025-11-18 10:00:00', 0, 0, 1, 1, 1, 50, 'publie', '2025-11-17 07:58:45', '2025-11-17 07:59:35'),
(10, 'Algorithme', NULL, NULL, 35, 4, 1, 'en_ligne', NULL, 25, 0, 0, 20.00, '2025-11-17 14:00:00', '2025-11-17 16:01:00', 0, 0, 1, 1, 1, 50, 'publie', '2025-11-17 12:53:51', '2025-11-17 13:47:13'),
(11, 'Base de donné', NULL, 'Répondez au questions respectives', 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-12-16 08:49:00', '2025-12-16 09:49:00', 0, 0, 0, 1, 0, 80, 'publie', '2025-12-16 07:51:00', '2025-12-16 07:51:00'),
(12, 'Baser de donnée', NULL, 'Répondez', 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-12-16 14:51:00', '2025-12-16 15:51:00', 0, 0, 0, 2, 0, 50, 'publie', '2025-12-16 13:52:30', '2025-12-16 13:52:30'),
(13, 'BDD', NULL, NULL, 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2025-12-16 15:13:00', '2025-12-16 16:13:00', 0, 0, 0, 2, 0, 50, 'publie', '2025-12-16 14:13:47', '2025-12-16 14:13:47'),
(14, 'BAse de donné', NULL, NULL, 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2026-01-12 09:47:00', '2026-01-12 12:47:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-01-12 08:49:59', '2026-01-12 08:49:59'),
(15, 'Algo', NULL, NULL, 35, 4, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2026-01-14 09:58:00', '2026-01-21 09:58:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-01-12 09:00:07', '2026-01-12 09:00:07'),
(16, 'BDD', NULL, NULL, 35, 3, 1, 'en_ligne', NULL, 20, 0, 0, 20.00, '2026-02-09 12:51:00', '2026-02-09 13:40:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-02-09 11:52:12', '2026-02-09 11:52:12'),
(17, 'ddd', NULL, NULL, 35, 2, 1, 'en_ligne', NULL, 5, 0, 0, 20.00, '2026-02-09 12:56:00', '2026-02-09 13:05:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-02-09 11:58:18', '2026-02-09 11:58:18'),
(18, 'Developpeur', NULL, NULL, 35, 3, 1, 'en_ligne', NULL, 10, 0, 0, 20.00, '2026-02-10 22:39:00', '2026-02-10 22:49:00', 0, 1, 0, 1, 0, 50, 'publie', '2026-02-10 21:43:25', '2026-02-10 21:43:25'),
(19, 'ALGO', NULL, NULL, 35, 4, 1, 'en_ligne', NULL, 8, 0, 0, 20.00, '2026-02-10 23:02:00', '2026-02-10 23:10:00', 0, 1, 1, 1, 0, 50, 'publie', '2026-02-10 22:03:02', '2026-02-10 22:03:02'),
(20, 'Developpeur', NULL, NULL, 35, 3, 1, 'en_ligne', NULL, 10, 0, 0, 20.00, '2026-02-11 08:54:00', '2026-02-11 09:04:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-02-11 07:55:22', '2026-02-11 07:55:22'),
(21, 'DEV', NULL, NULL, 35, 2, 1, 'en_ligne', NULL, 10, 0, 0, 20.00, '2026-02-12 16:48:00', '2026-02-12 16:58:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-02-12 15:48:39', '2026-02-12 15:48:39'),
(22, 'BDD', NULL, NULL, 35, 2, 1, 'en_ligne', NULL, 60, 0, 0, 20.00, '2026-03-09 13:03:00', '2026-03-09 14:03:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-03-09 12:02:23', '2026-03-09 12:02:23'),
(23, 'BDD', NULL, NULL, 52, 2, 2, 'en_ligne', NULL, 5, 0, 0, 10.00, '2026-03-09 20:46:00', '2026-03-09 20:51:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-03-09 19:46:46', '2026-03-09 19:46:46'),
(24, 'DEV', NULL, NULL, 35, 3, 1, 'en_ligne', NULL, 7, 0, 0, 20.00, '2026-03-21 16:24:00', '2026-03-21 16:31:00', 0, 0, 0, 1, 0, 50, 'publie', '2026-03-21 15:25:21', '2026-03-21 15:25:21');

-- --------------------------------------------------------

--
-- Structure de la table `examen_question`
--

CREATE TABLE `examen_question` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `examen_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT 0,
  `points` decimal(5,2) NOT NULL,
  `obligatoire` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `examen_question`
--

INSERT INTO `examen_question` (`id`, `examen_id`, `question_id`, `ordre`, `points`, `obligatoire`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 1, 10.00, 1, '2025-11-15 18:08:34', '2025-11-15 18:08:34'),
(2, 3, 1, 2, 10.00, 1, '2025-11-15 18:08:34', '2025-11-15 18:08:34'),
(3, 2, 2, 1, 15.00, 1, '2025-11-15 18:10:10', '2025-11-15 18:10:10'),
(4, 2, 1, 2, 5.00, 1, '2025-11-15 18:10:10', '2025-11-15 18:10:10'),
(5, 1, 2, 1, 5.00, 1, '2025-11-15 18:16:06', '2025-11-15 18:16:06'),
(6, 1, 1, 2, 15.00, 1, '2025-11-15 18:16:06', '2025-11-15 18:16:06'),
(7, 4, 3, 1, 5.00, 1, '2025-11-15 18:36:16', '2025-11-15 18:36:16'),
(8, 8, 3, 1, 20.00, 1, '2025-11-17 07:59:13', '2025-11-17 07:59:13'),
(10, 10, 7, 1, 10.00, 1, '2025-11-17 13:46:57', '2025-11-17 13:46:57'),
(11, 10, 6, 2, 10.00, 1, '2025-11-17 13:46:57', '2025-11-17 13:46:57'),
(12, 11, 8, 1, 5.00, 1, '2025-12-16 07:51:00', '2025-12-16 07:51:00'),
(13, 11, 7, 2, 5.00, 1, '2025-12-16 07:51:00', '2025-12-16 07:51:00'),
(14, 11, 6, 3, 5.00, 1, '2025-12-16 07:51:00', '2025-12-16 07:51:00'),
(15, 11, 5, 4, 5.00, 1, '2025-12-16 07:51:00', '2025-12-16 07:51:00'),
(16, 12, 8, 1, 5.00, 1, '2025-12-16 13:52:30', '2025-12-16 13:52:30'),
(17, 12, 7, 2, 5.00, 1, '2025-12-16 13:52:30', '2025-12-16 13:52:30'),
(18, 12, 6, 3, 5.00, 1, '2025-12-16 13:52:30', '2025-12-16 13:52:30'),
(19, 12, 5, 4, 5.00, 1, '2025-12-16 13:52:30', '2025-12-16 13:52:30'),
(20, 13, 8, 1, 5.00, 1, '2025-12-16 14:13:47', '2025-12-16 14:13:47'),
(21, 13, 7, 2, 5.00, 1, '2025-12-16 14:13:47', '2025-12-16 14:13:47'),
(22, 13, 6, 3, 10.00, 1, '2025-12-16 14:13:47', '2025-12-16 14:13:47'),
(23, 14, 8, 1, 5.00, 1, '2026-01-12 08:49:59', '2026-01-12 08:49:59'),
(24, 14, 7, 2, 5.00, 1, '2026-01-12 08:49:59', '2026-01-12 08:49:59'),
(25, 14, 6, 3, 5.00, 1, '2026-01-12 08:49:59', '2026-01-12 08:49:59'),
(26, 14, 3, 4, 5.00, 1, '2026-01-12 08:49:59', '2026-01-12 08:49:59'),
(27, 15, 8, 1, 5.00, 1, '2026-01-12 09:00:07', '2026-01-12 09:00:07'),
(28, 15, 7, 2, 5.00, 1, '2026-01-12 09:00:07', '2026-01-12 09:00:07'),
(29, 15, 6, 3, 5.00, 1, '2026-01-12 09:00:07', '2026-01-12 09:00:07'),
(30, 15, 5, 4, 5.00, 1, '2026-01-12 09:00:07', '2026-01-12 09:00:07'),
(31, 16, 7, 1, 8.00, 1, '2026-02-09 11:52:12', '2026-02-09 11:52:12'),
(32, 16, 6, 2, 7.00, 1, '2026-02-09 11:52:12', '2026-02-09 11:52:12'),
(33, 16, 5, 3, 5.00, 1, '2026-02-09 11:52:12', '2026-02-09 11:52:12'),
(34, 17, 8, 1, 5.00, 1, '2026-02-09 11:58:18', '2026-02-09 11:58:18'),
(35, 17, 7, 2, 5.00, 1, '2026-02-09 11:58:18', '2026-02-09 11:58:18'),
(36, 17, 6, 3, 5.00, 1, '2026-02-09 11:58:18', '2026-02-09 11:58:18'),
(37, 17, 5, 4, 5.00, 1, '2026-02-09 11:58:18', '2026-02-09 11:58:18'),
(38, 18, 8, 1, 5.00, 1, '2026-02-10 21:43:25', '2026-02-10 21:43:25'),
(39, 18, 7, 2, 5.00, 1, '2026-02-10 21:43:25', '2026-02-10 21:43:25'),
(40, 18, 6, 3, 5.00, 1, '2026-02-10 21:43:25', '2026-02-10 21:43:25'),
(41, 18, 5, 4, 5.00, 1, '2026-02-10 21:43:25', '2026-02-10 21:43:25'),
(42, 19, 8, 1, 5.00, 1, '2026-02-10 22:03:02', '2026-02-10 22:03:02'),
(43, 19, 7, 2, 5.00, 1, '2026-02-10 22:03:02', '2026-02-10 22:03:02'),
(44, 19, 6, 3, 5.00, 1, '2026-02-10 22:03:02', '2026-02-10 22:03:02'),
(45, 19, 5, 4, 5.00, 1, '2026-02-10 22:03:02', '2026-02-10 22:03:02'),
(46, 20, 7, 1, 10.00, 1, '2026-02-11 07:55:22', '2026-02-11 07:55:22'),
(47, 20, 6, 2, 5.00, 1, '2026-02-11 07:55:22', '2026-02-11 07:55:22'),
(48, 20, 5, 3, 5.00, 1, '2026-02-11 07:55:22', '2026-02-11 07:55:22'),
(49, 21, 8, 1, 5.00, 1, '2026-02-12 15:48:39', '2026-02-12 15:48:39'),
(50, 21, 7, 2, 5.00, 1, '2026-02-12 15:48:39', '2026-02-12 15:48:39'),
(51, 21, 6, 3, 5.00, 1, '2026-02-12 15:48:39', '2026-02-12 15:48:39'),
(52, 21, 5, 4, 5.00, 1, '2026-02-12 15:48:39', '2026-02-12 15:48:39'),
(53, 22, 7, 1, 5.00, 1, '2026-03-09 12:02:23', '2026-03-09 12:02:23'),
(54, 22, 6, 2, 5.00, 1, '2026-03-09 12:02:23', '2026-03-09 12:02:23'),
(55, 22, 5, 3, 5.00, 1, '2026-03-09 12:02:23', '2026-03-09 12:02:23'),
(56, 22, 3, 4, 5.00, 1, '2026-03-09 12:02:23', '2026-03-09 12:02:23'),
(57, 23, 9, 1, 10.00, 1, '2026-03-09 19:46:46', '2026-03-09 19:46:46'),
(58, 24, 7, 1, 5.00, 1, '2026-03-21 15:25:21', '2026-03-21 15:25:21'),
(59, 24, 6, 2, 5.00, 1, '2026-03-21 15:25:21', '2026-03-21 15:25:21'),
(60, 24, 5, 3, 5.00, 1, '2026-03-21 15:25:21', '2026-03-21 15:25:21'),
(61, 24, 1, 4, 5.00, 1, '2026-03-21 15:25:21', '2026-03-21 15:25:21');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs_activite`
--

CREATE TABLE `logs_activite` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `logs_activite`
--

INSERT INTO `logs_activite` (`id`, `utilisateur_id`, `action`, `module`, `description`, `details`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 16:28:35', '2025-11-13 16:28:35'),
(2, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Jean DUPONT (ID: 29)', '{\"utilisateur_id\":29}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 17:49:00', '2025-11-13 17:49:00'),
(3, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Marie MARTIN (ID: 30)', '{\"utilisateur_id\":30}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 17:50:53', '2025-11-13 17:50:53'),
(4, 1, 'affectation_etudiant', 'classes', 'Affectation de Jean DUPONT à la classe BTS SIO SLAM 1 (initial)', '{\"classe_id\":1,\"etudiant_id\":29,\"regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 17:52:43', '2025-11-13 17:52:43'),
(5, 1, 'affectation_etudiant', 'classes', 'Affectation de Marie MARTIN à la classe BTS SIO SLAM 1 (alternance)', '{\"classe_id\":1,\"etudiant_id\":30,\"regime\":\"alternance\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 17:56:21', '2025-11-13 17:56:21'),
(6, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur John PIERRE (ID: 31)', '{\"utilisateur_id\":31}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 17:58:23', '2025-11-13 17:58:23'),
(7, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Ikbal JACQUES (ID: 32)', '{\"utilisateur_id\":32}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 17:59:51', '2025-11-13 17:59:51'),
(8, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Bernard JOSEPH (ID: 33)', '{\"utilisateur_id\":33}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 18:01:04', '2025-11-13 18:01:04'),
(9, 1, 'affectation_masse', 'classes', 'Affectation de 3 étudiants à la classe BTS SIO SLAM 1', '{\"classe_id\":1,\"nb_etudiants\":3,\"regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 18:34:13', '2025-11-13 18:34:13'),
(10, 1, 'modification_affectation', 'classes', 'Modification de l\'affectation de Lucas LEFEBVRE dans la classe BTS SIO SLAM 1', '{\"classe_id\":1,\"etudiant_id\":9,\"ancien_regime\":\"initial\",\"nouveau_regime\":\"alternance\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 18:36:15', '2025-11-13 18:36:15'),
(11, 1, 'retrait_etudiant', 'classes', 'Retrait de Emma ROUX de la classe BTS SIO SLAM 1', '{\"classe_id\":1,\"etudiant_id\":10}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 18:36:51', '2025-11-13 18:36:51'),
(12, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Mariette MARTINE (ID: 34)', '{\"utilisateur_id\":34}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 18:59:03', '2025-11-13 18:59:03'),
(13, 1, 'suppression_utilisateur', 'utilisateurs', 'Suppression de l\'utilisateur Mariette MARTINE (ID: 34)', '{\"utilisateur_id\":34}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 19:01:18', '2025-11-13 19:01:18'),
(14, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Islaah BADIROU (ID: 35)', '{\"utilisateur_id\":35}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 20:13:38', '2025-11-13 20:13:38'),
(15, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 20:14:13', '2025-11-13 20:14:13'),
(16, 1, 'affectation_enseignant_classe', 'enseignant_classe', 'Affectation de Islaah BADIROU à la classe BTS SIO SLAM 1 pour la matière Algorithmique', '{\"enseignant_id\":35,\"classe_id\":1,\"matiere_id\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:00:06', '2025-11-13 22:00:06'),
(17, 1, 'affectation_enseignant_classe', 'enseignant_classe', 'Affectation de Islaah BADIROU à la classe BTS SIO SLAM 1 pour la matière Base de Données', '{\"enseignant_id\":35,\"classe_id\":1,\"matiere_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:00:34', '2025-11-13 22:00:34'),
(18, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:01:18', '2025-11-13 22:01:18'),
(19, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:01:23', '2025-11-13 22:01:23'),
(20, 35, 'creation_question', 'questions', 'Création de la question : Qu\'est ce qu\'une base de données', '{\"question_id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:14:18', '2025-11-13 22:14:18'),
(21, 35, 'creation_question', 'questions', 'Création de la question : Qu\'est ce qu\'un algorithme', '{\"question_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:33:51', '2025-11-13 22:33:51'),
(22, 35, 'modification_question', 'questions', 'Modification de la question : Qu\'est ce qu\'un algorithme en Python ?', '{\"question_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:38:55', '2025-11-13 22:38:55'),
(23, 35, 'duplication_question', 'questions', 'Duplication de la question : Qu\'est ce qu\'une base de données', '{\"question_id\":1,\"nouvelle_question_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:39:12', '2025-11-13 22:39:12'),
(24, 35, 'modification_question', 'questions', 'Modification de la question : Qu\'est ce qu\'une base de données (Copie)', '{\"question_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:39:16', '2025-11-13 22:39:16'),
(25, 35, 'suppression_question', 'questions', 'Suppression de la question : Qu\'est ce qu\'une base de données', '{\"question_id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 22:39:28', '2025-11-13 22:39:28'),
(26, 35, 'creation_question', 'banque_questions', 'Création de la question : Qu\'est ce qu\'une base de donnée', '{\"question_id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 23:44:25', '2025-11-13 23:44:25'),
(27, 35, 'duplication_question', 'banque_questions', 'Duplication de la question : Qu\'est ce qu\'une base de donnée', '{\"question_id\":1,\"nouvelle_question_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 23:44:31', '2025-11-13 23:44:31'),
(28, 35, 'modification_question', 'banque_questions', 'Modification de la question : Qu\'est ce qu\'une base de donnée en Python', '{\"question_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 23:44:47', '2025-11-13 23:44:47'),
(29, 35, 'creation_question', 'banque_questions', 'Création de la question : Qu\'est ce qu\'un algorithme', '{\"question_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-13 23:45:43', '2025-11-13 23:45:43'),
(30, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 10:26:56', '2025-11-14 10:26:56'),
(31, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Base de Donnée\' (ID: 1)', '{\"examen_id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 11:11:42', '2025-11-14 11:11:42'),
(32, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Base de Donnée\' (ID: 2)', '{\"examen_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 12:01:38', '2025-11-14 12:01:38'),
(33, 35, 'modification_examen', 'examens', 'Modification de l\'examen \'Base de Donnée\' (ID: 2)', '{\"examen_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 12:01:55', '2025-11-14 12:01:55'),
(34, 29, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:18:04', '2025-11-14 15:18:04'),
(35, 29, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:41:21', '2025-11-14 15:41:21'),
(36, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:41:41', '2025-11-14 15:41:41'),
(37, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:42:18', '2025-11-14 15:42:18'),
(38, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:42:26', '2025-11-14 15:42:26'),
(39, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:43:30', '2025-11-14 15:43:30'),
(40, 29, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 15:43:42', '2025-11-14 15:43:42'),
(41, 29, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 16:44:13', '2025-11-14 16:44:13'),
(42, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-14 16:45:25', '2025-11-14 16:45:25'),
(43, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:57:46', '2025-11-15 17:57:46'),
(44, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:58:12', '2025-11-15 17:58:12'),
(45, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:58:19', '2025-11-15 17:58:19'),
(46, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:58:40', '2025-11-15 17:58:40'),
(47, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:58:49', '2025-11-15 17:58:49'),
(48, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:59:43', '2025-11-15 17:59:43'),
(49, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 17:59:49', '2025-11-15 17:59:49'),
(50, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Base de Donnée\' (ID: 3)', '{\"examen_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:02:15', '2025-11-15 18:02:15'),
(51, 35, 'publication_examen', 'examens', 'Publication de l\'examen \'Base de Donnée\' (ID: 3)', '{\"examen_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:09:20', '2025-11-15 18:09:20'),
(52, 35, 'publication_examen', 'examens', 'Publication de l\'examen \'Base de Donnée\' (ID: 2)', '{\"examen_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:10:25', '2025-11-15 18:10:25'),
(53, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:12:41', '2025-11-15 18:12:41'),
(54, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:12:46', '2025-11-15 18:12:46'),
(55, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:15:26', '2025-11-15 18:15:26'),
(56, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:15:32', '2025-11-15 18:15:32'),
(57, 35, 'modification_examen', 'examens', 'Modification de l\'examen \'Base de Donnée\' (ID: 1)', '{\"examen_id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:17:14', '2025-11-15 18:17:14'),
(58, 35, 'publication_examen', 'examens', 'Publication de l\'examen \'Base de Donnée\' (ID: 1)', '{\"examen_id\":1}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:17:22', '2025-11-15 18:17:22'),
(59, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:17:41', '2025-11-15 18:17:41'),
(60, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:17:46', '2025-11-15 18:17:46'),
(61, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:32:27', '2025-11-15 18:32:27'),
(62, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:32:32', '2025-11-15 18:32:32'),
(63, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Algorithme\' (ID: 4)', '{\"examen_id\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:33:58', '2025-11-15 18:33:58'),
(64, 35, 'publication_examen', 'examens', 'Publication de l\'examen \'Algorithme\' (ID: 4)', '{\"examen_id\":4}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:36:33', '2025-11-15 18:36:33'),
(65, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:36:44', '2025-11-15 18:36:44'),
(66, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-15 18:36:53', '2025-11-15 18:36:53'),
(67, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-16 08:03:36', '2025-11-16 08:03:36'),
(68, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-16 13:43:26', '2025-11-16 13:43:26'),
(69, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-16 19:40:34', '2025-11-16 19:40:34'),
(70, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-16 19:40:44', '2025-11-16 19:40:44'),
(71, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-16 19:58:33', '2025-11-16 19:58:33'),
(72, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-16 19:58:41', '2025-11-16 19:58:41'),
(73, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 06:54:24', '2025-11-17 06:54:24'),
(74, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Algorithme\'', NULL, NULL, NULL, '2025-11-17 07:58:45', '2025-11-17 07:58:45'),
(75, 35, 'publication_examen', 'examens', 'Publication de l\'examen \'Algorithme\'', NULL, NULL, NULL, '2025-11-17 07:59:35', '2025-11-17 07:59:35'),
(76, 35, 'creation_question', 'questions', 'Création de la question \'Citez les différentes langages de programmation de logiciel\'', NULL, NULL, NULL, '2025-11-17 09:01:09', '2025-11-17 09:01:09'),
(77, 35, 'creation_question', 'questions', 'Création de la question \'Lequel de ces langage permet de créer une base de donnée\'', NULL, NULL, NULL, '2025-11-17 09:02:52', '2025-11-17 09:02:52'),
(78, 35, 'creation_question', 'questions', 'Création de la question \'Python est un langages de programmation algorithmique ?\'', NULL, NULL, NULL, '2025-11-17 09:05:23', '2025-11-17 09:05:23'),
(79, 35, 'creation_question', 'questions', 'Création de la question \'Définis moi le sigles BDD\'', NULL, NULL, NULL, '2025-11-17 09:06:27', '2025-11-17 09:06:27'),
(80, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 09:13:50', '2025-11-17 09:13:50'),
(81, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 09:13:59', '2025-11-17 09:13:59'),
(82, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 10:21:13', '2025-11-17 10:21:13'),
(83, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 10:21:21', '2025-11-17 10:21:21'),
(84, 1, 'affectation_masse', 'classes', 'Affectation de 16 étudiants à la classe Licence Pro Dev Web', '{\"classe_id\":3,\"nb_etudiants\":16,\"regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 10:22:57', '2025-11-17 10:22:57'),
(85, 1, 'affectation_masse', 'classes', 'Affectation de 9 étudiants à la classe Licence Pro Dev Web', '{\"classe_id\":3,\"nb_etudiants\":9,\"regime\":\"alternance\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 10:23:12', '2025-11-17 10:23:12'),
(86, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 10:24:55', '2025-11-17 10:24:55'),
(87, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 10:25:00', '2025-11-17 10:25:00'),
(88, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 11:27:17', '2025-11-17 11:27:17'),
(89, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 11:27:27', '2025-11-17 11:27:27'),
(90, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 12:50:09', '2025-11-17 12:50:09'),
(91, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 12:50:16', '2025-11-17 12:50:16'),
(92, 35, 'duplication_examen', 'examens', 'Duplication de l\'examen \'Algorithme\' vers \'Algorithme (Copie)\'', NULL, NULL, NULL, '2025-11-17 12:51:04', '2025-11-17 12:51:04'),
(93, 35, 'suppression_examen', 'examens', 'Suppression de l\'examen \'Algorithme (Copie)\'', NULL, NULL, NULL, '2025-11-17 12:52:51', '2025-11-17 12:52:51'),
(94, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Algorithme\'', NULL, NULL, NULL, '2025-11-17 12:53:51', '2025-11-17 12:53:51'),
(95, 35, 'publication_examen', 'examens', 'Publication de l\'examen \'Algorithme\'', NULL, NULL, NULL, '2025-11-17 13:47:13', '2025-11-17 13:47:13'),
(96, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 13:47:26', '2025-11-17 13:47:26'),
(97, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 13:47:32', '2025-11-17 13:47:32'),
(98, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 13:56:05', '2025-11-17 13:56:05'),
(99, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 13:56:11', '2025-11-17 13:56:11'),
(100, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 13:57:04', '2025-11-17 13:57:04'),
(101, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-17 13:57:09', '2025-11-17 13:57:09'),
(102, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 13:17:23', '2025-11-18 13:17:23'),
(103, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 13:20:54', '2025-11-18 13:20:54'),
(104, 1, 'affectation_enseignant_classe', 'enseignant_classe', 'Affectation de Islaah BADIROU à la classe Licence Pro Dev Web pour la matière Développement Web', '{\"enseignant_id\":35,\"classe_id\":3,\"matiere_id\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 15:55:07', '2025-11-18 15:55:07'),
(105, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 15:56:16', '2025-11-18 15:56:16'),
(106, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 15:56:22', '2025-11-18 15:56:22'),
(107, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 16:29:23', '2025-11-18 16:29:23'),
(108, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 16:29:36', '2025-11-18 16:29:36'),
(109, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 16:32:24', '2025-11-18 16:32:24'),
(110, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-18 16:32:29', '2025-11-18 16:32:29'),
(111, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-19 19:22:10', '2025-11-19 19:22:10'),
(112, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-19 19:33:57', '2025-11-19 19:33:57'),
(113, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-19 20:21:51', '2025-11-19 20:21:51'),
(114, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 11:02:59', '2025-11-20 11:02:59'),
(115, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 12:23:28', '2025-11-20 12:23:28'),
(116, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 12:23:33', '2025-11-20 12:23:33'),
(117, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:47:23', '2025-11-20 14:47:23'),
(118, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:47:32', '2025-11-20 14:47:32'),
(119, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Fadil ASSANE (ID: 36)', '{\"utilisateur_id\":36}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:49:52', '2025-11-20 14:49:52'),
(120, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:56:54', '2025-11-20 14:56:54'),
(121, 36, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-20 14:57:03', '2025-11-20 14:57:03'),
(122, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 07:23:29', '2025-11-21 07:23:29'),
(123, 1, 'affectation_etudiant', 'classes', 'Affectation de Fadil ASSANE à la classe BTS SIO SLAM 1 (initial)', '{\"classe_id\":1,\"etudiant_id\":36,\"regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 07:50:07', '2025-11-21 07:50:07'),
(124, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 07:50:34', '2025-11-21 07:50:34'),
(125, 36, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 07:50:41', '2025-11-21 07:50:41'),
(126, 36, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 08:33:11', '2025-11-21 08:33:11'),
(127, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 08:33:19', '2025-11-21 08:33:19'),
(128, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Billy RICARDO (ID: 37)', '{\"utilisateur_id\":37}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 08:35:26', '2025-11-21 08:35:26'),
(129, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 10:49:40', '2025-11-21 10:49:40'),
(130, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Mellisa DUPONT (ID: 38)', '{\"utilisateur_id\":38}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 10:51:54', '2025-11-21 10:51:54'),
(131, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 11:46:16', '2025-11-21 11:46:16'),
(132, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-21 11:46:22', '2025-11-21 11:46:22'),
(133, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 13:03:32', '2025-11-22 13:03:32'),
(134, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:16:58', '2025-11-23 15:16:58'),
(135, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:54:39', '2025-11-23 15:54:39'),
(136, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:54:53', '2025-11-23 15:54:53'),
(137, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 09:29:23', '2025-11-25 09:29:23'),
(138, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-26 11:24:45', '2025-11-26 11:24:45'),
(139, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 12:17:05', '2025-12-01 12:17:05'),
(140, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 17:13:52', '2025-12-01 17:13:52'),
(141, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 17:49:21', '2025-12-01 17:49:21'),
(142, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 17:49:29', '2025-12-01 17:49:29'),
(143, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 14:13:20', '2025-12-02 14:13:20'),
(144, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 16:34:28', '2025-12-02 16:34:28'),
(145, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 16:49:24', '2025-12-02 16:49:24'),
(146, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 18:51:08', '2025-12-15 18:51:08'),
(147, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 19:08:40', '2025-12-15 19:08:40'),
(148, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 19:10:02', '2025-12-15 19:10:02'),
(149, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 19:10:11', '2025-12-15 19:10:11'),
(150, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 19:10:44', '2025-12-15 19:10:44'),
(151, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 19:36:32', '2025-12-15 19:36:32'),
(152, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-15 19:39:03', '2025-12-15 19:39:03'),
(153, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 07:41:27', '2025-12-16 07:41:27'),
(154, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Base de donné\'', NULL, NULL, NULL, '2025-12-16 07:51:00', '2025-12-16 07:51:00'),
(155, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 07:51:14', '2025-12-16 07:51:14'),
(156, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 07:51:23', '2025-12-16 07:51:23'),
(157, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 07:52:46', '2025-12-16 07:52:46'),
(158, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 07:52:58', '2025-12-16 07:52:58'),
(159, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 12:45:32', '2025-12-16 12:45:32'),
(160, 35, 'correction_publiee', 'corrections', 'Correction de \'Algorithme\' pour   - Note: 20/20.00', NULL, NULL, NULL, '2025-12-16 13:15:31', '2025-12-16 13:15:31'),
(161, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'Base de donné\' pour   - Note: 15/20.00', NULL, NULL, NULL, '2025-12-16 13:35:49', '2025-12-16 13:35:49'),
(162, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'Base de donné\' pour   - Note: 15/20.00', NULL, NULL, NULL, '2025-12-16 13:51:04', '2025-12-16 13:51:04'),
(163, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Baser de donnée\'', NULL, NULL, NULL, '2025-12-16 13:52:30', '2025-12-16 13:52:30'),
(164, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 13:52:43', '2025-12-16 13:52:43'),
(165, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 13:52:52', '2025-12-16 13:52:52'),
(166, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 13:54:08', '2025-12-16 13:54:08'),
(167, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 13:54:14', '2025-12-16 13:54:14'),
(168, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'Baser de donnée\' pour   - Note: 15/20.00', NULL, NULL, NULL, '2025-12-16 14:12:31', '2025-12-16 14:12:31'),
(169, 35, 'creation_examen', 'examens', 'Création de l\'examen \'BDD\'', NULL, NULL, NULL, '2025-12-16 14:13:47', '2025-12-16 14:13:47'),
(170, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 14:13:53', '2025-12-16 14:13:53'),
(171, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 14:14:00', '2025-12-16 14:14:00'),
(172, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 14:15:01', '2025-12-16 14:15:01'),
(173, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-16 14:15:12', '2025-12-16 14:15:12'),
(174, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 07:02:05', '2025-12-17 07:02:05'),
(175, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'Baser de donnée\' pour   - Note: 18/20.00', NULL, NULL, NULL, '2025-12-17 08:44:41', '2025-12-17 08:44:41'),
(176, 35, 'correction_publiee', 'corrections', 'Correction de \'Baser de donnée\' pour   - Note: 18/20.00', NULL, NULL, NULL, '2025-12-17 08:45:08', '2025-12-17 08:45:08'),
(177, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 08:46:45', '2025-12-17 08:46:45'),
(178, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 08:46:54', '2025-12-17 08:46:54'),
(179, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Fadil ASSANE (ID: 39)', '{\"utilisateur_id\":39}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 08:50:44', '2025-12-17 08:50:44'),
(180, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 10:43:56', '2025-12-17 10:43:56'),
(181, 39, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 10:44:51', '2025-12-17 10:44:51'),
(182, 39, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 10:48:11', '2025-12-17 10:48:11'),
(183, 39, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 17:43:26', '2025-12-17 17:43:26'),
(184, 40, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 18:27:48', '2025-12-17 18:27:48'),
(185, 40, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 18:55:24', '2025-12-17 18:55:24'),
(186, 40, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 18:55:38', '2025-12-17 18:55:38'),
(187, 40, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 18:58:41', '2025-12-17 18:58:41'),
(188, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 19:00:35', '2025-12-17 19:00:35'),
(189, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur John JACK (ID: 41)', '{\"utilisateur_id\":41}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 19:02:20', '2025-12-17 19:02:20'),
(190, 41, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-17 19:14:22', '2025-12-17 19:14:22');
INSERT INTO `logs_activite` (`id`, `utilisateur_id`, `action`, `module`, `description`, `details`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(191, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:45:45', '2025-12-19 08:45:45'),
(192, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:46:33', '2025-12-19 08:46:33'),
(193, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:46:40', '2025-12-19 08:46:40'),
(194, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:47:07', '2025-12-19 08:47:07'),
(195, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:47:15', '2025-12-19 08:47:15'),
(196, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'BDD\' pour   - Note: 19/20.00', NULL, NULL, NULL, '2025-12-19 08:48:10', '2025-12-19 08:48:10'),
(197, 35, 'correction_publiee', 'corrections', 'Correction de \'BDD\' pour   - Note: 19/20.00', NULL, NULL, NULL, '2025-12-19 08:48:22', '2025-12-19 08:48:22'),
(198, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:49:19', '2025-12-19 08:49:19'),
(199, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-19 08:49:29', '2025-12-19 08:49:29'),
(200, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:38:46', '2026-01-12 08:38:46'),
(201, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:43:02', '2026-01-12 08:43:02'),
(202, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:43:43', '2026-01-12 08:43:43'),
(203, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:45:04', '2026-01-12 08:45:04'),
(204, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:45:13', '2026-01-12 08:45:13'),
(205, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:46:10', '2026-01-12 08:46:10'),
(206, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:46:22', '2026-01-12 08:46:22'),
(207, 35, 'creation_examen', 'examens', 'Création de l\'examen \'BAse de donné\'', NULL, NULL, NULL, '2026-01-12 08:49:59', '2026-01-12 08:49:59'),
(208, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:50:09', '2026-01-12 08:50:09'),
(209, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:50:22', '2026-01-12 08:50:22'),
(210, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:52:32', '2026-01-12 08:52:32'),
(211, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:52:40', '2026-01-12 08:52:40'),
(212, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'BAse de donné\' pour   - Note: 16/20.00', NULL, NULL, NULL, '2026-01-12 08:53:19', '2026-01-12 08:53:19'),
(213, 35, 'correction_publiee', 'corrections', 'Correction de \'BAse de donné\' pour   - Note: 16/20.00', NULL, NULL, NULL, '2026-01-12 08:53:47', '2026-01-12 08:53:47'),
(214, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:53:57', '2026-01-12 08:53:57'),
(215, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:54:02', '2026-01-12 08:54:02'),
(216, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:54:20', '2026-01-12 08:54:20'),
(217, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:54:26', '2026-01-12 08:54:26'),
(218, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:57:01', '2026-01-12 08:57:01'),
(219, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:57:07', '2026-01-12 08:57:07'),
(220, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:57:45', '2026-01-12 08:57:45'),
(221, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 08:57:53', '2026-01-12 08:57:53'),
(222, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Algo\'', NULL, NULL, NULL, '2026-01-12 09:00:07', '2026-01-12 09:00:07'),
(223, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 09:00:12', '2026-01-12 09:00:12'),
(224, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 09:00:21', '2026-01-12 09:00:21'),
(225, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 09:01:31', '2026-01-12 09:01:31'),
(226, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-12 14:39:51', '2026-01-12 14:39:51'),
(227, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-13 09:09:05', '2026-01-13 09:09:05'),
(228, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-13 10:42:23', '2026-01-13 10:42:23'),
(229, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-13 10:42:31', '2026-01-13 10:42:31'),
(230, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-14 09:07:10', '2026-01-14 09:07:10'),
(231, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-15 17:02:43', '2026-01-15 17:02:43'),
(232, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-29 18:49:56', '2026-01-29 18:49:56'),
(233, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-29 18:51:59', '2026-01-29 18:51:59'),
(234, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-29 18:52:11', '2026-01-29 18:52:11'),
(235, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-07 23:58:54', '2026-02-07 23:58:54'),
(236, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 00:00:43', '2026-02-08 00:00:43'),
(237, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 00:00:56', '2026-02-08 00:00:56'),
(238, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 00:01:31', '2026-02-08 00:01:31'),
(239, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 00:01:39', '2026-02-08 00:01:39'),
(240, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:29:20', '2026-02-08 09:29:20'),
(241, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Fadil ASSANE (ID: 42)', '{\"utilisateur_id\":42}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:34:50', '2026-02-08 09:34:50'),
(242, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:35:30', '2026-02-08 09:35:30'),
(243, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:36:37', '2026-02-08 09:36:37'),
(244, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:37:55', '2026-02-08 09:37:55'),
(245, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:38:01', '2026-02-08 09:38:01'),
(246, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:55:30', '2026-02-08 09:55:30'),
(247, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:55:36', '2026-02-08 09:55:36'),
(248, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:55:41', '2026-02-08 09:55:41'),
(249, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:55:49', '2026-02-08 09:55:49'),
(250, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Ass DIDI (ID: 43) - Matricule: ETU-2026-001', '{\"utilisateur_id\":43,\"matricule\":\"ETU-2026-001\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-08 09:57:25', '2026-02-08 09:57:25'),
(251, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-09 08:02:59', '2026-02-09 08:02:59'),
(252, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:04:09', '2026-02-09 08:04:09'),
(253, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:40:37', '2026-02-09 08:40:37'),
(254, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:40:54', '2026-02-09 08:40:54'),
(255, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:50:44', '2026-02-09 08:50:44'),
(256, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:51:01', '2026-02-09 08:51:01'),
(257, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Mohamad AHAMAD (ID: 44) - Matricule: ETU-2026-002', '{\"utilisateur_id\":44,\"matricule\":\"ETU-2026-002\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:54:03', '2026-02-09 08:54:03'),
(258, 44, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-02-09 08:55:18', '2026-02-09 08:55:18'),
(259, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 08:56:34', '2026-02-09 08:56:34'),
(260, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-09 08:57:04', '2026-02-09 08:57:04'),
(261, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-09 08:57:57', '2026-02-09 08:57:57'),
(262, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 09:32:05', '2026-02-09 09:32:05'),
(263, 1, 'affectation_etudiant', 'classes', 'Affectation de Mohamad AHAMAD à la classe BTS SIO SLAM 1 (alternance)', '{\"classe_id\":1,\"etudiant_id\":44,\"regime\":\"alternance\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 09:52:14', '2026-02-09 09:52:14'),
(264, 1, 'affectation_etudiant', 'classes', 'Affectation de Fadil ASSANE à la classe BTS SIO SLAM 1 (initial)', '{\"classe_id\":1,\"etudiant_id\":39,\"regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 09:54:15', '2026-02-09 09:54:15'),
(265, 1, 'modification_utilisateur', 'utilisateurs', 'Modification de l\'utilisateur Darlen MONKOTAN (ID: 45)', '{\"utilisateur_id\":45}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 09:59:58', '2026-02-09 09:59:58'),
(266, 1, 'suppression_utilisateur', 'utilisateurs', 'Suppression de l\'utilisateur Darlen MONKOTAN (ID: 45)', '{\"utilisateur_id\":45}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 10:00:02', '2026-02-09 10:00:02'),
(267, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Darlene MONKOTAN (ID: 51) - Matricule: ENS-2026-767', '{\"utilisateur_id\":51,\"matricule\":\"ENS-2026-767\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 10:47:54', '2026-02-09 10:47:54'),
(268, 1, 'suppression_utilisateur', 'utilisateurs', 'Suppression de l\'utilisateur Darlene MONKOTAN (ID: 50)', '{\"utilisateur_id\":50}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 10:48:09', '2026-02-09 10:48:09'),
(269, 1, 'suppression_utilisateur', 'utilisateurs', 'Suppression de l\'utilisateur Darlene MONKOTAN (ID: 51)', '{\"utilisateur_id\":51}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 10:48:14', '2026-02-09 10:48:14'),
(270, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Darlen MONKOTAN (ID: 45) - Matricule: ENS-2026-592', '{\"utilisateur_id\":45,\"matricule\":\"ENS-2026-592\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 11:42:25', '2026-02-09 11:42:25'),
(271, 45, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-02-09 11:43:57', '2026-02-09 11:43:57'),
(272, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-09 11:50:09', '2026-02-09 11:50:09'),
(273, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 11:50:24', '2026-02-09 11:50:24'),
(274, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 11:50:43', '2026-02-09 11:50:43'),
(275, 35, 'creation_examen', 'examens', 'Création de l\'examen \'BDD\'', NULL, NULL, NULL, '2026-02-09 11:52:12', '2026-02-09 11:52:12'),
(276, 35, 'creation_examen', 'examens', 'Création de l\'examen \'ddd\'', NULL, NULL, NULL, '2026-02-09 11:58:18', '2026-02-09 11:58:18'),
(277, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'ddd\' pour   - Note: 19/20.00', NULL, NULL, NULL, '2026-02-09 12:02:56', '2026-02-09 12:02:56'),
(278, 35, 'correction_publiee', 'corrections', 'Correction de \'ddd\' pour   - Note: 19/20.00', NULL, NULL, NULL, '2026-02-09 12:03:46', '2026-02-09 12:03:46'),
(279, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'Base de donné\' pour   - Note: 17/20.00', NULL, NULL, NULL, '2026-02-09 12:05:23', '2026-02-09 12:05:23'),
(280, 35, 'correction_publiee', 'corrections', 'Correction de \'Base de donné\' pour   - Note: 17/20.00', NULL, NULL, NULL, '2026-02-09 12:05:52', '2026-02-09 12:05:52'),
(281, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 12:06:53', '2026-02-09 12:06:53'),
(282, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 12:07:02', '2026-02-09 12:07:02'),
(283, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-09 21:23:45', '2026-02-09 21:23:45'),
(284, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-10 21:37:36', '2026-02-10 21:37:36'),
(285, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Developpeur\'', NULL, NULL, NULL, '2026-02-10 21:43:25', '2026-02-10 21:43:25'),
(286, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-10 21:44:21', '2026-02-10 21:44:21'),
(287, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'Developpeur\' pour   - Note: 20/20.00', NULL, NULL, NULL, '2026-02-10 21:49:57', '2026-02-10 21:49:57'),
(288, 35, 'correction_publiee', 'corrections', 'Correction de \'Developpeur\' pour   - Note: 20/20.00', NULL, NULL, NULL, '2026-02-10 21:50:17', '2026-02-10 21:50:17'),
(289, 35, 'creation_examen', 'examens', 'Création de l\'examen \'ALGO\'', NULL, NULL, NULL, '2026-02-10 22:03:02', '2026-02-10 22:03:02'),
(290, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-11 07:53:22', '2026-02-11 07:53:22'),
(291, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-11 07:54:20', '2026-02-11 07:54:20'),
(292, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-11 07:54:25', '2026-02-11 07:54:25'),
(293, 35, 'creation_examen', 'examens', 'Création de l\'examen \'Developpeur\'', NULL, NULL, NULL, '2026-02-11 07:55:22', '2026-02-11 07:55:22'),
(294, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-11 07:55:38', '2026-02-11 07:55:38'),
(296, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #12 - Étudiant: Fadil ASSANE', NULL, NULL, NULL, '2026-02-11 08:00:02', '2026-02-11 08:00:02'),
(297, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #12 - Étudiant: Fadil ASSANE', NULL, NULL, NULL, '2026-02-11 09:00:21', '2026-02-11 09:00:21'),
(298, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : ignore - Session #12 - Étudiant: Fadil ASSANE', NULL, NULL, NULL, '2026-02-11 09:00:31', '2026-02-11 09:00:31'),
(299, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : sanction - Session #12 - Étudiant: Fadil ASSANE', NULL, NULL, NULL, '2026-02-11 09:00:41', '2026-02-11 09:00:41'),
(300, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #12 - Étudiant: Fadil ASSANE', NULL, NULL, NULL, '2026-02-11 09:00:50', '2026-02-11 09:00:50'),
(301, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-11 11:47:20', '2026-02-11 11:47:20'),
(302, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-11 11:47:57', '2026-02-11 11:47:57'),
(303, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-11 11:48:05', '2026-02-11 11:48:05'),
(304, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 10:19:09', '2026-02-12 10:19:09'),
(305, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 10:19:15', '2026-02-12 10:19:15'),
(306, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 10:19:22', '2026-02-12 10:19:22'),
(307, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 12:38:43', '2026-02-12 12:38:43'),
(308, 44, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-12 13:31:44', '2026-02-12 13:31:44'),
(309, 44, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-12 13:33:28', '2026-02-12 13:33:28'),
(310, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:17:49', '2026-02-12 15:17:49'),
(311, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:38:32', '2026-02-12 15:38:32'),
(312, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:38:56', '2026-02-12 15:38:56'),
(313, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:40:25', '2026-02-12 15:40:25'),
(314, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:40:32', '2026-02-12 15:40:32'),
(315, 44, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-12 15:40:49', '2026-02-12 15:40:49'),
(316, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:41:24', '2026-02-12 15:41:24'),
(317, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:41:32', '2026-02-12 15:41:32'),
(318, 1, 'modification_affectation', 'classes', 'Modification de l\'affectation de Mohamad AHAMAD dans la classe BTS SIO SLAM 1', '{\"classe_id\":1,\"etudiant_id\":44,\"ancien_regime\":\"alternance\",\"nouveau_regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:42:51', '2026-02-12 15:42:51'),
(319, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:42:59', '2026-02-12 15:42:59'),
(320, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:43:05', '2026-02-12 15:43:05'),
(321, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:44:25', '2026-02-12 15:44:25'),
(322, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:44:32', '2026-02-12 15:44:32'),
(323, 1, 'suppression_utilisateur', 'utilisateurs', 'Suppression de l\'utilisateur Mohamad AHAMAD (ID: 44)', '{\"utilisateur_id\":44}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:44:40', '2026-02-12 15:44:40'),
(324, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Ahamada MOHAMED (ID: 44) - Matricule: ETU-2026-400', '{\"utilisateur_id\":44,\"matricule\":\"ETU-2026-400\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:45:18', '2026-02-12 15:45:18'),
(325, 44, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-12 15:46:29', '2026-02-12 15:46:29'),
(326, 44, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-12 15:47:28', '2026-02-12 15:47:28'),
(327, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:47:51', '2026-02-12 15:47:51'),
(328, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 15:48:00', '2026-02-12 15:48:00'),
(329, 35, 'creation_examen', 'examens', 'Création de l\'examen \'DEV\'', NULL, NULL, NULL, '2026-02-12 15:48:39', '2026-02-12 15:48:39'),
(330, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'DEV\' pour   - Note: 18/20.00', NULL, NULL, NULL, '2026-02-12 15:50:11', '2026-02-12 15:50:11'),
(331, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #13 - Étudiant: Ahamada MOHAMED', NULL, NULL, NULL, '2026-02-12 15:50:59', '2026-02-12 15:50:59'),
(332, 35, 'toggle_question', 'questions', 'Question \'Définis moi le sigles BDD\' désactivée', NULL, NULL, NULL, '2026-02-12 16:05:21', '2026-02-12 16:05:21'),
(333, 35, 'modification_question', 'questions', 'Modification de la question \'Python est un langages de programmation algorithmique ?\'', NULL, NULL, NULL, '2026-02-12 16:05:54', '2026-02-12 16:05:54'),
(334, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 16:25:18', '2026-02-12 16:25:18'),
(335, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 16:36:04', '2026-02-12 16:36:04'),
(336, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-12 17:18:24', '2026-02-12 17:18:24'),
(337, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-02-13 07:44:32', '2026-02-13 07:44:32'),
(338, 42, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 11:31:25', '2026-03-09 11:31:25'),
(339, 42, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 11:31:30', '2026-03-09 11:31:30'),
(340, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 11:31:38', '2026-03-09 11:31:38'),
(341, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 11:31:41', '2026-03-09 11:31:41'),
(342, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 11:31:47', '2026-03-09 11:31:47'),
(343, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 11:37:55', '2026-03-09 11:37:55'),
(344, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:01:10', '2026-03-09 12:01:10'),
(345, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:01:17', '2026-03-09 12:01:17'),
(346, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:01:25', '2026-03-09 12:01:25'),
(347, 35, 'creation_examen', 'examens', 'Création de l\'examen \'BDD\'', NULL, NULL, NULL, '2026-03-09 12:02:23', '2026-03-09 12:02:23'),
(348, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:02:39', '2026-03-09 12:02:39'),
(349, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:02:46', '2026-03-09 12:02:46'),
(350, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:04:30', '2026-03-09 12:04:30'),
(351, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:04:37', '2026-03-09 12:04:37'),
(352, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #14 - Étudiant: Lucas LEFEBVRE', NULL, NULL, NULL, '2026-03-09 12:04:54', '2026-03-09 12:04:54'),
(353, 35, 'correction_sauvegardee', 'corrections', 'Correction de \'BDD\' pour   - Note: 20/20.00', NULL, NULL, NULL, '2026-03-09 12:05:07', '2026-03-09 12:05:07'),
(354, 35, 'correction_publiee', 'corrections', 'Correction de \'BDD\' pour   - Note: 20/20.00', NULL, NULL, NULL, '2026-03-09 12:05:13', '2026-03-09 12:05:13'),
(355, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:05:20', '2026-03-09 12:05:20'),
(356, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:05:26', '2026-03-09 12:05:26'),
(357, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:05:37', '2026-03-09 12:05:37'),
(358, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:05:43', '2026-03-09 12:05:43'),
(359, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : sanction - Session #14 - Étudiant: Lucas LEFEBVRE', NULL, NULL, NULL, '2026-03-09 12:05:57', '2026-03-09 12:05:57'),
(360, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #14 - Étudiant: Lucas LEFEBVRE', NULL, NULL, NULL, '2026-03-09 12:06:09', '2026-03-09 12:06:09'),
(361, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:06:13', '2026-03-09 12:06:13'),
(362, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:06:19', '2026-03-09 12:06:19'),
(363, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 12:06:28', '2026-03-09 12:06:28'),
(364, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:37:09', '2026-03-09 19:37:09'),
(365, 1, 'affectation_masse', 'classes', 'Affectation de 9 étudiants à la classe BTS SIO SLAM 2', '{\"classe_id\":2,\"nb_etudiants\":9,\"regime\":\"initial\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:39:36', '2026-03-09 19:39:36'),
(366, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur Jean BADA (ID: 52) - Matricule: ENS-2026-259', '{\"utilisateur_id\":52,\"matricule\":\"ENS-2026-259\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:41:03', '2026-03-09 19:41:03'),
(367, 1, 'affectation_enseignant_classe', 'enseignant_classe', 'Affectation de Jean BADA à la classe BTS SIO SLAM 2 pour la matière Base de Données', '{\"enseignant_id\":52,\"classe_id\":2,\"matiere_id\":2}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:41:51', '2026-03-09 19:41:51'),
(368, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:41:58', '2026-03-09 19:41:58'),
(369, 52, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:42:35', '2026-03-09 19:42:35'),
(370, 52, 'creation_question', 'questions', 'Création de la question \'C\'est quoi une BDD\'', NULL, NULL, NULL, '2026-03-09 19:45:56', '2026-03-09 19:45:56'),
(371, 52, 'creation_examen', 'examens', 'Création de l\'examen \'BDD\'', NULL, NULL, NULL, '2026-03-09 19:46:46', '2026-03-09 19:46:46'),
(372, 52, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:46:51', '2026-03-09 19:46:51'),
(373, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:46:57', '2026-03-09 19:46:57'),
(374, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:47:13', '2026-03-09 19:47:13'),
(375, 52, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:47:20', '2026-03-09 19:47:20'),
(376, 52, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:47:34', '2026-03-09 19:47:34'),
(377, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:47:41', '2026-03-09 19:47:41'),
(378, 1, 'creation_utilisateur', 'utilisateurs', 'Création de l\'utilisateur DIDI Fadil (ID: 53) - Matricule: ETU-2026-485', '{\"utilisateur_id\":53,\"matricule\":\"ETU-2026-485\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:48:54', '2026-03-09 19:48:54'),
(379, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:49:10', '2026-03-09 19:49:10'),
(380, 53, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:49:58', '2026-03-09 19:49:58'),
(381, 53, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:51:01', '2026-03-09 19:51:01'),
(382, 52, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:51:08', '2026-03-09 19:51:08'),
(383, 52, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #15 - Étudiant: DIDI Fadil', NULL, NULL, NULL, '2026-03-09 19:51:44', '2026-03-09 19:51:44'),
(384, 52, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:51:49', '2026-03-09 19:51:49'),
(385, 53, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:51:54', '2026-03-09 19:51:54'),
(386, 53, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:52:09', '2026-03-09 19:52:09'),
(387, 52, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-09 19:52:17', '2026-03-09 19:52:17'),
(388, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:22:27', '2026-03-21 15:22:27'),
(389, 1, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:23:15', '2026-03-21 15:23:15'),
(390, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:23:21', '2026-03-21 15:23:21'),
(391, 35, 'creation_examen', 'examens', 'Création de l\'examen \'DEV\'', NULL, NULL, NULL, '2026-03-21 15:25:21', '2026-03-21 15:25:21'),
(392, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:25:48', '2026-03-21 15:25:48'),
(393, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:25:56', '2026-03-21 15:25:56'),
(394, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:27:28', '2026-03-21 15:27:28'),
(395, 35, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:27:34', '2026-03-21 15:27:34'),
(396, 35, 'decision_alerte_triche', 'alertes', 'Décision sur alerte : annulation - Session #16 - Étudiant: Lucas LEFEBVRE', NULL, NULL, NULL, '2026-03-21 15:28:15', '2026-03-21 15:28:15'),
(397, 35, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:28:20', '2026-03-21 15:28:20');
INSERT INTO `logs_activite` (`id`, `utilisateur_id`, `action`, `module`, `description`, `details`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(398, 9, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:28:26', '2026-03-21 15:28:26'),
(399, 9, 'deconnexion', 'authentification', 'Déconnexion', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', '2026-03-21 15:29:33', '2026-03-21 15:29:33'),
(400, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Safari/605.1.15', '2026-04-08 07:09:49', '2026-04-08 07:09:49'),
(401, 1, 'connexion', 'authentification', 'Connexion réussie', '[]', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Safari/605.1.15', '2026-04-08 07:10:00', '2026-04-08 07:10:00');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `couleur` varchar(7) NOT NULL DEFAULT '#10B981',
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `coefficient` int(11) NOT NULL DEFAULT 1,
  `statut` enum('active','inactive') NOT NULL DEFAULT 'active',
  `cree_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `couleur`, `code`, `description`, `coefficient`, `statut`, `cree_par`, `created_at`, `updated_at`) VALUES
(1, 'Programmation Orientée Objet', '#10B981', 'INFO101', 'Matière du programme IRIS', 3, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(2, 'Base de Données', '#10B981', 'INFO102', 'Matière du programme IRIS', 3, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(3, 'Développement Web', '#10B981', 'INFO103', 'Matière du programme IRIS', 4, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(4, 'Algorithmique', '#10B981', 'INFO104', 'Matière du programme IRIS', 3, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(5, 'Réseaux Informatiques', '#10B981', 'INFO105', 'Matière du programme IRIS', 2, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(6, 'Mathématiques Appliquées', '#10B981', 'MATH201', 'Matière du programme IRIS', 2, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(7, 'Anglais Technique', '#10B981', 'LANG301', 'Matière du programme IRIS', 2, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(8, 'Gestion de Projet', '#10B981', 'MGMT401', 'Matière du programme IRIS', 2, 'active', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_13_134454_create_roles_table', 1),
(5, '2025_11_13_134455_create_permissions_table', 1),
(6, '2025_11_13_134455_create_role_permission_table', 1),
(7, '2025_11_13_134456_create_utilisateurs_table', 1),
(8, '2025_11_13_134457_create_matieres_table', 1),
(9, '2025_11_13_134458_create_classes_table', 1),
(10, '2025_11_13_134459_create_classe_etudiant_table', 1),
(11, '2025_11_13_134460_create_enseignant_classe_table', 1),
(12, '2025_11_13_134461_create_types_question_table', 1),
(13, '2025_11_13_134462_create_banque_questions_table', 1),
(14, '2025_11_13_134463_create_examens_table', 1),
(15, '2025_11_13_134464_create_examen_question_table', 1),
(16, '2025_11_13_134465_create_sessions_examen_table', 1),
(17, '2025_11_13_134466_create_reponses_etudiant_table', 1),
(18, '2025_11_13_134467_create_logs_activite_table', 1),
(19, '2025_11_13_203113_create_questions_table', 2),
(20, '2025_11_13_203211_create_reponses_table', 2),
(21, '2025_11_14_002555_update_banque_questions_table_structure', 3),
(22, '2025_11_14_122045_add_type_examen_to_examens_table', 4),
(23, '2025_11_14_122128_create_copies_etudiants_table', 5),
(24, '2025_11_15_180404_create_enseignants_table', 6),
(25, '2025_11_15_180404_create_etudiants_table', 6),
(27, '2025_11_15_180407_remove_classe_id_from_utilisateurs', 7),
(28, '2025_11_15_180658_migrate_existing_users_data', 7),
(29, '2025_11_20_143751_create_notifications_table', 8),
(30, '2025_11_21_091832_create_critere_corrections_table', 9),
(31, '2025_11_21_115839_add_doit_changer_mot_de_passe_to_utilisateurs_table', 9),
(32, '2025_12_07_220012_create_rappels_table', 10),
(33, '2025_12_07_234357_add_tentatives_triche_to_sessions_examen_table', 10),
(34, '2025_12_08_102134_add_randomization_to_examens_table', 10),
(35, '2025_12_08_103534_add_ordre_questions_to_sessions_examen_table', 10),
(36, '2025_12_09_084557_add_couleur_to_matieres_table', 10),
(37, '2025_12_10_212511_add_data_column_to_notifications_table', 10),
(38, '2025_12_10_215357_make_notification_columns_nullable', 10),
(39, '2025_12_10_221058_add_deleted_at_to_classes_table', 10),
(41, '2025_12_11_164432_add_statut_correction_to_sessions_examen_table', 10),
(42, '2025_12_16_092447_update_sessions_examen_statut', 11),
(43, '2025_12_16_093409_uniformiser_reponse_etudiants_columns', 12),
(44, '2026_01_13_111027_add_regime_entreprise_to_etudiants_table', 13),
(45, '2026_02_09_114458_drop_matricule_from_enseignants_table', 14),
(46, '2026_02_11_080339_add_alertes_sanctions_to_sessions_examen_table', 15),
(47, '2026_02_12_170835_add_points_par_defaut_to_banque_questions_table', 16),
(48, '2026_02_12_172224_add_missing_columns_to_banque_questions_table', 17);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `utilisateur_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `titre` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `icone` varchar(255) DEFAULT NULL,
  `est_lue` tinyint(1) NOT NULL DEFAULT 0,
  `lue_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `utilisateur_id`, `type`, `data`, `titre`, `message`, `lien`, `icone`, `est_lue`, `lue_at`, `created_at`, `updated_at`) VALUES
(1, 36, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM !', 'http://127.0.0.1:8000', '🎓', 1, '2025-11-21 08:12:01', '2025-11-20 14:49:52', '2025-11-21 08:12:01'),
(2, 37, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2025-11-21 08:35:26', '2025-11-21 08:35:26'),
(3, 38, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2025-11-21 10:51:54', '2025-11-21 10:51:54'),
(4, 39, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2025-12-17 08:50:44', '2025-12-17 08:50:44'),
(5, 41, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2025-12-17 19:02:20', '2025-12-17 19:02:20'),
(6, 42, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2026-02-08 09:34:50', '2026-02-08 09:34:50'),
(7, 43, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2026-02-08 09:57:25', '2026-02-08 09:57:25'),
(8, 44, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://iris.test/etudiant/dashboard', '🎓', 0, NULL, '2026-02-09 08:54:03', '2026-02-09 08:54:03'),
(9, 51, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Commencez par créer vos premières questions.', 'http://iris.test/enseignant/dashboard', '🎓', 0, NULL, '2026-02-09 10:47:54', '2026-02-09 10:47:54'),
(10, 45, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Commencez par créer vos premières questions.', 'http://iris.test/enseignant/dashboard', '🎓', 0, NULL, '2026-02-09 11:42:25', '2026-02-09 11:42:25'),
(11, 44, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://iris.test/etudiant/dashboard', '🎓', 0, NULL, '2026-02-12 15:45:18', '2026-02-12 15:45:18'),
(12, 52, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Commencez par créer vos premières questions.', 'http://127.0.0.1:8000/enseignant/dashboard', '🎓', 0, NULL, '2026-03-09 19:41:03', '2026-03-09 19:41:03'),
(13, 53, 'bienvenue', NULL, '👋 Bienvenue !', 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.', 'http://127.0.0.1:8000/etudiant/dashboard', '🎓', 0, NULL, '2026-03-09 19:48:54', '2026-03-09 19:48:54');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `nom`, `module`, `description`, `created_at`, `updated_at`) VALUES
(1, 'creer_super_admin', 'utilisateurs', 'Créer un super administrateur', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(2, 'creer_admin', 'utilisateurs', 'Créer un administrateur', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(3, 'creer_enseignant', 'utilisateurs', 'Créer un enseignant', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(4, 'creer_etudiant', 'utilisateurs', 'Créer un étudiant', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(5, 'modifier_utilisateur', 'utilisateurs', 'Modifier un utilisateur', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(6, 'supprimer_utilisateur', 'utilisateurs', 'Supprimer un utilisateur', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(7, 'voir_tous_utilisateurs', 'utilisateurs', 'Voir tous les utilisateurs', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(8, 'activer_desactiver_utilisateur', 'utilisateurs', 'Activer/Désactiver un utilisateur', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(9, 'creer_classe', 'classes', 'Créer une classe', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(10, 'modifier_classe', 'classes', 'Modifier une classe', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(11, 'supprimer_classe', 'classes', 'Supprimer une classe', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(12, 'affecter_etudiants', 'classes', 'Affecter des étudiants aux classes', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(13, 'affecter_enseignants', 'classes', 'Affecter des enseignants aux classes', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(14, 'voir_toutes_classes', 'classes', 'Voir toutes les classes', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(15, 'creer_matiere', 'matieres', 'Créer une matière', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(16, 'modifier_matiere', 'matieres', 'Modifier une matière', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(17, 'supprimer_matiere', 'matieres', 'Supprimer une matière', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(18, 'creer_examen', 'examens', 'Créer un examen', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(19, 'modifier_examen', 'examens', 'Modifier un examen', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(20, 'supprimer_examen', 'examens', 'Supprimer un examen', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(21, 'publier_examen', 'examens', 'Publier un examen', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(22, 'voir_tous_examens', 'examens', 'Voir tous les examens', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(23, 'corriger_examen', 'examens', 'Corriger un examen', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(24, 'creer_question', 'questions', 'Créer une question', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(25, 'modifier_question', 'questions', 'Modifier une question', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(26, 'supprimer_question', 'questions', 'Supprimer une question', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(27, 'voir_banque_questions', 'questions', 'Voir la banque de questions', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(28, 'voir_statistiques_globales', 'statistiques', 'Voir les statistiques globales', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(29, 'voir_statistiques_classe', 'statistiques', 'Voir les statistiques d\'une classe', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(30, 'exporter_resultats', 'statistiques', 'Exporter les résultats', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(31, 'consulter_logs', 'statistiques', 'Consulter les logs d\'activité', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(32, 'modifier_parametres', 'systeme', 'Modifier les paramètres du système', '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(33, 'sauvegarder_base', 'systeme', 'Sauvegarder la base de données', '2025-11-13 13:23:22', '2025-11-13 13:23:22');

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matiere_id` bigint(20) UNSIGNED NOT NULL,
  `createur_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('qcm_simple','qcm_multiple','vrai_faux','texte_libre') NOT NULL DEFAULT 'qcm_simple',
  `enonce` text NOT NULL,
  `explication` text DEFAULT NULL,
  `difficulte` enum('facile','moyen','difficile') NOT NULL DEFAULT 'moyen',
  `points` decimal(5,2) NOT NULL DEFAULT 1.00,
  `tags` varchar(255) DEFAULT NULL,
  `est_active` tinyint(1) NOT NULL DEFAULT 1,
  `nb_utilisations` int(11) NOT NULL DEFAULT 0,
  `taux_reussite` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `matiere_id`, `createur_id`, `type`, `enonce`, `explication`, `difficulte`, `points`, `tags`, `est_active`, `nb_utilisations`, `taux_reussite`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 35, 'texte_libre', 'Qu\'est ce qu\'une base de données', 'c\'est une question simple', 'facile', 10.00, NULL, 1, 0, NULL, '2025-11-13 22:14:18', '2025-11-13 22:39:28', '2025-11-13 22:39:28'),
(2, 4, 35, 'texte_libre', 'Qu\'est ce qu\'un algorithme en Python ?', NULL, 'facile', 13.00, NULL, 1, 0, NULL, '2025-11-13 22:33:51', '2025-11-13 22:38:55', NULL),
(3, 2, 35, 'texte_libre', 'Qu\'est ce qu\'une base de données (Copie)', 'c\'est une question simple', 'facile', 10.00, NULL, 1, 0, NULL, '2025-11-13 22:39:12', '2025-11-13 22:39:12', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `rappels`
--

CREATE TABLE `rappels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `etudiant_id` bigint(20) UNSIGNED NOT NULL,
  `examen_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('24h','1h','personnalise') NOT NULL DEFAULT '24h',
  `date_rappel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `envoye` tinyint(1) NOT NULL DEFAULT 0,
  `date_envoi` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reponses`
--

CREATE TABLE `reponses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `texte` text NOT NULL,
  `est_correcte` tinyint(1) NOT NULL DEFAULT 0,
  `ordre` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reponses`
--

INSERT INTO `reponses` (`id`, `question_id`, `texte`, `est_correcte`, `ordre`, `created_at`, `updated_at`) VALUES
(2, 5, 'Python', 1, 1, '2025-11-17 09:01:09', '2025-11-17 09:01:09'),
(3, 5, 'Java', 1, 2, '2025-11-17 09:01:09', '2025-11-17 09:01:09'),
(4, 5, 'Scala', 1, 3, '2025-11-17 09:01:09', '2025-11-17 09:01:09'),
(5, 6, 'SQL', 1, 1, '2025-11-17 09:02:52', '2025-11-17 09:02:52'),
(6, 6, 'Python', 0, 2, '2025-11-17 09:02:52', '2025-11-17 09:02:52'),
(7, 6, 'Scala', 0, 3, '2025-11-17 09:02:52', '2025-11-17 09:02:52'),
(8, 6, 'Java', 0, 4, '2025-11-17 09:02:52', '2025-11-17 09:02:52'),
(9, 7, 'Vrai', 1, 1, '2025-11-17 09:05:23', '2025-11-17 09:05:23'),
(10, 7, 'Faux', 0, 2, '2025-11-17 09:05:23', '2025-11-17 09:05:23'),
(11, 9, 'Bande dessinée', 0, 1, '2026-03-09 19:45:56', '2026-03-09 19:45:56'),
(12, 9, 'Base de donnée', 1, 2, '2026-03-09 19:45:56', '2026-03-09 19:45:56'),
(13, 9, 'Base de document', 0, 3, '2026-03-09 19:45:56', '2026-03-09 19:45:56');

-- --------------------------------------------------------

--
-- Structure de la table `reponses_etudiant`
--

CREATE TABLE `reponses_etudiant` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_examen_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `reponse_donnee` text DEFAULT NULL,
  `est_correct` tinyint(1) NOT NULL DEFAULT 0,
  `est_correcte` tinyint(1) DEFAULT NULL,
  `points_obtenus` decimal(5,2) DEFAULT NULL,
  `points_max` decimal(5,2) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `commentaire_correcteur` text DEFAULT NULL,
  `corrige_par` bigint(20) UNSIGNED DEFAULT NULL,
  `corrige_le` timestamp NULL DEFAULT NULL,
  `temps_passe_secondes` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reponses_etudiant`
--

INSERT INTO `reponses_etudiant` (`id`, `session_id`, `session_examen_id`, `question_id`, `reponse_donnee`, `est_correct`, `est_correcte`, `points_obtenus`, `points_max`, `commentaire`, `commentaire_correcteur`, `corrige_par`, `corrige_le`, `temps_passe_secondes`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 3, 'hhkd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-17 12:48:29', '2025-11-17 12:48:29'),
(2, 3, 3, 7, '9', 1, 1, 10.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-17 13:48:30', '2025-11-17 13:48:30'),
(3, 3, 3, 6, '5', 1, 1, 10.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-11-17 13:48:30', '2025-11-17 13:48:30'),
(4, 4, 4, 8, 'Base de donnée', 1, 1, 2.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 07:52:19', '2026-02-09 12:05:23'),
(5, 4, 4, 7, '9', 1, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 07:52:19', '2025-12-16 07:52:19'),
(6, 4, 4, 6, '5', 1, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 07:52:19', '2025-12-16 07:52:19'),
(7, 4, 4, 5, '[\"2\",\"3\",\"4\"]', 1, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 07:52:19', '2025-12-16 07:52:19'),
(8, NULL, 5, 8, '\"Base de donn\\u00e9e\"', 1, 1, 3.00, NULL, 'C\'est très simple', 'C\'est très simple', NULL, NULL, 0, '2025-12-16 13:53:54', '2025-12-17 08:44:41'),
(9, NULL, 5, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 13:53:54', '2025-12-16 13:53:54'),
(10, NULL, 5, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 13:53:54', '2025-12-16 13:53:54'),
(11, NULL, 5, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 13:53:54', '2025-12-16 13:53:54'),
(12, NULL, 6, 8, '\"BDD se d\\u00e9finis comme suit Base de donn\\u00e9\"', 1, 1, 4.00, NULL, 'trop simple', 'trop simple', NULL, NULL, 0, '2025-12-16 14:14:47', '2025-12-19 08:48:10'),
(13, NULL, 6, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 14:14:47', '2025-12-16 14:14:47'),
(14, NULL, 6, 6, '\"5\"', 0, 1, 10.00, NULL, NULL, NULL, NULL, NULL, 0, '2025-12-16 14:14:47', '2025-12-16 14:14:47'),
(15, NULL, 7, 8, '\"Base de donn\\u00e9e\"', 1, 1, 4.00, NULL, 'bien', 'bien', NULL, NULL, 0, '2026-01-12 08:51:31', '2026-01-12 08:53:19'),
(16, NULL, 7, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-01-12 08:51:31', '2026-01-12 08:51:31'),
(17, NULL, 7, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-01-12 08:51:31', '2026-01-12 08:51:31'),
(18, NULL, 7, 3, '\"unt\"', 1, 1, 2.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-01-12 08:51:31', '2026-01-12 08:53:19'),
(19, NULL, 8, 7, '\"9\"', 0, 1, 8.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:53:59', '2026-02-09 11:53:59'),
(20, NULL, 8, 6, '\"5\"', 0, 1, 7.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:53:59', '2026-02-09 11:53:59'),
(21, NULL, 8, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:53:59', '2026-02-09 11:53:59'),
(22, NULL, 9, 8, '\"Base de donn\\u00e9es\"', 1, 1, 4.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:59:15', '2026-02-09 12:02:56'),
(23, NULL, 9, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:59:15', '2026-02-09 11:59:15'),
(24, NULL, 9, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:59:15', '2026-02-09 11:59:15'),
(25, NULL, 9, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-09 11:59:15', '2026-02-09 11:59:15'),
(26, NULL, 10, 8, '\"Base de donn\\u00e9es\"', 1, 1, 5.00, NULL, 'c\'est bien', 'c\'est bien', NULL, NULL, 0, '2026-02-10 21:45:04', '2026-02-10 21:49:57'),
(27, NULL, 10, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 21:45:04', '2026-02-10 21:45:04'),
(28, NULL, 10, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 21:45:04', '2026-02-10 21:45:04'),
(29, NULL, 10, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 21:45:04', '2026-02-10 21:45:04'),
(30, NULL, 11, 8, '\"Base de donn\\u00e9es\"', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 22:04:10', '2026-02-10 22:04:10'),
(31, NULL, 11, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 22:04:10', '2026-02-10 22:04:10'),
(32, NULL, 11, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 22:04:10', '2026-02-10 22:04:10'),
(33, NULL, 11, 5, '\"[\\\"4\\\",\\\"2\\\",\\\"3\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-10 22:04:10', '2026-02-10 22:04:10'),
(34, NULL, 12, 7, '\"9\"', 0, 1, 10.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-11 07:56:26', '2026-02-11 07:56:26'),
(35, NULL, 12, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-11 07:56:26', '2026-02-11 07:56:26'),
(36, NULL, 12, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-11 07:56:26', '2026-02-11 07:56:26'),
(37, NULL, 13, 8, '\"BDD\"', 1, 1, 3.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-12 15:49:47', '2026-02-12 15:50:11'),
(38, NULL, 13, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-12 15:49:47', '2026-02-12 15:49:47'),
(39, NULL, 13, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-12 15:49:47', '2026-02-12 15:49:47'),
(40, NULL, 13, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-02-12 15:49:47', '2026-02-12 15:49:47'),
(41, NULL, 14, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-09 12:04:08', '2026-03-09 12:04:08'),
(42, NULL, 14, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-09 12:04:08', '2026-03-09 12:04:08'),
(43, NULL, 14, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-09 12:04:08', '2026-03-09 12:04:08'),
(44, NULL, 14, 3, '\"un language\"', 1, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-09 12:04:08', '2026-03-09 12:05:07'),
(45, NULL, 15, 9, '\"12\"', 0, 1, 10.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-09 19:50:54', '2026-03-09 19:50:54'),
(46, NULL, 16, 7, '\"9\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-21 15:27:02', '2026-03-21 15:27:02'),
(47, NULL, 16, 6, '\"5\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-21 15:27:02', '2026-03-21 15:27:02'),
(48, NULL, 16, 5, '\"[\\\"2\\\",\\\"3\\\",\\\"4\\\"]\"', 0, 1, 5.00, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-21 15:27:02', '2026-03-21 15:27:02'),
(49, NULL, 16, 1, '\"vbhvbheeb\"', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-03-21 15:27:02', '2026-03-21 15:27:02');

-- --------------------------------------------------------

--
-- Structure de la table `reponse_critere`
--

CREATE TABLE `reponse_critere` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reponse_etudiant_id` bigint(20) UNSIGNED NOT NULL,
  `critere_id` bigint(20) UNSIGNED NOT NULL,
  `points_obtenus` decimal(5,2) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `niveau_hierarchie` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `nom`, `description`, `niveau_hierarchie`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'Super Administrateur - Accès complet au système', 3, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(2, 'admin', 'Administrateur - Gestion de l\'établissement', 2, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(3, 'enseignant', 'Enseignant - Création et gestion des examens', 1, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(4, 'etudiant', 'Étudiant - Passage des examens', 0, '2025-11-13 13:23:22', '2025-11-13 13:23:22');

-- --------------------------------------------------------

--
-- Structure de la table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(2, 1, 2, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(3, 1, 3, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(4, 1, 4, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(5, 1, 5, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(6, 1, 6, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(7, 1, 7, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(8, 1, 8, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(9, 1, 9, '2025-11-13 13:23:22', '2025-11-13 13:23:22'),
(10, 1, 10, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(11, 1, 11, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(12, 1, 12, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(13, 1, 13, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(14, 1, 14, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(15, 1, 15, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(16, 1, 16, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(17, 1, 17, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(18, 1, 18, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(19, 1, 19, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(20, 1, 20, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(21, 1, 21, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(22, 1, 22, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(23, 1, 23, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(24, 1, 24, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(25, 1, 25, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(26, 1, 26, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(27, 1, 27, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(28, 1, 28, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(29, 1, 29, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(30, 1, 30, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(31, 1, 31, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(32, 1, 32, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(33, 1, 33, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(34, 2, 2, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(35, 2, 3, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(36, 2, 4, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(37, 2, 5, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(38, 2, 6, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(39, 2, 7, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(40, 2, 8, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(41, 2, 9, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(42, 2, 10, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(43, 2, 11, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(44, 2, 12, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(45, 2, 13, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(46, 2, 14, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(47, 2, 15, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(48, 2, 16, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(49, 2, 17, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(50, 2, 18, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(51, 2, 19, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(52, 2, 20, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(53, 2, 21, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(54, 2, 22, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(55, 2, 23, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(56, 2, 24, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(57, 2, 25, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(58, 2, 26, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(59, 2, 27, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(60, 2, 28, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(61, 2, 29, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(62, 2, 30, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(63, 2, 31, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(64, 3, 18, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(65, 3, 19, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(66, 3, 20, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(67, 3, 21, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(68, 3, 23, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(69, 3, 24, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(70, 3, 25, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(71, 3, 26, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(72, 3, 27, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(73, 3, 29, '2025-11-13 13:23:23', '2025-11-13 13:23:23'),
(74, 3, 30, '2025-11-13 13:23:23', '2025-11-13 13:23:23');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5EnUkwfZmvch7ScWC88wndLAxohGMswiWCabnsw7', 44, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT0cwRzhKYUIxYzhnbElSd0NmcVkyQ2kyUVFNYk5tUk11NWlFSFA0QiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njg6Imh0dHA6Ly9zaGFubmEtbWVhc3VyZWQtZmxleHVvdXNseS5uZ3Jvay1mcmVlLmRldi9ldHVkaWFudC9jYWxlbmRyaWVyIjtzOjU6InJvdXRlIjtzOjE5OiJldHVkaWFudC5jYWxlbmRyaWVyIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDQ7fQ==', 1770912748),
('IyEGVh9BWteX1qJDQYH9p4f29RkSmSVOfofmCg8Z', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia0VjVDd1SDhQTk9uaWNhTXNoUnpBbWJQQnRxaUdreDA2UFM2UTFjRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9pcmlzLnRlc3QvYWRtaW4vbWF0aWVyZXMiO3M6NToicm91dGUiO3M6MjA6ImFkbWluLm1hdGllcmVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1770968714),
('QUw75eCTqPBntzVz37lFtaPZMPMtibiIbCJiMIzO', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEJ5Y0xGcHVWMmEyb2tzaWtNb21jMFNJUlU4WTNLT2ZSWGJIZHExMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9pcmlzLnRlc3QiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fX0=', 1770916707);

-- --------------------------------------------------------

--
-- Structure de la table `sessions_examen`
--

CREATE TABLE `sessions_examen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `examen_id` bigint(20) UNSIGNED NOT NULL,
  `etudiant_id` bigint(20) UNSIGNED NOT NULL,
  `numero_tentative` int(11) NOT NULL DEFAULT 1,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime DEFAULT NULL,
  `date_soumission` datetime DEFAULT NULL,
  `note_obtenue` decimal(5,2) DEFAULT NULL,
  `note_maximale` decimal(5,2) DEFAULT NULL,
  `pourcentage` decimal(5,2) DEFAULT NULL,
  `temps_passe_secondes` int(11) NOT NULL DEFAULT 0,
  `changements_onglet` int(11) NOT NULL DEFAULT 0,
  `alertes_triche` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`alertes_triche`)),
  `decision_enseignant` enum('aucune','ignore','avertissement','annulation','sanction') NOT NULL DEFAULT 'aucune',
  `commentaire_enseignant` text DEFAULT NULL,
  `date_decision` timestamp NULL DEFAULT NULL,
  `decision_par` bigint(20) UNSIGNED DEFAULT NULL,
  `questions_repondues` int(11) NOT NULL DEFAULT 0,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `statut` enum('en_cours','soumis','corrige','abandonne','temps_ecoule') NOT NULL DEFAULT 'en_cours',
  `statut_correction` enum('en_attente','corrige','publie') NOT NULL DEFAULT 'en_attente',
  `ordre_questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ordre_questions`)),
  `ordre_reponses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ordre_reponses`)),
  `tentatives_triche` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions_examen`
--

INSERT INTO `sessions_examen` (`id`, `examen_id`, `etudiant_id`, `numero_tentative`, `date_debut`, `date_fin`, `date_soumission`, `note_obtenue`, `note_maximale`, `pourcentage`, `temps_passe_secondes`, `changements_onglet`, `alertes_triche`, `decision_enseignant`, `commentaire_enseignant`, `date_decision`, `decision_par`, `questions_repondues`, `ip_address`, `user_agent`, `statut`, `statut_correction`, `ordre_questions`, `ordre_reponses`, `tentatives_triche`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 1, '2025-11-15 21:06:54', NULL, '2025-11-15 21:06:55', NULL, NULL, NULL, 0, 0, NULL, 'aucune', NULL, NULL, NULL, 0, NULL, NULL, 'soumis', 'en_attente', NULL, NULL, 0, '2025-11-15 20:06:54', '2025-11-15 20:06:55'),
(2, 8, 1, 1, '2025-11-17 12:34:14', '2025-11-17 13:34:14', '2025-11-17 13:48:29', 0.00, 20.00, 0.00, -4455, 0, NULL, 'aucune', NULL, NULL, NULL, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'soumis', 'en_attente', NULL, NULL, 0, '2025-11-17 11:34:14', '2025-11-17 12:48:29'),
(3, 10, 1, 1, '2025-11-17 14:48:13', '2025-11-17 15:13:13', '2025-11-17 14:48:30', 20.00, 20.00, 100.00, -17, 0, NULL, 'aucune', NULL, NULL, NULL, 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'soumis', 'publie', NULL, NULL, 0, '2025-11-17 13:48:13', '2025-12-16 13:15:31'),
(4, 11, 1, 1, '2025-12-16 08:51:44', '2025-12-16 09:51:44', '2025-12-16 08:52:19', 17.00, 20.00, 85.00, -36, 0, NULL, 'aucune', NULL, NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'soumis', 'publie', NULL, NULL, 0, '2025-12-16 07:51:44', '2026-02-09 12:05:52'),
(5, 12, 1, 1, '2025-12-16 14:53:21', '2025-12-16 15:53:21', '2025-12-16 14:53:54', 18.00, 20.00, 90.00, -34, 0, NULL, 'aucune', NULL, NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'soumis', 'publie', NULL, NULL, 0, '2025-12-16 13:53:21', '2025-12-17 08:45:08'),
(6, 13, 1, 1, '2025-12-16 15:14:11', '2025-12-16 16:14:11', '2025-12-16 15:14:47', 19.00, 20.00, 95.00, -36, 0, NULL, 'aucune', NULL, NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'soumis', 'publie', NULL, NULL, 0, '2025-12-16 14:14:11', '2025-12-19 08:48:22'),
(7, 14, 1, 1, '2026-01-12 09:50:36', '2026-01-12 10:50:36', '2026-01-12 09:51:31', 16.00, 20.00, 80.00, -56, 0, NULL, 'aucune', NULL, NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'soumis', 'publie', NULL, NULL, 0, '2026-01-12 08:50:36', '2026-01-12 08:53:47'),
(8, 16, 1, 1, '2026-02-09 12:52:58', '2026-02-09 13:12:58', '2026-02-09 12:53:59', 20.00, 20.00, 100.00, -62, 0, NULL, 'aucune', NULL, NULL, NULL, 3, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'corrige', 'en_attente', NULL, NULL, 0, '2026-02-09 11:52:58', '2026-02-09 11:53:59'),
(9, 17, 1, 1, '2026-02-09 12:58:35', '2026-02-09 13:03:35', '2026-02-09 12:59:15', 19.00, 20.00, 95.00, -40, 0, NULL, 'aucune', NULL, NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'soumis', 'publie', NULL, NULL, 0, '2026-02-09 11:58:35', '2026-02-09 12:03:46'),
(10, 18, 31, 1, '2026-02-10 22:39:00', '2026-02-10 22:49:00', '2026-02-10 22:45:04', 20.00, 20.00, 100.00, -365, 0, NULL, 'aucune', NULL, NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'soumis', 'publie', NULL, '{\"7\":[10,9],\"6\":[6,5,7,8],\"5\":[2,3,4]}', 0, '2026-02-10 21:44:38', '2026-02-10 21:50:17'),
(11, 19, 31, 1, '2026-02-10 23:02:00', '2026-02-10 23:10:00', '2026-02-10 23:04:10', 15.00, 20.00, 75.00, -130, 0, NULL, 'aucune', NULL, NULL, NULL, 4, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'soumis', 'en_attente', NULL, '{\"7\":[9,10],\"6\":[5,8,7,6],\"5\":[4,2,3]}', 0, '2026-02-10 22:03:19', '2026-02-10 22:04:10'),
(12, 20, 31, 1, '2026-02-11 08:54:00', '2026-02-11 09:04:00', '2026-02-11 08:56:26', 0.00, 20.00, 0.00, -147, 0, '[{\"type\":\"changement_onglet\",\"timestamp\":\"2026-02-11T08:56:26+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}},{\"type\":\"changement_onglet\",\"timestamp\":\"2026-02-11T08:56:26+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}}]', 'annulation', 'Tricheur', '2026-02-11 09:00:50', 35, 3, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'corrige', 'en_attente', NULL, NULL, 0, '2026-02-11 07:56:02', '2026-02-11 09:00:50'),
(13, 21, 33, 1, '2026-02-12 16:48:00', '2026-02-12 16:58:00', '2026-02-12 16:49:47', 0.00, 20.00, 0.00, -107, 0, '[{\"type\":\"changement_onglet\",\"timestamp\":\"2026-02-12T16:49:47+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}}]', 'annulation', 'Tricheur', '2026-02-12 15:50:59', 35, 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'soumis', 'corrige', NULL, NULL, 0, '2026-02-12 15:48:53', '2026-02-12 15:50:59'),
(14, 22, 1, 1, '2026-03-09 13:03:00', '2026-03-09 14:03:00', '2026-03-09 13:04:08', 0.00, 20.00, 0.00, -68, 0, '[{\"type\":\"changement_onglet\",\"timestamp\":\"2026-03-09T13:04:08+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}},{\"type\":\"changement_onglet\",\"timestamp\":\"2026-03-09T13:04:08+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}}]', 'annulation', NULL, '2026-03-09 12:06:09', 35, 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', 'soumis', 'publie', NULL, NULL, 0, '2026-03-09 12:03:20', '2026-03-09 12:06:09'),
(15, 23, 34, 1, '2026-03-09 20:46:00', '2026-03-09 20:51:00', '2026-03-09 20:50:54', 0.00, 10.00, 0.00, -295, 0, '[{\"type\":\"changement_onglet\",\"timestamp\":\"2026-03-09T20:50:54+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}}]', 'annulation', 'Tricheur', '2026-03-09 19:51:44', 52, 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', 'corrige', 'en_attente', NULL, NULL, 0, '2026-03-09 19:50:38', '2026-03-09 19:51:44'),
(16, 24, 1, 1, '2026-03-21 16:24:00', '2026-03-21 16:31:00', '2026-03-21 16:27:02', 0.00, 20.00, 0.00, -182, 0, '[{\"type\":\"changement_onglet\",\"timestamp\":\"2026-03-21T16:27:02+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}},{\"type\":\"changement_onglet\",\"timestamp\":\"2026-03-21T16:27:02+01:00\",\"details\":{\"message\":\"Changement d\'onglet d\\u00e9tect\\u00e9\"}}]', 'annulation', NULL, '2026-03-21 15:28:15', 35, 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3.1 Safari/605.1.15', 'soumis', 'en_attente', NULL, NULL, 0, '2026-03-21 15:26:09', '2026-03-21 15:28:15');

-- --------------------------------------------------------

--
-- Structure de la table `types_question`
--

CREATE TABLE `types_question` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `correction_automatique` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_question`
--

INSERT INTO `types_question` (`id`, `nom`, `code`, `description`, `correction_automatique`, `created_at`, `updated_at`) VALUES
(1, 'QCM (Choix Multiple)', 'qcm', 'Question à choix multiples avec plusieurs réponses possibles', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(2, 'QCU (Choix Unique)', 'qcu', 'Question à choix unique avec une seule bonne réponse', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(3, 'Vrai/Faux', 'vrai_faux', 'Question nécessitant une réponse vrai ou faux', 1, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(4, 'Texte Libre', 'texte_libre', 'Réponse rédigée nécessitant une correction manuelle', 0, '2025-11-13 13:23:29', '2025-11-13 13:23:29'),
(5, 'Code', 'code', 'Question nécessitant d\'écrire du code', 0, '2025-11-13 13:23:30', '2025-11-13 13:23:30');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `doit_changer_mot_de_passe` tinyint(1) NOT NULL DEFAULT 0,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `matricule` varchar(255) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `genre` enum('homme','femme','autre') DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `pays` varchar(255) NOT NULL DEFAULT 'France',
  `contact_urgence_nom` varchar(255) DEFAULT NULL,
  `contact_urgence_lien` varchar(255) DEFAULT NULL,
  `contact_urgence_telephone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `statut` enum('actif','inactif','suspendu') NOT NULL DEFAULT 'actif',
  `cree_par` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `email_verified_at`, `password`, `doit_changer_mot_de_passe`, `role_id`, `telephone`, `matricule`, `date_naissance`, `genre`, `adresse`, `ville`, `code_postal`, `pays`, `contact_urgence_nom`, `contact_urgence_lien`, `contact_urgence_telephone`, `photo`, `statut`, `cree_par`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SUPER', 'Admin', 'superadmin@iris.fr', '2025-11-13 13:23:23', '$2y$12$n0NpRYcd1fHqtRMU05NzR.Ti7jXPyBHJIFLqY8WydrX1UZgF9s.0e', 0, 1, '+33 1 23 45 67 89', 'SA-001', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', NULL, 'KICUHUOWd53YpU6gHVQMaUnj5sdH71lEvQaUJQjNmlMv9h87jJZ1yU87qMgM', '2025-11-13 13:23:23', '2025-11-13 13:23:23', NULL),
(2, 'DUPONT', 'Marie', 'marie.dupont@iris.fr', '2025-11-13 13:23:23', '$2y$12$jp6bpNsezUJGOj7JfoHrROq1lGYHkeg/rRAppOQkHkrWFVOn4kRRe', 0, 2, '+33 6 12 34 56 78', 'AD-001', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 13:23:23', '2025-11-13 13:23:23', NULL),
(3, 'MARTIN', 'Pierre', 'pierre.martin@iris.fr', '2025-11-13 13:23:23', '$2y$12$NMSukJVQLKdV1CRniaadc..LppaabGgOhbZKShMuTzzlOjuaBTizS', 0, 2, '+33 6 98 76 54 32', 'AD-002', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 13:23:23', '2025-11-13 13:23:23', NULL),
(4, 'BERNARD', 'Sophie', 'sophie.bernard@iris.fr', '2025-11-13 13:23:23', '$2y$12$cC5.WN0mhjctUwCXx2aS.Oi.wltwtABpLnLamq2OcT6kaH7Yux.4a', 0, 3, '+33 6 23 83 91 20', 'ENS-001', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:24', '2025-11-13 13:23:24', NULL),
(5, 'DUBOIS', 'Jean', 'jean.dubois@iris.fr', '2025-11-13 13:23:24', '$2y$12$qCbe/rLpWpZ/Z8FuZ43GwuXa9zOzGfuIVH0I/OFV3uLfn6oMiGLym', 0, 3, '+33 6 78 96 31 32', 'ENS-002', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:24', '2025-11-13 13:23:24', NULL),
(6, 'LEROY', 'Catherine', 'catherine.leroy@iris.fr', '2025-11-13 13:23:24', '$2y$12$GfMUnSu/E3pEmY1b760RH.fzbZ1Ufqdz06gjsU.xy9aaagli6fuJ6', 0, 3, '+33 6 23 95 51 24', 'ENS-003', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:24', '2025-11-13 13:23:24', NULL),
(7, 'MOREAU', 'François', 'francois.moreau@iris.fr', '2025-11-13 13:23:24', '$2y$12$N6YMHfZtjvniY2wZ6gFgquqzV.99TzxrTEctr6s29IO97kw0LH4HO', 0, 3, '+33 6 14 93 61 89', 'ENS-004', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:24', '2025-11-13 13:23:24', NULL),
(8, 'SIMON', 'Nathalie', 'nathalie.simon@iris.fr', '2025-11-13 13:23:24', '$2y$12$6IM1s6ESoYeFhw/ZGXW42e1fERiveHBxFBSt2np3uspTIkbigbL5S', 0, 3, '+33 6 64 66 15 60', 'ENS-005', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:25', '2025-11-13 13:23:25', NULL),
(9, 'LEFEBVRE', 'Lucas', 'lucas.lefebvre@etudiant.iris.fr', '2025-11-13 13:23:25', '$2y$12$BysyDGA0yWF68/ftW3JFA.ZOlinyBXpYnPt0HPG72IM.rsPapb/0i', 0, 4, '+33 6 22 90 92 63', 'ETU-001', '2006-11-13', NULL, '91 rue de Marseille', 'Marseille', '86314', 'France', NULL, NULL, NULL, NULL, 'actif', 2, 'VlCBTPNqjm2q558TPLm5aVqjoRQNWMDFArL9j1gS5dTpz5hmPT1zg2SRB9lB', '2025-11-13 13:23:25', '2025-11-13 13:23:25', NULL),
(10, 'ROUX', 'Emma', 'emma.roux@etudiant.iris.fr', '2025-11-13 13:23:25', '$2y$12$WWnk.Q5AOQ6EfdJu3U1KNuMdV8F52rxYKbPoY4c82K25IjC1PQ3PW', 0, 4, '+33 6 71 32 36 69', 'ETU-002', '2003-11-13', NULL, '176 rue de Marseille', 'Toulouse', '25886', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:25', '2025-11-13 13:23:25', NULL),
(11, 'FOURNIER', 'Hugo', 'hugo.fournier@etudiant.iris.fr', '2025-11-13 13:23:25', '$2y$12$bLQJjRh9rwhi4./c.e.03.bz7zomPDbo3xkUE3I7fjXdoRik54P7i', 0, 4, '+33 6 89 82 25 37', 'ETU-003', '2004-11-13', NULL, '194 rue de Toulouse', 'Lyon', '38840', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:25', '2025-11-13 13:23:25', NULL),
(12, 'GIRARD', 'Léa', 'lea.girard@etudiant.iris.fr', '2025-11-13 13:23:25', '$2y$12$..rVOSsJbKvVj0WqwIconu8TTECu9W/Z.mdyp65YBDDuFIVeWkvTa', 0, 4, '+33 6 78 14 21 69', 'ETU-004', '2003-11-13', NULL, '92 rue de Lyon', 'Paris', '44688', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:25', '2025-11-13 13:23:25', NULL),
(13, 'BONNET', 'Tom', 'tom.bonnet@etudiant.iris.fr', '2025-11-13 13:23:25', '$2y$12$xFYWbhQ8/PPa.bV1spH.Qu5hjNQTPXEfQvokVoBaqn5JkiK0IJYF.', 0, 4, '+33 6 19 70 23 97', 'ETU-005', '2007-11-13', NULL, '115 rue de Paris', 'Toulouse', '64185', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:26', '2025-11-13 13:23:26', NULL),
(14, 'DUPUIS', 'Chloé', 'chloe.dupuis@etudiant.iris.fr', '2025-11-13 13:23:26', '$2y$12$Sbjckq7WzawTTzbawQqGFOKtwQmB05f9ASoX7I2qDIEPr5hbSAgOe', 0, 4, '+33 6 41 24 30 84', 'ETU-006', '2007-11-13', NULL, '79 rue de Toulouse', 'Lyon', '49058', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:26', '2025-11-13 13:23:26', NULL),
(15, 'LAMBERT', 'Nathan', 'nathan.lambert@etudiant.iris.fr', '2025-11-13 13:23:26', '$2y$12$BdcUT3n3WmQ3zXjse/tFOOtQ47.ItpSkGwhHV6uMEkDXy81V1F9Na', 0, 4, '+33 6 59 26 98 21', 'ETU-007', '2007-11-13', NULL, '181 rue de Toulouse', 'Lyon', '47570', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:26', '2025-11-13 13:23:26', NULL),
(16, 'FONTAINE', 'Sarah', 'sarah.fontaine@etudiant.iris.fr', '2025-11-13 13:23:26', '$2y$12$nB43dHnM8sihI0I.t0v24uDIi1VJKGmPVoovSki22la8fo7p53oVS', 0, 4, '+33 6 44 52 23 14', 'ETU-008', '2004-11-13', NULL, '3 rue de Marseille', 'Marseille', '95781', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:26', '2025-11-13 13:23:26', NULL),
(17, 'ROUSSEAU', 'Alexandre', 'alexandre.rousseau@etudiant.iris.fr', '2025-11-13 13:23:26', '$2y$12$wNpg0Fn2SVNbokQKthbbKui5jp9SybOQH9jRJ6puF4rb/QyclWOam', 0, 4, '+33 6 87 67 46 16', 'ETU-009', '2005-11-13', NULL, '51 rue de Lyon', 'Nantes', '40152', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:27', '2025-11-13 13:23:27', NULL),
(18, 'VINCENT', 'Manon', 'manon.vincent@etudiant.iris.fr', '2025-11-13 13:23:27', '$2y$12$x7t1e.YBAEPsxu4lCJqUsevFRN5oreiGVyBmxHl3IXHFezlgJp3Wa', 0, 4, '+33 6 83 75 39 46', 'ETU-010', '2001-11-13', NULL, '47 rue de Paris', 'Nantes', '71547', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:27', '2025-11-13 13:23:27', NULL),
(19, 'GAUTHIER', 'Antoine', 'antoine.gauthier@etudiant.iris.fr', '2025-11-13 13:23:27', '$2y$12$2o1VTsFHYzqoPgX0OTHA8.havOqDpLBjHaNxYnkoy8xf0BXXePDBy', 0, 4, '+33 6 41 24 39 68', 'ETU-011', '2001-11-13', NULL, '79 rue de Marseille', 'Nantes', '43550', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:27', '2025-11-13 13:23:27', NULL),
(20, 'PERRIN', 'Julie', 'julie.perrin@etudiant.iris.fr', '2025-11-13 13:23:27', '$2y$12$btlfIJTsl1RZG.k59x1HlO/tRxiDT8ATjvMGgiBUNSeKid.bp64de', 0, 4, '+33 6 58 58 19 38', 'ETU-012', '2002-11-13', NULL, '112 rue de Marseille', 'Paris', '91668', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:27', '2025-11-13 13:23:27', NULL),
(21, 'MOREL', 'Maxime', 'maxime.morel@etudiant.iris.fr', '2025-11-13 13:23:27', '$2y$12$YpS35nPJGsX0fPxlCMDPtOXytqw7KTH9Whllf6TTFk7CvWW6wQc1K', 0, 4, '+33 6 69 84 25 81', 'ETU-013', '2005-11-13', NULL, '142 rue de Toulouse', 'Toulouse', '49053', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:27', '2025-11-13 13:23:27', NULL),
(22, 'GARCIA', 'Laura', 'laura.garcia@etudiant.iris.fr', '2025-11-13 13:23:27', '$2y$12$n0z/GFCc/UNQjvHeHZ/gderYc3tJq3TcpipL33iQKkBWaWxRbwCQ.', 0, 4, '+33 6 50 98 70 99', 'ETU-014', '2001-11-13', NULL, '115 rue de Marseille', 'Marseille', '79144', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:28', '2025-11-13 13:23:28', NULL),
(23, 'DAVID', 'Thomas', 'thomas.david@etudiant.iris.fr', '2025-11-13 13:23:28', '$2y$12$xmmEXm3oU9nufIvo4eijw./7rEQ6LyxNWmLOuqDT7Rah383MEpQma', 0, 4, '+33 6 91 49 39 97', 'ETU-015', '2002-11-13', NULL, '66 rue de Marseille', 'Toulouse', '64619', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:28', '2025-11-13 13:23:28', NULL),
(24, 'BERTRAND', 'Clara', 'clara.bertrand@etudiant.iris.fr', '2025-11-13 13:23:28', '$2y$12$jbTH7Qcg3IpLOcX9jO1dQ.T7LK/t7w/udKy3SoMmwCFdxCTRCW1Wm', 0, 4, '+33 6 26 19 39 35', 'ETU-016', '2004-11-13', NULL, '57 rue de Lyon', 'Marseille', '84782', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:28', '2025-11-13 13:23:28', NULL),
(25, 'CHEVALIER', 'Julien', 'julien.chevalier@etudiant.iris.fr', '2025-11-13 13:23:28', '$2y$12$2fF2hc9yEyv1ranTZf/f7OSvlwKGH.9sKp3tFQjWy6tPkT05nAb4S', 0, 4, '+33 6 75 39 75 54', 'ETU-017', '2001-11-13', NULL, '167 rue de Toulouse', 'Lyon', '39388', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:28', '2025-11-13 13:23:28', NULL),
(26, 'ROBIN', 'Camille', 'camille.robin@etudiant.iris.fr', '2025-11-13 13:23:28', '$2y$12$G.5TeoO5fMJonC7FizSoSeVXyejh6z3EeYuWiNuRuESHUmxbC0UYa', 0, 4, '+33 6 40 15 53 79', 'ETU-018', '2007-11-13', NULL, '55 rue de Paris', 'Lyon', '85318', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:28', '2025-11-13 13:23:28', NULL),
(27, 'CLEMENT', 'Nicolas', 'nicolas.clement@etudiant.iris.fr', '2025-11-13 13:23:28', '$2y$12$fhCofcFk68MMLRYOfh2GBun6YBSgfSE4l9aEdbfwMzCPJZ3dj8L7G', 0, 4, '+33 6 12 16 89 84', 'ETU-019', '2003-11-13', NULL, '23 rue de Marseille', 'Toulouse', '42408', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29', NULL),
(28, 'GUILLAUME', 'Alice', 'alice.guillaume@etudiant.iris.fr', '2025-11-13 13:23:29', '$2y$12$ss70xCf3kcJ1hM7JBj0HSe/CNkdnttJWvS/QXx6bNtlMfyPlexQ7e', 0, 4, '+33 6 30 64 83 95', 'ETU-020', '2000-11-13', NULL, '138 rue de Paris', 'Paris', '44116', 'France', NULL, NULL, NULL, NULL, 'actif', 2, NULL, '2025-11-13 13:23:29', '2025-11-13 13:23:29', NULL),
(29, 'DUPONT', 'Jean', 'jean.dupont@email.com', NULL, '$2y$12$bg8KlB/XYL0rW2MaX9PjSuy0mX19cJQvlqr1GBl/XDqhwz7XU569K', 0, 4, '69347689', 'ETU2025001', '2007-02-06', 'homme', '14 Rue saint lazare', 'paris', '12345', 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 17:49:00', '2025-11-13 17:49:00', NULL),
(30, 'MARTIN', 'Marie', 'marie.martin@email.com', NULL, '$2y$12$t7hI9/N0sQf/6lRRViX/leDtPH2GRY/RR8Gr6syKoBTBpBzcKxdES', 0, 4, '693476893', 'ETU2025002', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 17:50:53', '2025-11-13 17:50:53', NULL),
(31, 'PIERRE', 'John', 'john.pierre@email.com', NULL, '$2y$12$ZGsvba17PUxutuVDHPboveOm86jcJqwEBvwqpYuj9hUjbKLhVZv4e', 0, 4, '0123456789', 'ETU2025003', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 17:58:23', '2025-11-13 17:58:23', NULL),
(32, 'JACQUES', 'Ikbal', 'ikbal.jacques@email.com', NULL, '$2y$12$uE59GAOFVqqRbM9iX5SUAuca6kntWLCFCpibo6l4h7h2KfOW0k10.', 0, 4, '0693476893', 'ETU2025004', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 17:59:51', '2025-11-13 17:59:51', NULL),
(33, 'JOSEPH', 'Bernard', 'bernard.joseph@email.com', NULL, '$2y$12$jQqzKW04/oOahbq/1l5xa.LwftW18soF174oQrWER5IWBFLtne8i.', 0, 4, NULL, 'ETU2025005', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 18:01:04', '2025-11-13 18:01:04', NULL),
(34, 'MARTINE', 'Mariette', 'mariette.martine@email.com', NULL, '$2y$12$yHTG/JJMyvjf0lPG9YqVSedoFRY9zZYzucKn5Aa1gc87Fok7FZ4Ti', 0, 4, '0693476893', 'ETU2025006', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-13 18:59:03', '2025-11-13 19:01:18', '2025-11-13 19:01:18'),
(35, 'BADIROU', 'Islaah', 'islaahbadirou@gmail.fr', NULL, '$2y$12$YsPfQrJOWHYcPJEdktiLOer4zruzJDcZ/KmD1YRmMPMV8IykNS7FS', 0, 3, '0123456789', 'ES2025012', '1988-06-21', 'homme', '8 rue de londress', 'PAaris', '13456', 'France', NULL, NULL, NULL, NULL, 'actif', 1, '3LSwAVRq9CKyhtxczbBChCD6pSDRf9A23R4OmJHnCzYNrtqZd0ox8Gi6KWN4', '2025-11-13 20:13:38', '2025-11-13 20:13:38', NULL),
(36, 'ASSANE', 'Fadil', 'fadilassane700@gmail.com', NULL, '$2y$12$3rojrFh1SCdj46n55KbCW.k31we78AbqSd7Aor4J8aaE2//F9912W', 0, 4, '0641014031', 'ETU2025008', '2006-06-06', 'homme', '12 Rue Jules Guesde', 'Montrouge', '92120', 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-20 14:49:52', '2025-11-20 14:49:52', NULL),
(37, 'RICARDO', 'Billy', 'ricardobilly5422@gmail.com', NULL, '$2y$12$h37y9prBDQ2xlegtU8/tO.XoNNlegRh1xw0gr0g2Yx9BnCRJp97mG', 0, 4, '0169258689', 'ETU2025009', '2006-06-06', NULL, '12 Rue Jules Guesde', 'Montrouge', '92134', 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-21 08:35:26', '2025-11-21 08:35:26', NULL),
(38, 'DUPONT', 'Mellisa', 'mellisa13856@gmail.com', NULL, '$2y$12$Kd3BgKSQpA.8Yulx/D/W3.cb4V4ombd/as9sD8OCF2WHc8Cnk278a', 0, 4, '0169258688', 'ETU2025010', '2004-02-04', 'femme', '12 Rue Jules Guesde', 'Montrouge', '12343', 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-11-21 10:51:54', '2025-11-21 10:51:54', NULL),
(39, 'ASSANE', 'Fadil', 'fadilassane666@gmail.com', NULL, '$2y$12$MVeclLkWKl.HxLqYQB1zJ.aHeXaDgDUUrwTpk.ayDh3WdG/.ocAGC', 0, 4, '69258689', 'ETU06345', '1999-03-01', 'homme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-12-17 08:50:44', '2025-12-17 10:45:36', NULL),
(40, 'Test', 'User', 'test@test.com', NULL, '$2y$12$hpu9nCCJJZSI/wfepK28lu8760LoOU1t8hu0Cd4rmMxVAwEtQsBCG', 0, 3, NULL, 'TEST001', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', NULL, NULL, '2025-12-17 18:22:46', '2025-12-17 18:55:04', NULL),
(41, 'JACK', 'John', 'john@gmail.com', NULL, '$2y$12$pDv7mIVNMlj57rEPjRDwMu0boAT3BXrtXy1jwb5myzMg3iFcfiFUS', 0, 4, NULL, 'ETU25609', '2025-12-17', 'homme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2025-12-17 19:02:20', '2025-12-17 19:14:46', NULL),
(42, 'ASSANE', 'Fadil', 'fadilassane55@gmail.com', NULL, '$2y$12$IrIMoo4LhEMhyLrPhtFVZOu192Vhsn/H44Zoe0us68.ImHaI7cFEi', 0, 4, NULL, 'ETU43566', '2003-02-08', 'homme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, 'n6m1dBCWRAcHK5rEhbKQHe0UnYxqJIy7HLTRLsZoNH9I0unAVgE8FjzUuJd9', '2026-02-08 09:34:50', '2026-02-08 09:37:18', NULL),
(43, 'DIDI', 'Ass', 'jeanpeter89082@gmail.com', NULL, '$2y$12$4.qrMC9Vz.0G4Agtb52xTer78EAGvO3D4v0t5yieIahiQ.d0CnTQu', 1, 4, NULL, 'ETU-2026-001', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2026-02-08 09:57:25', '2026-02-08 09:57:25', NULL),
(44, 'MOHAMED', 'Ahamada', 'med07ahama@gmail.com', NULL, '$2y$12$0EcYMvGfpwNCPuRjKh6Ixu..mejz1m.T7YzwmLHyK8NECDBgwhHGC', 0, 4, NULL, 'ETU-2026-002', NULL, 'homme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, 'UkXUtvJZS2IEs2aBvDg8ZsMiLt2VGAlZ85AN5Yo5YuXygGZQhIbyDMKMMFMq', '2026-02-09 08:54:03', '2026-02-12 15:45:18', NULL),
(45, 'MONKOTAN', 'Darlen', 'mahugnonmonkotan@gmail.com', NULL, '$2y$12$2p5s6Tt7sYSbavJvzcf0AO2zYVZTEFx8r97YSdR8PcHRm0rm7nehq', 0, 3, NULL, 'ENS-2026-001', NULL, 'femme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, 'kcv1pQ3ofuvFMWJIRSIdqduTpOUIsW7v6DXpwmRRKFA7MbLVc2G4i19C0Hd3', '2026-02-09 09:55:32', '2026-02-09 11:44:42', NULL),
(50, 'MONKOTAN', 'Darlene', 'mahugnonmonkotan+1@gmail.com', NULL, '$2y$12$HQ29jX2MzJN1X99JQ.TxGOYbkNLTcjJBLBXwCmgPQ/Wf3Qpw8/NpG', 1, 3, NULL, 'ENS-2026-481', NULL, 'femme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2026-02-09 10:21:44', '2026-02-09 10:48:09', '2026-02-09 10:48:09'),
(51, 'MONKOTAN', 'Darlene', 'mahugnonmonkotan+2@gmail.com', NULL, '$2y$12$Vwp6WoAxJ1M9eDQHXGlxBuRtr0XGDuoX9Zy6ajuXFbHLQ4b03w412', 1, 3, NULL, 'ENS-2026-136', NULL, 'femme', NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2026-02-09 10:47:54', '2026-02-09 10:48:14', '2026-02-09 10:48:14'),
(52, 'BADA', 'Jean', 'fadilassane06@gmail.com', NULL, '$2y$12$faZ3AC1nUq5cod.D7JWc/OTkFn9YeZXWA4Fu1DmWhBiX.Lln8kkdG', 0, 3, NULL, 'ENS-2026-315', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2026-03-09 19:41:03', '2026-03-09 19:43:16', NULL),
(53, 'Fadil', 'DIDI', 'fadilassane06+1@gmail.com', NULL, '$2y$12$WKn.VQ4gSF5ciBDTOZjCgeidJvnEBxu9eN6W/TGe3sprkjfPlx/oS', 0, 4, NULL, 'ETU-2026-247', NULL, NULL, NULL, NULL, NULL, 'France', NULL, NULL, NULL, NULL, 'actif', 1, NULL, '2026-03-09 19:48:54', '2026-03-09 19:50:29', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `banque_questions`
--
ALTER TABLE `banque_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banque_questions_enseignant_id_foreign` (`enseignant_id`),
  ADD KEY `banque_questions_matiere_id_foreign` (`matiere_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classes_code_unique` (`code`),
  ADD KEY `classes_cree_par_foreign` (`cree_par`);

--
-- Index pour la table `classe_etudiant`
--
ALTER TABLE `classe_etudiant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classe_etudiant_classe_id_etudiant_id_unique` (`classe_id`,`etudiant_id`),
  ADD KEY `classe_etudiant_etudiant_id_foreign` (`etudiant_id`),
  ADD KEY `classe_etudiant_inscrit_par_foreign` (`inscrit_par`),
  ADD KEY `classe_etudiant_desinscrit_par_foreign` (`desinscrit_par`),
  ADD KEY `classe_etudiant_classe_id_regime_statut_index` (`classe_id`,`regime`,`statut`);

--
-- Index pour la table `copies_etudiants`
--
ALTER TABLE `copies_etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `copies_etudiants_session_examen_id_foreign` (`session_examen_id`),
  ADD KEY `copies_etudiants_etudiant_id_foreign` (`etudiant_id`),
  ADD KEY `copies_etudiants_correcteur_id_foreign` (`correcteur_id`),
  ADD KEY `copies_etudiants_examen_id_etudiant_id_index` (`examen_id`,`etudiant_id`),
  ADD KEY `copies_etudiants_statut_index` (`statut`);

--
-- Index pour la table `critere_corrections`
--
ALTER TABLE `critere_corrections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `critere_corrections_question_id_foreign` (`question_id`);

--
-- Index pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enseignants_statut_index` (`statut`),
  ADD KEY `enseignants_utilisateur_id_index` (`utilisateur_id`);

--
-- Index pour la table `enseignant_classe`
--
ALTER TABLE `enseignant_classe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enseignant_classe_enseignant_id_classe_id_matiere_id_unique` (`enseignant_id`,`classe_id`,`matiere_id`),
  ADD KEY `enseignant_classe_classe_id_foreign` (`classe_id`),
  ADD KEY `enseignant_classe_matiere_id_foreign` (`matiere_id`),
  ADD KEY `enseignant_classe_affecte_par_foreign` (`affecte_par`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `etudiants_matricule_unique` (`matricule`),
  ADD KEY `etudiants_matricule_index` (`matricule`),
  ADD KEY `etudiants_classe_id_index` (`classe_id`),
  ADD KEY `etudiants_statut_index` (`statut`),
  ADD KEY `etudiants_utilisateur_id_index` (`utilisateur_id`);

--
-- Index pour la table `examens`
--
ALTER TABLE `examens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examens_enseignant_id_foreign` (`enseignant_id`),
  ADD KEY `examens_matiere_id_foreign` (`matiere_id`),
  ADD KEY `examens_classe_id_foreign` (`classe_id`);

--
-- Index pour la table `examen_question`
--
ALTER TABLE `examen_question`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `examen_question_examen_id_question_id_unique` (`examen_id`,`question_id`),
  ADD KEY `examen_question_question_id_foreign` (`question_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `logs_activite`
--
ALTER TABLE `logs_activite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_activite_utilisateur_id_created_at_index` (`utilisateur_id`,`created_at`),
  ADD KEY `logs_activite_module_action_index` (`module`,`action`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matieres_code_unique` (`code`),
  ADD KEY `matieres_cree_par_foreign` (`cree_par`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_utilisateur_id_foreign` (`utilisateur_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_nom_unique` (`nom`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_createur_id_foreign` (`createur_id`),
  ADD KEY `questions_matiere_id_index` (`matiere_id`),
  ADD KEY `questions_type_index` (`type`),
  ADD KEY `questions_difficulte_index` (`difficulte`),
  ADD KEY `questions_est_active_index` (`est_active`);

--
-- Index pour la table `rappels`
--
ALTER TABLE `rappels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rappels_examen_id_foreign` (`examen_id`),
  ADD KEY `rappels_etudiant_id_examen_id_envoye_index` (`etudiant_id`,`examen_id`,`envoye`);

--
-- Index pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reponses_question_id_index` (`question_id`);

--
-- Index pour la table `reponses_etudiant`
--
ALTER TABLE `reponses_etudiant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reponses_etudiant_session_examen_id_question_id_unique` (`session_examen_id`,`question_id`),
  ADD KEY `reponses_etudiant_question_id_foreign` (`question_id`),
  ADD KEY `reponses_etudiant_corrige_par_foreign` (`corrige_par`),
  ADD KEY `reponses_etudiant_session_id_index` (`session_id`);

--
-- Index pour la table `reponse_critere`
--
ALTER TABLE `reponse_critere`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reponse_critere_reponse_etudiant_id_foreign` (`reponse_etudiant_id`),
  ADD KEY `reponse_critere_critere_id_foreign` (`critere_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nom_unique` (`nom`);

--
-- Index pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `sessions_examen`
--
ALTER TABLE `sessions_examen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sessions_examen_examen_id_etudiant_id_numero_tentative_unique` (`examen_id`,`etudiant_id`,`numero_tentative`),
  ADD KEY `sessions_examen_etudiant_id_foreign` (`etudiant_id`),
  ADD KEY `idx_statut_correction` (`statut_correction`),
  ADD KEY `sessions_examen_decision_par_foreign` (`decision_par`);

--
-- Index pour la table `types_question`
--
ALTER TABLE `types_question`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `types_question_code_unique` (`code`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utilisateurs_email_unique` (`email`),
  ADD UNIQUE KEY `utilisateurs_matricule_unique` (`matricule`),
  ADD KEY `utilisateurs_role_id_foreign` (`role_id`),
  ADD KEY `utilisateurs_cree_par_foreign` (`cree_par`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `banque_questions`
--
ALTER TABLE `banque_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `classe_etudiant`
--
ALTER TABLE `classe_etudiant`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `copies_etudiants`
--
ALTER TABLE `copies_etudiants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `critere_corrections`
--
ALTER TABLE `critere_corrections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `enseignant_classe`
--
ALTER TABLE `enseignant_classe`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `examens`
--
ALTER TABLE `examens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `examen_question`
--
ALTER TABLE `examen_question`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `logs_activite`
--
ALTER TABLE `logs_activite`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `rappels`
--
ALTER TABLE `rappels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reponses`
--
ALTER TABLE `reponses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `reponses_etudiant`
--
ALTER TABLE `reponses_etudiant`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `reponse_critere`
--
ALTER TABLE `reponse_critere`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `sessions_examen`
--
ALTER TABLE `sessions_examen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `types_question`
--
ALTER TABLE `types_question`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `banque_questions`
--
ALTER TABLE `banque_questions`
  ADD CONSTRAINT `banque_questions_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `banque_questions_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `classe_etudiant`
--
ALTER TABLE `classe_etudiant`
  ADD CONSTRAINT `classe_etudiant_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classe_etudiant_desinscrit_par_foreign` FOREIGN KEY (`desinscrit_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `classe_etudiant_etudiant_id_foreign` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classe_etudiant_inscrit_par_foreign` FOREIGN KEY (`inscrit_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `copies_etudiants`
--
ALTER TABLE `copies_etudiants`
  ADD CONSTRAINT `copies_etudiants_correcteur_id_foreign` FOREIGN KEY (`correcteur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `copies_etudiants_etudiant_id_foreign` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `copies_etudiants_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `copies_etudiants_session_examen_id_foreign` FOREIGN KEY (`session_examen_id`) REFERENCES `sessions_examen` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `critere_corrections`
--
ALTER TABLE `critere_corrections`
  ADD CONSTRAINT `critere_corrections_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `banque_questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD CONSTRAINT `enseignants_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `enseignant_classe`
--
ALTER TABLE `enseignant_classe`
  ADD CONSTRAINT `enseignant_classe_affecte_par_foreign` FOREIGN KEY (`affecte_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `enseignant_classe_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enseignant_classe_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enseignant_classe_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `etudiants_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `etudiants_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `examens`
--
ALTER TABLE `examens`
  ADD CONSTRAINT `examens_classe_id_foreign` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `examens_enseignant_id_foreign` FOREIGN KEY (`enseignant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `examens_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `examen_question`
--
ALTER TABLE `examen_question`
  ADD CONSTRAINT `examen_question_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `examen_question_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `banque_questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `logs_activite`
--
ALTER TABLE `logs_activite`
  ADD CONSTRAINT `logs_activite_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_utilisateur_id_foreign` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_createur_id_foreign` FOREIGN KEY (`createur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questions_matiere_id_foreign` FOREIGN KEY (`matiere_id`) REFERENCES `matieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rappels`
--
ALTER TABLE `rappels`
  ADD CONSTRAINT `rappels_etudiant_id_foreign` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rappels_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD CONSTRAINT `reponses_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `banque_questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponses_etudiant`
--
ALTER TABLE `reponses_etudiant`
  ADD CONSTRAINT `reponses_etudiant_corrige_par_foreign` FOREIGN KEY (`corrige_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reponses_etudiant_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `banque_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reponses_etudiant_session_examen_id_foreign` FOREIGN KEY (`session_examen_id`) REFERENCES `sessions_examen` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponse_critere`
--
ALTER TABLE `reponse_critere`
  ADD CONSTRAINT `reponse_critere_critere_id_foreign` FOREIGN KEY (`critere_id`) REFERENCES `critere_corrections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reponse_critere_reponse_etudiant_id_foreign` FOREIGN KEY (`reponse_etudiant_id`) REFERENCES `reponses_etudiant` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sessions_examen`
--
ALTER TABLE `sessions_examen`
  ADD CONSTRAINT `sessions_examen_decision_par_foreign` FOREIGN KEY (`decision_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sessions_examen_etudiant_id_foreign` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_examen_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_cree_par_foreign` FOREIGN KEY (`cree_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `utilisateurs_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
