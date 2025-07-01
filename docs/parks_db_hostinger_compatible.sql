-- AmyoParks Database - Hostinger Compatible Version
-- Compatible with MySQL 5.7+ and MariaDB
-- Uses utf8mb4_unicode_ci collation instead of utf8mb4_0900_ai_ci

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parks_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@amyoparks.com', 1, '2025-06-30 20:35:12', '2025-06-30 20:35:12');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `amenity_id` int NOT NULL AUTO_INCREMENT,
  `park_id` int NOT NULL,
  `category_id` int NOT NULL,
  `instance_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`amenity_id`),
  KEY `idx_amenities_park_id` (`park_id`),
  KEY `idx_amenities_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `attribute_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `icon` varchar(50) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `icon`, `color`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Restrooms', 'Public restroom facilities', 'restroom', '#4A90E2', 1, 1, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(2, 'Baseball/Softball Fields', 'Baseball and softball playing fields', 'sports_baseball', '#E74C3C', 1, 2, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(3, 'Basketball Courts', 'Basketball playing courts', 'sports_basketball', '#F39C12', 1, 3, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(4, 'Basketball Courts', 'Basketball playing courts', 'sports_basketball', '#F39C12', 1, 4, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(5, 'Tennis Courts', 'Tennis playing courts', 'sports_tennis', '#2ECC71', 1, 5, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(6, 'Playgrounds', 'Children playground equipment', 'playground', '#9B59B6', 1, 6, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(7, 'Picnic Areas', 'Picnic tables and shelters', 'picnic', '#16A085', 1, 7, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(8, 'Dog Parks', 'Off-leash dog exercise areas', 'pets', '#E67E22', 1, 8, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(9, 'Walking/Hiking Trails', 'Walking and hiking trail systems', 'hiking', '#27AE60', 1, 9, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(10, 'Swimming Pools', 'Public swimming facilities', 'pool', '#3498DB', 1, 10, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(11, 'Fishing Areas', 'Designated fishing spots', 'fishing', '#1ABC9C', 1, 11, '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(12, 'Lakes/Ponds', 'Natural or artificial water bodies', 'water', '#3498DB', 1, 12, '2025-06-30 19:37:31', '2025-06-30 19:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `parks`
--

CREATE TABLE `parks` (
  `park_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code_id` int DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `hours_open` time DEFAULT NULL,
  `hours_close` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category_id` int DEFAULT NULL,
  `description` text,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_featured` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`park_id`),
  KEY `idx_parks_zip_code_id` (`zip_code_id`),
  KEY `idx_parks_category_id` (`category_id`),
  KEY `idx_parks_active` (`is_active`),
  KEY `idx_parks_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parks`
--

INSERT INTO `parks` (`park_id`, `name`, `address`, `zip_code_id`, `latitude`, `longitude`, `hours_open`, `hours_close`, `created_at`, `updated_at`, `category_id`, `description`, `city`, `state`, `phone`, `website`, `email`, `is_active`, `is_featured`) VALUES
(1, 'Swope Park', '6600 Swope Pkwy', 1, '39.003600', '-94.535800', '05:00:00', '00:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(2, 'Loose Park', '5200 Wornall Rd', 2, '39.033400', '-94.595300', '05:00:00', '00:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(3, 'Wyandotte County Lake Park', '9100 Leavenworth Rd', 3, '39.141700', '-94.797500', '06:00:00', '22:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(4, 'Penn Valley Park', '2500 Southwest Trfy', 4, '39.073600', '-94.589400', '05:00:00', '00:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `state_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `abbreviation` char(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`state_id`),
  UNIQUE KEY `abbreviation` (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `name`, `abbreviation`, `created_at`, `updated_at`) VALUES
(1, 'Missouri', 'MO', '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(2, 'Kansas', 'KS', '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(9, 'Nebraska', 'NE', '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(10, 'Oklahoma', 'OK', '2025-07-01 06:20:31', '2025-07-01 06:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `websites`
--

CREATE TABLE `websites` (
  `website_id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `state_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`website_id`),
  KEY `idx_websites_state_id` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `websites`
--

INSERT INTO `websites` (`website_id`, `url`, `description`, `state_id`, `created_at`, `updated_at`) VALUES
(1, 'https://lawrenceks.org', 'Official website for the City of Lawrence, Kansas, providing park and recreation information for city parks like Lyons Park and Buford M. Watson Jr. Park.', 2, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(2, 'https://ksoutdoors.com', 'Official Kansas state parks website, managed by the Kansas Department of Wildlife, Parks and Tourism, with details on parks like Fall River, Perry, and Kanopolis.', 2, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(3, 'https://mostateparks.com', 'Official Missouri state parks website, managed by the Missouri Department of Natural Resources, with information on parks like Pershing, St. Joe, and Graham Cave.', 1, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(4, 'https://www.nps.gov', 'Official National Park Service website, providing information on national parks and historic sites like Fort Larned National Historic Site.', NULL, '2025-07-01 06:20:31', '2025-07-01 06:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `zip_codes`
--

CREATE TABLE `zip_codes` (
  `zip_code_id` int NOT NULL AUTO_INCREMENT,
  `zip_code` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state_id` int NOT NULL,
  `latitude` decimal(8,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`zip_code_id`),
  UNIQUE KEY `zip_code` (`zip_code`),
  KEY `idx_zip_codes_state_id` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Add foreign key constraints
--

ALTER TABLE `amenities`
  ADD CONSTRAINT `amenities_ibfk_1` FOREIGN KEY (`park_id`) REFERENCES `parks` (`park_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `amenities_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

ALTER TABLE `parks`
  ADD CONSTRAINT `parks_ibfk_1` FOREIGN KEY (`zip_code_id`) REFERENCES `zip_codes` (`zip_code_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `parks_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

ALTER TABLE `websites`
  ADD CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`state_id`) ON DELETE SET NULL;

ALTER TABLE `zip_codes`
  ADD CONSTRAINT `zip_codes_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`state_id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
