-- phpMyAdmin SQL Dump
-- version 5.2.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 09, 2021 at 08:42 AM
-- Server version: 5.7.36-0ubuntu0.18.04.1
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blockcity`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banishments`
--

DROP TABLE IF EXISTS `banishments`;
CREATE TABLE IF NOT EXISTS `banishments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `finishes_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banishments_admin_id_foreign` (`admin_id`),
  KEY `banishments_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
CREATE TABLE IF NOT EXISTS `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rarity` tinyint(1) DEFAULT NULL,
  `chance` float NOT NULL DEFAULT '0',
  `incomes` float DEFAULT NULL,
  `upgrade_cost` float DEFAULT NULL,
  `images` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `name`, `rarity`, `chance`, `incomes`, `upgrade_cost`, `images`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Common House', 1, 0, 1.44, 36, 1, '2021-12-01 19:13:15', '2021-12-01 19:13:15', NULL),
(2, 'Epic House', 2, 0, 3.16, 76, 1, '2021-12-01 19:20:13', '2021-12-01 19:20:13', NULL),
(3, 'Legendary House', 3, 0, 7.77, 162, 1, '2021-12-01 19:20:13', '2021-12-01 19:20:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `building_statuses`
--

DROP TABLE IF EXISTS `building_statuses`;
CREATE TABLE IF NOT EXISTS `building_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loss` float NOT NULL,
  `fix_price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `building_statuses`
--

INSERT INTO `building_statuses` (`id`, `name`, `color`, `loss`, `fix_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'High', 'text-success', 0, 0, '2021-12-09 07:33:35', '2021-12-09 07:33:35', NULL),
(2, 'Medium', 'text-warning', 20, 10, '2021-12-09 07:33:35', '2021-12-09 07:33:35', NULL),
(3, 'Low', 'text-danger', 30, 20, '2021-12-09 07:33:35', '2021-12-09 07:33:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('box','consumable','house') COLLATE utf8mb4_unicode_ci NOT NULL,
  `rarity` tinyint(4) NOT NULL,
  `price` float NOT NULL,
  `drop_chance` float NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2021_12_08_064602_create_users_table', 1),
(3, '2021_12_08_064620_create_items_table', 1),
(4, '2021_12_08_064627_create_item_statuses_table', 1),
(5, '2021_12_08_064628_create_admin_table', 1),
(6, '2021_12_08_064653_create_user_items_table', 1),
(7, '2021_12_08_064704_create_transactions_table', 1),
(8, '2021_12_08_071302_create_banishments_table', 1),
(9, '2021_12_08_072041_create_packs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `packs`
--

DROP TABLE IF EXISTS `packs`;
CREATE TABLE IF NOT EXISTS `packs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `price_bnb` float NOT NULL,
  `sell_limit` int(11) NOT NULL DEFAULT '1',
  `rewards` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packs`
--

INSERT INTO `packs` (`id`, `name`, `image`, `price`, `price_bnb`, `sell_limit`, `rewards`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Little House Pack', 'casas/build6.png', 36, 0.06, 500, '{\"7\": 6, \"8\": 6, \"9\": 6}', '2021-12-09 07:34:00', '2021-12-09 07:34:00', NULL),
(2, 'Medium House Pack', 'casas/build8.png', 76, 0.13, 400, '{\"10\": 4, \"11\": 4, \"12\": 4}', '2021-12-09 07:34:00', '2021-12-09 07:34:00', NULL),
(3, 'Big House Pack', 'casas/build10.png', 162, 0.28, 500, '{\"13\": 3, \"14\": 3, \"15\": 3}', '2021-12-09 07:34:00', '2021-12-09 07:34:00', NULL),
(4, 'Small Neighborhood Pack', 'casas/build12.png', 180, 0.31, 400, '{\"7\": 18, \"8\": 18, \"9\": 18, \"10\": 4, \"11\": 4, \"12\": 4}', '2021-12-09 07:34:00', '2021-12-09 07:34:00', NULL),
(5, 'Medium Neighborhood Pack', 'casas/build34.png', 490, 0.83, 300, '{\"7\": 18, \"8\": 18, \"9\": 18, \"10\": 12, \"11\": 12, \"12\": 12, \"13\": 3, \"14\": 3, \"15\": 3}', '2021-12-09 07:34:00', '2021-12-09 07:34:00', NULL),
(6, 'Big Neighborhood Pack', 'casas/build33.png', 1350, 2.3, 200, '{\"7\": 30, \"8\": 30, \"9\": 30, \"10\": 20, \"11\": 20, \"12\": 20, \"13\": 15, \"14\": 15, \"15\": 15}', '2021-12-09 07:34:00', '2021-12-09 07:34:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('exchange','withdraw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','failed','success') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amount` float NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `txid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` float NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `wallet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `earnings` float NOT NULL DEFAULT '0',
  `currency` float NOT NULL DEFAULT '0',
  `presale` tinyint(4) NOT NULL DEFAULT '0',
  `last_captcha_check` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_wallet_unique` (`wallet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `wallet`, `secret`, `earnings`, `currency`, `presale`, `last_captcha_check`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0x369a4e92da7835dee6fcc7a2bdd275afcb0f54d6', '0x06931e6d7d1c6ccf94386708c54b474f8d2527895a12831469125e20842948fd18f9f1d3c8a905e0c20a0b7e44f4ba38ab742e9a87c2abb9617e19c10da0740a1c', 0, 1000, 0, NULL, '2021-12-08 15:26:03', '2021-12-08 15:26:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_buildings`
--

DROP TABLE IF EXISTS `user_buildings`;
CREATE TABLE IF NOT EXISTS `user_buildings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `building_status_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` tinyint(4) NOT NULL DEFAULT '1',
  `level` int(11) NOT NULL DEFAULT '1',
  `highlight` tinyint(1) NOT NULL DEFAULT '1',
  `earnings` float NOT NULL DEFAULT '0',
  `last_claim` float NOT NULL DEFAULT '0',
  `last_claim_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_buildings`
--

INSERT INTO `user_buildings` (`id`, `user_id`, `building_id`, `building_status_id`, `name`, `image`, `level`, `highlight`, `earnings`, `last_claim`, `last_claim_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '', 1, 1, 0, 12.357, 4.32, '2021-12-08 05:44:34', '2021-12-08 05:44:34', '2021-12-08 05:44:34'),
(2, 1, 2, 1, NULL, 1, 1, 0, 27.1336, 9.48, '2021-12-09 02:23:13', '2021-12-09 04:23:13', '2021-12-09 04:23:13'),
(3, 1, 3, 1, NULL, 1, 1, 0, 48.997, 23.31, '2021-12-09 07:44:34', '2021-12-09 07:44:34', '2021-12-09 07:44:34'),
(4, 1, 1, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 12:28:20'),
(5, 1, 2, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 12:28:20'),
(6, 1, 1, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 12:28:20'),
(7, 1, 2, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 09:30:09'),
(8, 1, 3, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 09:30:09'),
(9, 1, 3, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 09:30:09'),
(10, 1, 2, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 09:30:09'),
(11, 1, 2, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 09:30:09'),
(12, 1, 1, 1, NULL, 1, 1, 0, 0, 0, '2021-12-09 09:28:17', '2021-12-09 09:28:17', '2021-12-09 09:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_items`
--

DROP TABLE IF EXISTS `user_items`;
CREATE TABLE IF NOT EXISTS `user_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_items_user_id_foreign` (`user_id`),
  KEY `user_items_item_id_foreign` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `banishments`
--
ALTER TABLE `banishments`
  ADD CONSTRAINT `banishments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `banishments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_items`
--
ALTER TABLE `user_items`
  ADD CONSTRAINT `user_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `user_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
