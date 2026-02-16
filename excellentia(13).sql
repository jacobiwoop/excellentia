-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 30 juin 2025 à 16:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `excellentia`
--

-- --------------------------------------------------------

--
-- Structure de la table `assignations`
--

CREATE TABLE `assignations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formateur_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `filiere_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `assignations`
--

INSERT INTO `assignations` (`id`, `formateur_id`, `subject_id`, `filiere_id`, `site_id`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 2, 2, '2025-06-04 11:36:35', '2025-06-04 11:36:35'),
(3, 6, 2, 6, 1, '2025-06-12 14:14:49', '2025-06-12 14:14:49'),
(4, 5, 4, 2, 2, '2025-06-13 07:17:35', '2025-06-20 08:08:07');

-- --------------------------------------------------------

--
-- Structure de la table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0=Absent, 1=Présent, 2=Justifié',
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `formateur_id` bigint(20) UNSIGNED NOT NULL,
  `marked_by_role` enum('formateur','admin_site','super_admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `attendances`
--

INSERT INTO `attendances` (`id`, `date`, `status`, `student_id`, `formateur_id`, `marked_by_role`, `created_at`, `updated_at`) VALUES
(1, '2025-06-24', 1, 5, 5, 'formateur', '2025-06-24 13:45:13', '2025-06-24 15:14:01'),
(2, '2025-06-24', 0, 17, 5, 'formateur', '2025-06-24 13:45:13', '2025-06-24 15:30:22'),
(3, '2025-06-25', 1, 5, 5, 'formateur', '2025-06-25 15:41:57', '2025-06-25 15:42:55'),
(4, '2025-06-25', 2, 17, 5, 'formateur', '2025-06-25 15:41:57', '2025-06-25 15:41:57');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignation_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('interro','devoir','compo') NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filieres`
--

CREATE TABLE `filieres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `filieres`
--

INSERT INTO `filieres` (`id`, `nom`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Comptable', 'ADC', '2025-05-28 13:50:22', '2025-06-02 10:17:58'),
(2, 'Commerciale', 'ADCO', '2025-05-28 13:50:49', '2025-06-02 10:20:01'),
(3, 'Juridique', 'ADJ', '2025-05-28 13:50:57', '2025-06-02 10:20:11'),
(4, 'Médicale', 'ADM', '2025-05-28 13:51:08', '2025-06-02 10:20:19'),
(5, 'Graphisme', 'ADG', '2025-05-28 13:51:20', '2025-06-02 10:20:28'),
(6, 'Bilingue', 'ADB', '2025-05-28 13:51:32', '2025-06-02 10:20:52'),
(7, 'Ressource Humaine', 'ADRH', '2025-05-28 13:51:42', '2025-06-02 10:21:15'),
(8, 'Chargé de communication', 'CDC', '2025-05-28 13:51:57', '2025-06-02 10:21:40'),
(9, 'Tronc Commun', 'CC', '2025-06-15 08:51:23', '2025-06-15 08:51:23');

-- --------------------------------------------------------

--
-- Structure de la table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `filiere_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `formateur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assignation_id` bigint(20) UNSIGNED NOT NULL,
  `interro1` decimal(5,2) DEFAULT NULL,
  `interro2` decimal(5,2) DEFAULT NULL,
  `interro3` decimal(5,2) DEFAULT NULL,
  `devoir` decimal(5,2) DEFAULT NULL,
  `composition` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `moy_interro` decimal(4,2) DEFAULT NULL,
  `moy_continue` decimal(4,2) DEFAULT NULL,
  `moy_finale` decimal(4,2) DEFAULT NULL,
  `term` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `filiere_id`, `site_id`, `formateur_id`, `assignation_id`, `interro1`, `interro2`, `interro3`, `devoir`, `composition`, `created_at`, `updated_at`, `moy_interro`, `moy_continue`, `moy_finale`, `term`) VALUES
(4, 5, NULL, NULL, NULL, NULL, 1, 12.00, 18.00, 12.00, 12.00, 14.00, '2025-06-12 11:17:20', '2025-06-13 10:56:41', 14.00, 13.00, 13.50, NULL),
(14, 17, NULL, NULL, NULL, NULL, 1, 14.00, 15.00, 13.00, 15.00, 14.00, '2025-06-12 15:29:10', '2025-06-12 15:36:57', 14.00, 14.50, 14.25, NULL),
(15, 5, NULL, NULL, NULL, NULL, 4, 15.00, 13.00, NULL, NULL, NULL, '2025-06-13 08:34:22', '2025-06-13 08:34:22', 14.00, NULL, NULL, NULL),
(29, 5, NULL, NULL, NULL, NULL, 1, 12.00, 16.00, 15.00, NULL, NULL, '2025-06-13 14:37:52', '2025-06-13 16:26:35', 14.33, 14.33, 11.00, '1'),
(30, 17, NULL, NULL, NULL, NULL, 1, 18.00, 14.00, 14.00, NULL, NULL, '2025-06-13 14:37:52', '2025-06-13 16:26:35', 15.33, 15.33, 12.50, '1'),
(31, 5, NULL, NULL, NULL, NULL, 1, 10.00, 12.00, NULL, NULL, NULL, '2025-06-13 14:59:24', '2025-06-13 16:26:35', 11.00, 11.00, 10.00, '2'),
(32, 17, NULL, NULL, NULL, NULL, 1, 10.00, 15.00, NULL, NULL, NULL, '2025-06-13 14:59:24', '2025-06-13 16:26:35', 12.50, 12.50, 10.00, '2'),
(33, 5, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-06-13 15:40:25', '2025-06-13 15:40:25', NULL, NULL, NULL, '3'),
(34, 17, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, '2025-06-13 15:40:25', '2025-06-13 15:40:25', NULL, NULL, NULL, '3'),
(35, 5, NULL, NULL, NULL, NULL, 4, 14.00, NULL, NULL, NULL, NULL, '2025-06-14 18:29:47', '2025-06-14 18:29:47', 14.00, 14.00, 14.00, '1'),
(36, 5, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-06-14 18:29:47', '2025-06-14 18:29:47', NULL, NULL, NULL, '2'),
(37, 5, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-06-14 18:29:47', '2025-06-14 18:29:47', NULL, NULL, NULL, '3'),
(38, 17, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-06-14 18:29:47', '2025-06-14 18:29:47', NULL, NULL, NULL, '1'),
(39, 17, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-06-14 18:29:47', '2025-06-14 18:29:47', NULL, NULL, NULL, '2'),
(40, 17, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-06-14 18:29:47', '2025-06-14 18:29:47', NULL, NULL, NULL, '3'),
(41, 16, NULL, NULL, NULL, NULL, 3, 14.00, 15.00, NULL, NULL, NULL, '2025-06-20 08:05:07', '2025-06-20 08:05:07', 14.50, 14.50, 14.50, '1'),
(42, 16, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, '2025-06-20 08:05:07', '2025-06-20 08:05:07', NULL, NULL, NULL, '2'),
(43, 16, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, '2025-06-20 08:05:07', '2025-06-20 08:05:07', NULL, NULL, NULL, '3');

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
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2025_05_27_150851_create_sites_table', 1),
(3, '2025_05_27_150926_create_users_table', 1),
(4, '2025_05_28_143944_create_filieres_table', 2),
(5, '2025_05_28_145813_create_promotions_table', 3),
(6, '2025_05_28_153829_create_students_table', 4),
(7, '2025_05_30_085632_add_code_to_filieres_table', 5),
(8, '2025_06_04_101317_create_subjects_table', 6),
(9, '2025_06_04_101413_create_assignations_table', 6),
(10, '2025_06_04_101439_create_grades_table', 6),
(11, '2025_06_04_122003_add_filiere_id_to_subjects_table', 7),
(13, '2025_06_04_142202_create_evaluations_table', 8);

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `programmes`
--

CREATE TABLE `programmes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_seance` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `titre_custom` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `formateur_id` bigint(20) UNSIGNED NOT NULL,
  `filiere_id` bigint(20) UNSIGNED NOT NULL,
  `recurrence` enum('ponctuel','hebdomadaire','mensuel') NOT NULL,
  `date_fin_recurrence` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `programmes`
--

INSERT INTO `programmes` (`id`, `date_seance`, `heure_debut`, `heure_fin`, `subject_id`, `titre_custom`, `description`, `formateur_id`, `filiere_id`, `recurrence`, `date_fin_recurrence`, `created_at`, `updated_at`) VALUES
(1, '2025-06-19', '14:00:00', '16:00:00', 5, NULL, NULL, 5, 6, 'ponctuel', NULL, '2025-06-18 09:16:58', '2025-06-19 09:01:34'),
(2, '2025-06-19', '11:00:00', '13:00:00', 2, 'Ethique', NULL, 6, 2, 'ponctuel', NULL, '2025-06-18 09:23:46', '2025-06-19 06:52:39');

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotions`
--

INSERT INTO `promotions` (`id`, `nom`, `date_debut`, `date_fin`, `created_at`, `updated_at`) VALUES
(1, 'Septembre 2025', '2025-05-23', '2025-06-09', '2025-05-28 14:15:22', '2025-06-02 15:15:13');

-- --------------------------------------------------------

--
-- Structure de la table `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sites`
--

INSERT INTO `sites` (`id`, `nom`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Akpakpa Excellentia', 'AKP', '2025-05-27 15:10:44', '2025-05-27 15:14:17'),
(2, 'Calavi Excellentia', 'CAL', '2025-05-27 15:17:37', '2025-05-27 15:17:37'),
(3, 'Porto-Novo Excellentia', 'PRT', '2025-05-27 15:18:01', '2025-05-27 15:18:01');

-- --------------------------------------------------------

--
-- Structure de la table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom_prenom` varchar(255) NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sexe` enum('M','F') NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `filiere_id` bigint(20) UNSIGNED NOT NULL,
  `promotion_id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `students`
--

INSERT INTO `students` (`id`, `nom_prenom`, `matricule`, `telephone`, `email`, `sexe`, `site_id`, `filiere_id`, `promotion_id`, `photo`, `created_at`, `updated_at`) VALUES
(5, 'Max Welle', 'COM-8434-CAL', '54184194', 'max@example.com', 'M', 2, 2, 1, 'students_photos/l6MEgEMo3FL7XGULkYP2avcilbWqCZ8PmPvHBgub.jpg', '2025-05-30 14:08:46', '2025-06-12 14:52:25'),
(7, 'Bea Trix', 'ADM-4362-PRT', '+229 0167138644', 'bea@gmail.com', 'F', 3, 4, 1, 'students_photos/TabxjXpKF7rrGqHV4meR6UTMwDgcz0eoowVJdGju.jpg', '2025-06-02 10:30:45', '2025-06-02 10:30:45'),
(15, 'Fran Vk', 'ADM-8718-AKP', '54152552', 'fr@example.com', 'M', 1, 4, 1, 'students_photos/kRx7WWvHn4MGqPjjJrJdat9FrdpKyuDGRI3JeApQ.jpg', '2025-06-12 08:56:27', '2025-06-12 08:56:27'),
(16, 'Zack Hen', 'ADJ-8495-AKP', '54152552', 'hen@gmail.com', 'M', 1, 6, 1, 'students_photos/ZwnHxPBkJqXTbJM7gnKziiW8RAeO9aZAi9W7yk0m.jpg', '2025-06-12 13:46:22', '2025-06-12 14:14:10'),
(17, 'Sara Gan', 'ADCO-8908-CAL', '41586333', 'gan@example.com', 'F', 2, 2, 1, 'students_photos/xyviAhX5BUbhxLAToSFa98HcEfrv838S5TA0wFef.jpg', '2025-06-12 14:54:52', '2025-06-12 14:54:52'),
(18, 'Ben Bu', 'CC-5848-CAL', '545541454', 'bu@example.com', 'M', 2, 9, 1, 'students_photos/0KOtBvo0CivGEKrphnWBKS3zFGQtWQN4fP10HGnm.png', '2025-06-26 13:22:25', '2025-06-26 13:22:25');

-- --------------------------------------------------------

--
-- Structure de la table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `filiere_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `subjects`
--

INSERT INTO `subjects` (`id`, `nom`, `created_at`, `updated_at`, `filiere_id`) VALUES
(1, 'Techniques de vente', '2025-06-04 11:31:29', '2025-06-04 11:31:29', 2),
(2, 'Anglais professionnel', '2025-06-12 14:13:43', '2025-06-12 14:13:43', 6),
(3, 'Gestion de la relation client', '2025-06-13 07:12:32', '2025-06-13 07:12:32', 2),
(4, 'Rédaction Commerciale', '2025-06-13 07:12:52', '2025-06-13 07:12:52', 2),
(5, 'Terminologie médicale', '2025-06-13 07:13:26', '2025-06-13 07:13:26', 4),
(6, 'Gestion des dossiers patients', '2025-06-13 07:13:56', '2025-06-13 07:13:56', 4),
(7, 'Ethique et Confidentialité', '2025-06-13 07:14:18', '2025-06-13 07:14:18', 4),
(8, 'Organisation et Gestion Administrative', '2025-06-15 08:52:15', '2025-06-15 08:52:15', 9),
(9, 'Communication Professionnelle', '2025-06-15 08:52:37', '2025-06-15 08:52:37', 9),
(10, 'Bureautique et outils numériques', '2025-06-15 08:53:05', '2025-06-15 08:53:05', 9),
(11, 'Rédaction Administrative', '2025-06-15 08:53:29', '2025-06-15 08:53:29', 9),
(12, 'Techniques d\'accueil et relations professionnelles', '2025-06-15 08:53:55', '2025-06-15 08:53:55', 9),
(13, 'Gestion des documents commerciaux', '2025-06-15 08:54:20', '2025-06-15 08:54:20', 9),
(14, 'Droit du travail appliqué', '2025-06-15 08:54:37', '2025-06-15 08:54:37', 9),
(15, 'Gestion administrative du personnel', '2025-06-15 08:55:02', '2025-06-15 08:55:02', 9),
(16, 'Développement personnel et Leadership', '2025-06-15 08:55:28', '2025-06-15 08:55:28', 9),
(17, 'Gestion du temps', '2025-06-15 08:55:49', '2025-06-15 08:55:49', 9),
(18, 'Comptabilité Générale', '2025-06-15 09:09:22', '2025-06-15 09:09:22', 1),
(19, 'Trésorerie et Fiscalité', '2025-06-15 09:09:39', '2025-06-15 09:09:39', 1),
(20, 'Administration du personnel', '2025-06-15 09:09:59', '2025-06-15 09:09:59', 7),
(21, 'Recrutement et Formation', '2025-06-15 09:10:18', '2025-06-15 09:10:18', 7),
(22, 'Relations sociales et gestion des conflits', '2025-06-15 09:10:39', '2025-06-15 09:10:39', 7),
(23, 'Droit des affaires', '2025-06-15 09:11:58', '2025-06-15 09:11:58', 3),
(24, 'Gestion des dossiers juridiques', '2025-06-15 09:12:24', '2025-06-15 09:12:24', 3),
(25, 'Rédaction Juridique', '2025-06-15 09:12:44', '2025-06-15 09:12:44', 3),
(26, 'Outils de PAO', '2025-06-15 09:13:16', '2025-06-15 09:13:16', 5),
(27, 'Création de supports de communication', '2025-06-15 09:13:40', '2025-06-15 09:13:40', 5),
(28, 'Marketing digital et branding', '2025-06-15 09:14:01', '2025-06-15 09:14:01', 5);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `matricule` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin_site','formateur','etudiant') NOT NULL DEFAULT 'etudiant',
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `matricule`, `email_verified_at`, `password`, `role`, `site_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@example.com', NULL, NULL, '$2y$10$Xep6P/pZAsjKze7HXQNBiOvvgMk7SGmqLSDQ7rXHXDYPIHNRMibZu', 'super_admin', NULL, NULL, '2025-05-27 14:30:49', '2025-05-27 14:30:49'),
(2, 'Excellentia Calavi', 'calavi@example.com', NULL, NULL, '$2y$10$tTskXPVjo2tD3Pu/h12dleTajs8ZP5jO/tp25FGXnS.cdrFOJkBv.', 'admin_site', 2, NULL, '2025-05-27 14:59:43', '2025-05-30 14:04:03'),
(3, 'Excellentia Akpakpa', 'akpakpa@example.com', NULL, NULL, '$2y$10$.IQ.WO8Z8PRrLE9flUIST.bPXOzEYYop.r8iCPiKl27kS.NwsrZ52', 'admin_site', 1, NULL, '2025-05-27 15:01:01', '2025-06-23 13:12:33'),
(4, 'Excellentia Porto-Novo', 'porto@example.com', NULL, NULL, '$2y$10$CG.xX21lu4Pa7ycun2uQH.pZBUuRhACem/iQADhy15QIK5ZY7B6G6', 'admin_site', 3, NULL, '2025-05-27 15:01:34', '2025-06-12 14:07:15'),
(5, 'Ru Bèn', 'ru@gmail.com', NULL, NULL, '$2y$10$PLhUJ9MAuVxiLUk2/a.pIO68DZSx/muxX5Cg0UxZlNv40ucIjjKI6', 'formateur', 2, NULL, '2025-06-02 09:39:10', '2025-06-19 14:53:20'),
(6, 'Frank Ga', 'ga@example.com', NULL, NULL, '$2y$10$8U94Vri4kiEvMeqoGS6kSeyfNC1Q2BB9sy./OmO7DeSmoi18I3cNq', 'formateur', 1, NULL, '2025-06-12 13:33:39', '2025-06-12 13:33:39');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `assignations`
--
ALTER TABLE `assignations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignations_formateur_id_foreign` (`formateur_id`),
  ADD KEY `assignations_subject_id_foreign` (`subject_id`),
  ADD KEY `assignations_filiere_id_foreign` (`filiere_id`),
  ADD KEY `assignations_site_id_foreign` (`site_id`);

--
-- Index pour la table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`date`,`student_id`,`formateur_id`),
  ADD KEY `date_index` (`date`),
  ADD KEY `fk_student` (`student_id`),
  ADD KEY `fk_user` (`formateur_id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_assignation_id_foreign` (`assignation_id`);

--
-- Index pour la table `filieres`
--
ALTER TABLE `filieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `filieres_nom_unique` (`nom`);

--
-- Index pour la table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grades_assignation_id_foreign` (`assignation_id`),
  ADD KEY `grades_student_id_foreign` (`student_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `formateur_id` (`formateur_id`),
  ADD KEY `filiere_id` (`filiere_id`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sites_code_unique` (`code`);

--
-- Index pour la table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_matricule_unique` (`matricule`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD KEY `students_site_id_foreign` (`site_id`),
  ADD KEY `students_filiere_id_foreign` (`filiere_id`),
  ADD KEY `students_promotion_id_foreign` (`promotion_id`);

--
-- Index pour la table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjects_filiere_id_foreign` (`filiere_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD KEY `users_site_id_foreign` (`site_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `assignations`
--
ALTER TABLE `assignations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `filieres`
--
ALTER TABLE `filieres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `assignations`
--
ALTER TABLE `assignations`
  ADD CONSTRAINT `assignations_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignations_formateur_id_foreign` FOREIGN KEY (`formateur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignations_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignations_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`formateur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_assignation_id_foreign` FOREIGN KEY (`assignation_id`) REFERENCES `assignations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_assignation_id_foreign` FOREIGN KEY (`assignation_id`) REFERENCES `assignations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `programmes_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `programmes_ibfk_2` FOREIGN KEY (`formateur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `programmes_ibfk_3` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_filiere_id_foreign` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
