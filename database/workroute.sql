-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jul 2026 pada 11.16
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `workroute`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group_chat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `is_group` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `group_chat_id`, `message`, `is_group`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 'halo', 0, '2026-07-06 00:27:55', '2026-07-06 00:27:55'),
(2, 1, 2, NULL, 'halo', 0, '2026-07-06 00:27:58', '2026-07-06 00:27:58'),
(3, 1, 2, NULL, 'halo', 0, '2026-07-06 00:27:59', '2026-07-06 00:27:59'),
(4, 1, 2, NULL, 'halo', 0, '2026-07-06 00:28:01', '2026-07-06 00:28:01'),
(5, 1, 2, NULL, 'halo', 0, '2026-07-06 00:28:02', '2026-07-06 00:28:02'),
(6, 1, 2, NULL, 'halo', 0, '2026-07-06 00:28:04', '2026-07-06 00:28:04'),
(9, 1, NULL, NULL, 'halo guys', 1, '2026-07-06 00:31:05', '2026-07-06 00:31:05'),
(10, 2, 1, NULL, 'hola', 0, '2026-07-06 00:43:03', '2026-07-06 00:43:03'),
(11, 2, NULL, NULL, 'hili giys', 1, '2026-07-06 00:43:18', '2026-07-06 00:43:18'),
(12, 1, NULL, NULL, 'halo gays', 1, '2026-07-06 01:18:17', '2026-07-06 01:18:17'),
(13, 1, NULL, 1, 'hili giys', 1, '2026-07-09 20:16:36', '2026-07-09 20:16:36'),
(14, 1, NULL, 1, 'aku guy', 1, '2026-07-09 20:16:45', '2026-07-09 20:16:55'),
(15, 3, NULL, 1, 'wow ada guy', 1, '2026-07-09 20:18:29', '2026-07-09 20:18:29'),
(16, 2, NULL, 1, 'ambi', 1, '2026-07-09 20:19:19', '2026-07-09 20:19:19'),
(17, 3, NULL, 1, 'A gus Libert', 1, '2026-07-13 00:30:40', '2026-07-13 00:30:40'),
(18, 1, NULL, 1, 'amba\\', 1, '2026-07-14 00:17:36', '2026-07-14 00:17:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `group_chats`
--

CREATE TABLE `group_chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `group_chats`
--

INSERT INTO `group_chats` (`id`, `name`, `avatar`, `created_by`, `is_main`, `created_at`, `updated_at`) VALUES
(1, 'Main Group', 'group-avatars/MAHXvkwr7BOzeVGq5DttWTTQwHmVGLl19FpmYepB.png', 1, 1, '2026-07-09 20:16:21', '2026-07-14 00:18:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `group_chat_members`
--

CREATE TABLE `group_chat_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_chat_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `group_chat_members`
--

INSERT INTO `group_chat_members` (`id`, `group_chat_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-07-09 20:16:21', '2026-07-09 20:16:21'),
(2, 1, 3, '2026-07-09 20:16:21', '2026-07-09 20:16:21'),
(3, 1, 2, '2026-07-09 20:16:21', '2026-07-09 20:16:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `issues`
--

CREATE TABLE `issues` (
  `id` varchar(255) NOT NULL,
  `type` enum('Bug','Improve','Request') NOT NULL,
  `status` enum('Unassigned','Assigned','In Progress','Complete') NOT NULL DEFAULT 'Unassigned',
  `priority` enum('Low','Medium','High') NOT NULL DEFAULT 'Low',
  `deadline` date DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `issues`
--

INSERT INTO `issues` (`id`, `type`, `status`, `priority`, `deadline`, `subject`, `title`, `description`, `assigned_to`, `creator_id`, `created_at`, `updated_at`) VALUES
('ISS-006', 'Request', 'Assigned', 'High', '2026-07-22', 'SellerPro', 'Saya Rayhan', 'halo', 2, 1, '2026-07-09 19:53:11', '2026-07-09 19:53:11'),
('ISS-007', 'Bug', 'Assigned', 'Medium', '2026-07-21', 'SellerPro', 'Saya Rahan', 'Halo', 2, 1, '2026-07-09 19:56:48', '2026-07-09 19:56:48'),
('ISS-008', 'Improve', 'Assigned', 'Low', '2026-07-20', 'SellerPro', 'Saya Rayhan', 'Halo', 2, 1, '2026-07-09 19:58:01', '2026-07-09 19:58:01'),
('ISS-009', 'Bug', 'Assigned', 'High', '2026-08-17', 'LPM UKP', 'rehung', 'bikin logo', 2, 1, '2026-07-09 19:59:45', '2026-07-09 19:59:45'),
('ISS-010', 'Improve', 'Assigned', 'Medium', '2026-07-19', 'LPM UKP', 'rehung', 'rehung', 2, 1, '2026-07-09 20:01:16', '2026-07-09 20:01:16'),
('ISS-011', 'Request', 'Assigned', 'Low', '2026-07-18', 'LPM UKP', 'rehung', 'hai', 2, 1, '2026-07-09 20:02:26', '2026-07-09 20:02:26'),
('ISS-012', 'Bug', 'Assigned', 'High', '2026-07-18', 'Social Lens', 'Raymond', 'Disini', 2, 1, '2026-07-09 20:03:09', '2026-07-09 20:03:09'),
('ISS-013', 'Improve', 'Assigned', 'Medium', '2026-07-16', 'Social Lens', 'Disana', 'Kerjakan', 2, 1, '2026-07-09 20:03:38', '2026-07-09 20:03:38'),
('ISS-014', 'Request', 'Assigned', 'Low', '2026-07-15', 'Social Lens', 'Disitu', 'Ambil ini', 2, 1, '2026-07-09 20:04:02', '2026-07-09 20:04:02'),
('ISS-015', 'Bug', 'Assigned', 'Medium', '2026-07-09', 'LPM UKP', 'Bug di bagian sini', 'disini', 2, 1, '2026-07-17 01:47:24', '2026-07-17 01:47:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `issue_attachments`
--

CREATE TABLE `issue_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issue_id` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `issue_attachments`
--

INSERT INTO `issue_attachments` (`id`, `issue_id`, `file_path`, `file_name`, `created_at`, `updated_at`) VALUES
(3, 'ISS-009', 'attachments/NsyChDgJ1wsc2dV3KJjCbqJ9RMfJM32ynohWHsNR.png', 'Screenshot (39).png', '2026-07-09 19:59:46', '2026-07-09 19:59:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `issue_histories`
--

CREATE TABLE `issue_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issue_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `issue_histories`
--

INSERT INTO `issue_histories` (`id`, `issue_id`, `user_id`, `action`, `description`, `created_at`, `updated_at`) VALUES
(15, 'ISS-006', 1, 'created', 'Issue created by Admin User', '2026-07-09 19:53:11', '2026-07-09 19:53:11'),
(16, 'ISS-006', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 19:53:11', '2026-07-09 19:53:11'),
(17, 'ISS-007', 1, 'created', 'Issue created by Admin User', '2026-07-09 19:56:48', '2026-07-09 19:56:48'),
(18, 'ISS-007', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 19:56:48', '2026-07-09 19:56:48'),
(19, 'ISS-008', 1, 'created', 'Issue created by Admin User', '2026-07-09 19:58:01', '2026-07-09 19:58:01'),
(20, 'ISS-008', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 19:58:01', '2026-07-09 19:58:01'),
(21, 'ISS-009', 1, 'created', 'Issue created by Admin User', '2026-07-09 19:59:46', '2026-07-09 19:59:46'),
(22, 'ISS-009', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 19:59:46', '2026-07-09 19:59:46'),
(23, 'ISS-010', 1, 'created', 'Issue created by Admin User', '2026-07-09 20:01:16', '2026-07-09 20:01:16'),
(24, 'ISS-010', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 20:01:16', '2026-07-09 20:01:16'),
(25, 'ISS-011', 1, 'created', 'Issue created by Admin User', '2026-07-09 20:02:26', '2026-07-09 20:02:26'),
(26, 'ISS-011', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 20:02:26', '2026-07-09 20:02:26'),
(27, 'ISS-012', 1, 'created', 'Issue created by Admin User', '2026-07-09 20:03:09', '2026-07-09 20:03:09'),
(28, 'ISS-012', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 20:03:09', '2026-07-09 20:03:09'),
(29, 'ISS-013', 1, 'created', 'Issue created by Admin User', '2026-07-09 20:03:38', '2026-07-09 20:03:38'),
(30, 'ISS-013', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 20:03:38', '2026-07-09 20:03:38'),
(31, 'ISS-014', 1, 'created', 'Issue created by Admin User', '2026-07-09 20:04:02', '2026-07-09 20:04:02'),
(32, 'ISS-014', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-09 20:04:02', '2026-07-09 20:04:02'),
(33, 'ISS-015', 1, 'created', 'Issue created by Admin User', '2026-07-17 01:47:24', '2026-07-17 01:47:24'),
(34, 'ISS-015', 1, 'assigned', 'Issue assigned to Worker User by Admin', '2026-07-17 01:47:24', '2026-07-17 01:47:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_06_000000_create_workroute_tables', 1),
(5, '2026_07_09_000000_update_issues_add_title_deadline_and_group_chats', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('H79MHLSYGCCOPkEdEVCJ2CxrA5kDvF8QS2slLFvI', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieURoT1hPd1dLUGF1R2hlb043Q1VDZlZIZE1FTUFHQXVtQ2xJWTFMTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90YXNrcz9hc3NpZ25lZF90bz01JmRlYWRsaW5lPSZwcmlvcml0eT0mc2VhcmNoPSZzdGF0dXM9JnN1YmplY3Q9JnR5cGU9IjtzOjU6InJvdXRlIjtzOjExOiJ0YXNrcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1784278405);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','worker','client') NOT NULL DEFAULT 'client',
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@workroute.com', NULL, '$2y$12$8nsEKMPnh8IqLHvXr/pgFeyQ3AK3/b1HMcTnRNc11sKk/7lHfN/Eq', 'admin', NULL, 'eGDaGZZJbq4tbnvO0IqL8htv4vNEiJAI04ZdgDUgdhWUPawF8kbmfCeAqNwl', '2026-07-06 00:10:32', '2026-07-06 20:27:20'),
(2, 'Worker User', 'worker@workroute.com', NULL, '$2y$12$AmeZUtbG6R1IhcV.MebYHOBCajOqHQBJXdVFMizjSDtHwTjof04iK', 'worker', NULL, NULL, '2026-07-06 00:10:32', '2026-07-06 00:10:32'),
(3, 'Client User', 'client@workroute.com', NULL, '$2y$12$uY46mVPMe86J/E8ltjkyT.DI1pRnluXmE3R6uHUUtiD40P9d4GwHi', 'client', NULL, NULL, '2026-07-06 00:10:33', '2026-07-09 20:17:54'),
(5, 'Worker2', 'worker2@workroute.com', NULL, '$2y$12$kt.XsSooCKxJH20gaetIFOpIzlxLcRBu/LMYnImFufoRxBwiymZg2', 'worker', NULL, NULL, '2026-07-17 01:50:55', '2026-07-17 01:50:55');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chats_sender_id_foreign` (`sender_id`),
  ADD KEY `chats_receiver_id_foreign` (`receiver_id`),
  ADD KEY `chats_group_chat_id_foreign` (`group_chat_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `group_chats`
--
ALTER TABLE `group_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_chats_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `group_chat_members`
--
ALTER TABLE `group_chat_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_chat_members_group_chat_id_user_id_unique` (`group_chat_id`,`user_id`),
  ADD KEY `group_chat_members_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issues_assigned_to_foreign` (`assigned_to`),
  ADD KEY `issues_creator_id_foreign` (`creator_id`);

--
-- Indeks untuk tabel `issue_attachments`
--
ALTER TABLE `issue_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_attachments_issue_id_foreign` (`issue_id`);

--
-- Indeks untuk tabel `issue_histories`
--
ALTER TABLE `issue_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_histories_issue_id_foreign` (`issue_id`),
  ADD KEY `issue_histories_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `group_chats`
--
ALTER TABLE `group_chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `group_chat_members`
--
ALTER TABLE `group_chat_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `issue_attachments`
--
ALTER TABLE `issue_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `issue_histories`
--
ALTER TABLE `issue_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_group_chat_id_foreign` FOREIGN KEY (`group_chat_id`) REFERENCES `group_chats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chats_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `group_chats`
--
ALTER TABLE `group_chats`
  ADD CONSTRAINT `group_chats_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `group_chat_members`
--
ALTER TABLE `group_chat_members`
  ADD CONSTRAINT `group_chat_members_group_chat_id_foreign` FOREIGN KEY (`group_chat_id`) REFERENCES `group_chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_chat_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `issues_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `issue_attachments`
--
ALTER TABLE `issue_attachments`
  ADD CONSTRAINT `issue_attachments_issue_id_foreign` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `issue_histories`
--
ALTER TABLE `issue_histories`
  ADD CONSTRAINT `issue_histories_issue_id_foreign` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `issue_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
