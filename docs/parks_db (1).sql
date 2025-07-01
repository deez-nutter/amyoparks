-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 01, 2025 at 06:23 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

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
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `amenity_id` int NOT NULL,
  `park_id` int NOT NULL,
  `category_id` int NOT NULL,
  `instance_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`amenity_id`, `park_id`, `category_id`, `instance_name`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Swope Diamond 1', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(2, 1, 2, 'Swope Diamond 2', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(3, 1, 2, 'Swope Diamond 3', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(4, 1, 12, 'Swope Lake', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(5, 1, 9, 'Swope Main Trail', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(6, 2, 12, 'Loose Park Pond', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(7, 2, 5, 'Loose Tennis Courts', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(8, 2, 7, 'Loose Picnic Area', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(9, 3, 12, 'Wyandotte Lake', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(10, 3, 9, 'Wyandotte Lake Trail', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(11, 3, 7, 'Wyandotte Picnic Area', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(12, 4, 8, 'Penn Valley Dog Park', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(13, 4, 9, 'Penn Valley Trail', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(14, 4, 58, 'Liberty Memorial', '2025-06-30 19:37:31', '2025-06-30 19:37:31'),
(15, 5, 9, 'Mill Creek Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(16, 5, 7, 'Mill Creek Picnic Area', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(17, 6, 9, 'Cliff Drive Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(18, 7, 12, 'Longview Lake', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(19, 7, 9, 'Longview Bike Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(20, 8, 12, 'Lake Jacomo', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(21, 8, 9, 'Fleming Hiking Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(22, 9, 6, 'Penguin Playground', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(23, 10, 6, 'Antioch Playground', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(24, 10, 7, 'Antioch Picnic Area', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(25, 11, 12, 'Rose Lake', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(26, 11, 9, 'Sar-Ko-Par Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(27, 12, 2, 'Legacy Baseball Field 1', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(28, 12, 28, 'Legacy Soccer Field 1', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(29, 13, 9, 'Hidden Valley Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(30, 14, 6, 'Gezer Playground', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(31, 14, 4, 'Gezer Basketball Court', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(32, 15, 2, 'Pierson Baseball Field', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(33, 15, 7, 'Pierson Picnic Area', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(34, 16, 9, 'Tomahawk Creek Trail', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(35, 16, 2, 'Tomahawk Baseball Field', '2025-06-30 19:40:06', '2025-06-30 19:40:06'),
(36, 17, 6, 'Waterfall Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(37, 17, 9, 'Waterfall Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(38, 18, 48, 'Blue Valley Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(39, 18, 6, 'Blue Valley Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(40, 19, 6, 'Budd Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(41, 19, 9, 'Budd Perimeter Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(42, 20, 6, 'MLK Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(43, 21, 6, 'Tower Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(44, 22, 48, 'Parade Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(45, 23, 48, 'Gillham Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(46, 23, 6, 'Gillham Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(47, 24, 12, 'Shawnee Mission Lake', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(48, 24, 9, 'Shawnee Mission Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(49, 25, 6, 'Hodge Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(50, 25, 9, 'Hodge Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(51, 26, 6, 'Grove Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(52, 27, 48, 'Spring Valley Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(53, 28, 6, 'Roanoke Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(54, 29, 9, 'Minor Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(55, 30, 5, 'Harmon Tennis Courts', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(56, 30, 6, 'Harmon Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(57, 31, 6, 'Hawk\'s Nest Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(58, 32, 6, 'Arno Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(59, 33, 48, 'Lea McKeighan Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(60, 34, 6, 'Macken Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(61, 34, 9, 'Macken Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(62, 35, 9, 'English Landing Trail', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(63, 36, 6, 'Gum Springs Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(64, 37, 12, 'Cedar Lake', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(65, 38, 6, 'Meadowbrook Inclusive Playground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(66, 39, 48, 'Harmony Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(67, 40, 48, 'Ashland Square Sprayground', '2025-06-30 19:43:32', '2025-06-30 19:43:32'),
(68, 65, 9, 'Indian Creek Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(69, 66, 6, 'Sapling Grove Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(70, 67, 12, 'Black Hoof Lake', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(71, 67, 9, 'Black Hoof Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(72, 68, 6, 'Stump Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(73, 69, 6, 'Santa Fe Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(74, 70, 6, 'Ironwoods Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(75, 70, 9, 'Ironwoods Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(76, 71, 6, 'Klamm Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(77, 72, 12, 'Heritage Lake', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(78, 72, 8, 'Heritage Dog Park', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(79, 73, 6, 'Bluejacket Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(80, 74, 6, 'Quail Creek Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(81, 75, 5, 'Franklin Tennis Courts', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(82, 76, 6, 'City Park Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(83, 77, 9, 'Two Rivers Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(84, 78, 6, 'I-Lan Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(85, 79, 6, 'Swarner Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(86, 80, 58, 'Mahaffie Stagecoach', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(87, 81, 6, 'Emerson Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(88, 82, 6, 'Listowel Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(89, 83, 6, 'Porter Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(90, 84, 6, 'Oak Hill Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(91, 85, 6, 'Jacob Loose Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(92, 86, 6, 'Brookside Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(93, 87, 6, 'Sunnyside Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(94, 88, 6, 'Waterwell Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(95, 89, 6, 'Park Forest Playground', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(96, 90, 9, 'Little Blue Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(97, 91, 5, 'Happy Rock Tennis Courts', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(98, 92, 9, 'George Owens Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(99, 93, 9, 'Line Creek Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(100, 94, 9, 'Lakewood Trail', '2025-07-01 03:06:55', '2025-07-01 03:06:55'),
(101, 145, 9, 'Burcham River Trail', '2025-07-01 05:41:46', '2025-07-01 05:41:46'),
(102, 145, 7, 'Burcham Picnic Area', '2025-07-01 05:41:46', '2025-07-01 05:41:46'),
(103, 146, 16, 'Clinton Campground', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(104, 146, 10, 'Clinton Nature Trail', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(105, 146, 25, 'Clinton Boat Ramp', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(106, 147, 58, 'Watkins Woolen Mill', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(107, 147, 16, 'Watkins Campground', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(108, 147, 10, 'Watkins Lake Trail', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(109, 148, 10, 'Weston Bluff Trail', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(110, 148, 16, 'Weston Campground', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(111, 148, 7, 'Weston Picnic Area', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(112, 149, 10, 'Clearfork Trail', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(113, 149, 16, 'Knob Noster Campground', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(114, 149, 12, 'Buteo Lake', '2025-07-01 05:41:47', '2025-07-01 05:41:47'),
(115, 150, 6, 'South Park Playground', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(116, 150, 7, 'South Park Picnic Area', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(117, 151, 16, 'El Dorado Campground', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(118, 151, 10, 'El Dorado Lake Trail', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(119, 151, 25, 'El Dorado Boat Ramp', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(120, 152, 16, 'Crowder Campground', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(121, 152, 10, 'Crowder Trail', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(122, 152, 12, 'Crowder Lake', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(123, 153, 16, 'Thousand Hills Campground', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(124, 153, 10, 'Forest Lake Trail', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(125, 153, 12, 'Forest Lake', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(126, 154, 6, 'Swope Memorial Playground', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(127, 154, 9, 'Swope Memorial Trail', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(128, 154, 7, 'Swope Memorial Picnic Area', '2025-07-01 05:44:41', '2025-07-01 05:44:41'),
(129, 155, 6, 'Watson Playground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(130, 155, 7, 'Watson Picnic Area', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(131, 156, 16, 'Kanopolis Campground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(132, 156, 10, 'Horsethief Canyon Trail', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(133, 156, 12, 'Kanopolis Lake', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(134, 157, 16, 'Cheney Campground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(135, 157, 25, 'Cheney Boat Ramp', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(136, 157, 10, 'Cheney Nature Trail', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(137, 158, 58, 'Lewis and Clark Pavilion', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(138, 158, 9, 'Riverfront Trail', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(139, 159, 16, 'Finger Lakes Campground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(140, 159, 10, 'Kelley Branch Trail', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(141, 159, 12, 'Finger Lakes', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(142, 160, 16, 'Ozarks Campground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(143, 160, 10, 'Woodland Trail', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(144, 160, 25, 'Ozarks Boat Ramp', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(145, 161, 16, 'Wallace Campground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(146, 161, 10, 'Deer Run Trail', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(147, 161, 12, 'Wallace Lake', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(148, 162, 6, 'McBaine Playground', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(149, 162, 7, 'McBaine Picnic Area', '2025-07-01 06:08:53', '2025-07-01 06:08:53'),
(150, 163, 6, 'Lyons Playground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(151, 163, 7, 'Lyons Picnic Area', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(152, 163, 2, 'Lyons Baseball Field', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(153, 164, 16, 'Fall River Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(154, 164, 10, 'Fall River Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(155, 164, 25, 'Fall River Boat Ramp', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(156, 165, 16, 'Perry Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(157, 165, 10, 'Perry Lake Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(158, 165, 25, 'Perry Boat Ramp', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(159, 166, 58, 'Fort Larned Historic Buildings', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(160, 166, 9, 'Fort Larned History Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(161, 167, 16, 'Pershing Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(162, 167, 10, 'Locust Creek Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(163, 167, 43, 'Pershing Wetland Boardwalk', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(164, 168, 16, 'St. Joe Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(165, 168, 10, 'St. Joe ORV Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(166, 168, 12, 'Monsanto Lake', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(167, 169, 16, 'Graham Cave Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(168, 169, 10, 'Graham Cave Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(169, 169, 58, 'Graham Cave', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(170, 170, 16, 'Indian Cave Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(171, 170, 10, 'Indian Cave Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(172, 170, 58, 'Indian Cave Petroglyphs', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(173, 171, 16, 'Robbers Cave Campground', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(174, 171, 10, 'Rough Canyon Trail', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(175, 171, 58, 'Robbers Cave', '2025-07-01 06:14:04', '2025-07-01 06:14:04'),
(176, 172, 16, 'Natural Falls Campground', '2025-07-01 06:14:05', '2025-07-01 06:14:05'),
(177, 172, 10, 'Dripping Springs Trail', '2025-07-01 06:14:05', '2025-07-01 06:14:05'),
(178, 172, 55, 'Natural Falls', '2025-07-01 06:14:05', '2025-07-01 06:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `amenity_attributes`
--

CREATE TABLE `amenity_attributes` (
  `attribute_id` int NOT NULL,
  `amenity_id` int NOT NULL,
  `attribute_type_id` int NOT NULL,
  `attribute_value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `amenity_attributes`
--

INSERT INTO `amenity_attributes` (`attribute_id`, `amenity_id`, `attribute_type_id`, `attribute_value`, `created_at`) VALUES
(1, 7, 1, '6', '2025-06-30 19:37:31'),
(2, 7, 2, 'yes', '2025-06-30 19:37:31'),
(3, 8, 2, 'yes', '2025-06-30 19:37:31'),
(4, 11, 2, 'yes', '2025-06-30 19:37:31'),
(5, 12, 3, 'all', '2025-06-30 19:37:31'),
(6, 12, 2, 'yes', '2025-06-30 19:37:31'),
(7, 14, 2, 'yes', '2025-06-30 19:37:31'),
(8, 16, 2, 'yes', '2025-06-30 19:40:06'),
(9, 17, 5, 'paved', '2025-06-30 19:40:06'),
(10, 17, 2, 'yes', '2025-06-30 19:40:06'),
(11, 19, 5, 'paved', '2025-06-30 19:40:06'),
(12, 22, 4, 'animal-themed', '2025-06-30 19:40:06'),
(13, 22, 2, 'yes', '2025-06-30 19:40:06'),
(14, 23, 2, 'yes', '2025-06-30 19:40:06'),
(15, 24, 2, 'yes', '2025-06-30 19:40:06'),
(16, 26, 5, 'paved', '2025-06-30 19:40:06'),
(17, 33, 2, 'yes', '2025-06-30 19:40:06'),
(18, 68, 5, 'paved', '2025-07-01 03:06:55'),
(19, 68, 2, 'yes', '2025-07-01 03:06:55'),
(20, 69, 2, 'yes', '2025-07-01 03:06:55'),
(21, 71, 5, 'paved', '2025-07-01 03:06:55'),
(22, 72, 2, 'yes', '2025-07-01 03:06:55'),
(23, 73, 2, 'yes', '2025-07-01 03:06:55'),
(24, 74, 2, 'yes', '2025-07-01 03:06:55'),
(25, 76, 2, 'yes', '2025-07-01 03:06:55'),
(26, 78, 3, 'all', '2025-07-01 03:06:55'),
(27, 78, 2, 'yes', '2025-07-01 03:06:55'),
(28, 79, 2, 'yes', '2025-07-01 03:06:55'),
(29, 80, 2, 'yes', '2025-07-01 03:06:55'),
(30, 81, 1, '2', '2025-07-01 03:06:55'),
(31, 81, 2, 'yes', '2025-07-01 03:06:55'),
(32, 82, 2, 'yes', '2025-07-01 03:06:55'),
(33, 84, 2, 'yes', '2025-07-01 03:06:55'),
(34, 85, 2, 'yes', '2025-07-01 03:06:55'),
(35, 87, 2, 'yes', '2025-07-01 03:06:55'),
(36, 88, 2, 'yes', '2025-07-01 03:06:55'),
(37, 89, 2, 'yes', '2025-07-01 03:06:55'),
(38, 90, 2, 'yes', '2025-07-01 03:06:55'),
(39, 91, 2, 'yes', '2025-07-01 03:06:55'),
(40, 92, 2, 'yes', '2025-07-01 03:06:55'),
(41, 93, 2, 'yes', '2025-07-01 03:06:55'),
(42, 94, 2, 'yes', '2025-07-01 03:06:55'),
(43, 95, 2, 'yes', '2025-07-01 03:06:55'),
(44, 97, 1, '4', '2025-07-01 03:06:55'),
(45, 97, 2, 'yes', '2025-07-01 03:06:55'),
(46, 101, 5, 'paved', '2025-07-01 05:41:47'),
(47, 101, 2, 'yes', '2025-07-01 05:41:47'),
(48, 102, 2, 'yes', '2025-07-01 05:41:47'),
(49, 103, 2, 'yes', '2025-07-01 05:41:47'),
(50, 104, 5, 'dirt', '2025-07-01 05:41:47'),
(51, 105, 2, 'yes', '2025-07-01 05:41:47'),
(52, 106, 2, 'yes', '2025-07-01 05:41:47'),
(53, 107, 2, 'yes', '2025-07-01 05:41:47'),
(54, 108, 5, 'gravel', '2025-07-01 05:41:47'),
(55, 109, 5, 'dirt', '2025-07-01 05:41:47'),
(56, 110, 2, 'yes', '2025-07-01 05:41:47'),
(57, 111, 2, 'yes', '2025-07-01 05:41:47'),
(58, 112, 5, 'dirt', '2025-07-01 05:41:47'),
(59, 113, 2, 'yes', '2025-07-01 05:41:47'),
(60, 114, 2, 'yes', '2025-07-01 05:41:47'),
(61, 115, 2, 'yes', '2025-07-01 05:44:41'),
(62, 116, 2, 'yes', '2025-07-01 05:44:41'),
(63, 117, 2, 'yes', '2025-07-01 05:44:41'),
(64, 118, 5, 'gravel', '2025-07-01 05:44:41'),
(65, 119, 2, 'yes', '2025-07-01 05:44:41'),
(66, 120, 2, 'yes', '2025-07-01 05:44:41'),
(67, 121, 5, 'dirt', '2025-07-01 05:44:41'),
(68, 122, 2, 'yes', '2025-07-01 05:44:41'),
(69, 123, 2, 'yes', '2025-07-01 05:44:41'),
(70, 124, 5, 'gravel', '2025-07-01 05:44:41'),
(71, 125, 2, 'yes', '2025-07-01 05:44:41'),
(72, 126, 2, 'yes', '2025-07-01 05:44:41'),
(73, 127, 5, 'paved', '2025-07-01 05:44:41'),
(74, 127, 2, 'yes', '2025-07-01 05:44:41'),
(75, 128, 2, 'yes', '2025-07-01 05:44:41'),
(76, 129, 2, 'yes', '2025-07-01 06:08:53'),
(77, 130, 2, 'yes', '2025-07-01 06:08:53'),
(78, 131, 2, 'yes', '2025-07-01 06:08:53'),
(79, 132, 5, 'dirt', '2025-07-01 06:08:53'),
(80, 133, 2, 'yes', '2025-07-01 06:08:53'),
(81, 134, 2, 'yes', '2025-07-01 06:08:53'),
(82, 135, 2, 'yes', '2025-07-01 06:08:53'),
(83, 136, 5, 'gravel', '2025-07-01 06:08:53'),
(84, 137, 2, 'yes', '2025-07-01 06:08:53'),
(85, 138, 5, 'paved', '2025-07-01 06:08:53'),
(86, 138, 2, 'yes', '2025-07-01 06:08:53'),
(87, 139, 2, 'yes', '2025-07-01 06:08:53'),
(88, 140, 5, 'dirt', '2025-07-01 06:08:53'),
(89, 141, 2, 'yes', '2025-07-01 06:08:53'),
(90, 142, 2, 'yes', '2025-07-01 06:08:53'),
(91, 143, 5, 'gravel', '2025-07-01 06:08:53'),
(92, 144, 2, 'yes', '2025-07-01 06:08:53'),
(93, 145, 2, 'yes', '2025-07-01 06:08:53'),
(94, 146, 5, 'gravel', '2025-07-01 06:08:53'),
(95, 147, 2, 'yes', '2025-07-01 06:08:53'),
(96, 148, 2, 'yes', '2025-07-01 06:08:53'),
(97, 149, 2, 'yes', '2025-07-01 06:08:53'),
(98, 150, 2, 'yes', '2025-07-01 06:14:04'),
(99, 151, 2, 'yes', '2025-07-01 06:14:04'),
(100, 152, 2, 'yes', '2025-07-01 06:14:04'),
(101, 153, 2, 'yes', '2025-07-01 06:14:04'),
(102, 154, 5, 'gravel', '2025-07-01 06:14:04'),
(103, 155, 2, 'yes', '2025-07-01 06:14:04'),
(104, 156, 2, 'yes', '2025-07-01 06:14:04'),
(105, 157, 5, 'gravel', '2025-07-01 06:14:04'),
(106, 158, 2, 'yes', '2025-07-01 06:14:04'),
(107, 159, 2, 'yes', '2025-07-01 06:14:04'),
(108, 160, 5, 'paved', '2025-07-01 06:14:04'),
(109, 160, 2, 'yes', '2025-07-01 06:14:04'),
(110, 161, 2, 'yes', '2025-07-01 06:14:04'),
(111, 162, 5, 'gravel', '2025-07-01 06:14:04'),
(112, 163, 2, 'yes', '2025-07-01 06:14:04'),
(113, 164, 2, 'yes', '2025-07-01 06:14:04'),
(114, 165, 5, 'dirt', '2025-07-01 06:14:04'),
(115, 166, 2, 'yes', '2025-07-01 06:14:04'),
(116, 167, 2, 'yes', '2025-07-01 06:14:04'),
(117, 168, 5, 'gravel', '2025-07-01 06:14:04'),
(118, 169, 2, 'yes', '2025-07-01 06:14:04'),
(119, 170, 2, 'yes', '2025-07-01 06:14:04'),
(120, 171, 5, 'dirt', '2025-07-01 06:14:04'),
(121, 172, 2, 'yes', '2025-07-01 06:14:04'),
(122, 173, 2, 'yes', '2025-07-01 06:14:04'),
(123, 174, 5, 'dirt', '2025-07-01 06:14:04'),
(124, 175, 2, 'yes', '2025-07-01 06:14:04'),
(125, 176, 2, 'yes', '2025-07-01 06:14:05'),
(126, 177, 5, 'gravel', '2025-07-01 06:14:05'),
(127, 178, 2, 'yes', '2025-07-01 06:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `amenity_categories`
--

CREATE TABLE `amenity_categories` (
  `category_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `amenity_categories`
--

INSERT INTO `amenity_categories` (`category_id`, `name`, `description`, `created_at`) VALUES
(1, 'Swimming Pool', 'Public or private pools for lap swimming, recreation, or splash play, varying by size, depth, or water type', '2025-06-30 18:55:07'),
(2, 'Baseball Field', 'Fields for baseball or softball, with specific fence lengths, seating, lighting, or surface types', '2025-06-30 18:55:07'),
(3, 'Football Field', 'Fields for American football, soccer, rugby, or lacrosse, with turf, grass, or lighting features', '2025-06-30 18:55:07'),
(4, 'Basketball Court', 'Outdoor or indoor courts for basketball, supporting full or half-court play, with varying surfaces', '2025-06-30 18:55:07'),
(5, 'Tennis Court', 'Courts for tennis or pickleball, with hard, clay, grass, or cushioned surfaces and net types', '2025-06-30 18:55:07'),
(6, 'Playground', 'Areas with slides, swings, climbing structures, or sensory equipment for children of varying ages', '2025-06-30 18:55:07'),
(7, 'Picnic Area', 'Spaces with tables, benches, grills, or shelters for outdoor dining and gatherings', '2025-06-30 18:55:07'),
(8, 'Dog Park', 'Fenced areas for off-leash dog play, often with separate sections for small and large dogs', '2025-06-30 18:55:07'),
(9, 'Walking Trail', 'Paved or unpaved paths for walking, jogging, or casual strolls, varying by length or accessibility', '2025-06-30 18:55:07'),
(10, 'Hiking Trail', 'Trails for hiking through natural landscapes, with different difficulty levels or elevations', '2025-06-30 18:55:07'),
(11, 'Biking Trail', 'Dedicated paths for road or mountain biking, with paved or dirt surfaces', '2025-06-30 18:55:07'),
(12, 'Lake', 'Natural or artificial bodies of water for fishing, boating, swimming, or aesthetic purposes', '2025-06-30 18:55:07'),
(13, 'Pond', 'Smaller water bodies for ecological, recreational, or decorative purposes', '2025-06-30 18:55:07'),
(14, 'Restroom', 'Permanent or portable restroom facilities, with varying accessibility or amenities', '2025-06-30 18:55:07'),
(15, 'Parking Lot', 'Designated vehicle parking areas, with accessible spaces, EV charging, or bike racks', '2025-06-30 18:55:07'),
(16, 'Campground', 'Sites for tent, RV, or cabin camping, with fire pits, hookups, or sanitation facilities', '2025-06-30 18:55:07'),
(17, 'Amphitheater', 'Outdoor venues for concerts, plays, lectures, or community events, with seating or stage features', '2025-06-30 18:55:07'),
(18, 'Skate Park', 'Areas for skateboarding, BMX, or inline skating, with ramps, rails, or bowls', '2025-06-30 18:55:07'),
(19, 'Golf Course', 'Courses for golf, including fairways, greens, driving ranges, or putting areas', '2025-06-30 18:55:07'),
(20, 'Volleyball Court', 'Courts for sand, grass, or hard-court volleyball, with permanent or portable nets', '2025-06-30 18:55:07'),
(21, 'Fitness Station', 'Outdoor exercise equipment for strength, cardio, or flexibility workouts', '2025-06-30 18:55:07'),
(22, 'Shelter', 'Covered structures for picnics, gatherings, or weather protection, with varying capacities', '2025-06-30 18:55:07'),
(23, 'Visitor Center', 'Facilities offering park information, maps, exhibits, or educational programs', '2025-06-30 18:55:07'),
(24, 'Wildlife Viewing Area', 'Designated spots for observing birds, mammals, or other wildlife, with blinds or platforms', '2025-06-30 18:55:07'),
(25, 'Boat Launch', 'Ramps or docks for launching boats, kayaks, or canoes, with trailer parking or docks', '2025-06-30 18:55:07'),
(26, 'Fishing Pier', 'Structures extending into water for fishing, with railings or seating', '2025-06-30 18:55:07'),
(27, 'Beach', 'Sandy or rocky shorelines for swimming, sunbathing, or water activities', '2025-06-30 18:55:07'),
(28, 'Soccer Field', 'Fields specifically for soccer, with goals, markings, or synthetic surfaces', '2025-06-30 18:55:07'),
(29, 'Cricket Pitch', 'Fields for cricket, with prepared pitches, boundary lines, or seating', '2025-06-30 18:55:07'),
(30, 'Archery Range', 'Areas for archery practice or competitions, with targets and safety barriers', '2025-06-30 18:55:07'),
(31, 'Disc Golf Course', 'Courses for disc golf, with baskets, tees, and fairways', '2025-06-30 18:55:07'),
(32, 'Horseshoe Pit', 'Areas for horseshoe games, with stakes and surrounding surfaces', '2025-06-30 18:55:07'),
(33, 'Bocce Ball Court', 'Courts for bocce ball, with flat surfaces and boundary markers', '2025-06-30 18:55:07'),
(34, 'Shuffleboard Court', 'Courts for shuffleboard, with marked lanes and equipment storage', '2025-06-30 18:55:07'),
(35, 'Nature Center', 'Buildings or areas for environmental education, with exhibits or trails', '2025-06-30 18:55:07'),
(36, 'Observation Tower', 'Towers or platforms for scenic views or wildlife observation', '2025-06-30 18:55:07'),
(37, 'Sledding Hill', 'Sloped areas for sledding or snow tubing during winter months', '2025-06-30 18:55:07'),
(38, 'Ice Skating Rink', 'Outdoor or covered areas for ice skating, with rental or lighting facilities', '2025-06-30 18:55:07'),
(39, 'Cross-Country Ski Trail', 'Trails for cross-country skiing, with groomed or natural snow paths', '2025-06-30 18:55:07'),
(40, 'Community Garden', 'Plots for public gardening, with water access or tool storage', '2025-06-30 18:55:07'),
(41, 'Butterfly Garden', 'Landscaped areas to attract butterflies, with native plants and benches', '2025-06-30 18:55:07'),
(42, 'Arboretum', 'Collections of trees or shrubs for education, conservation, or aesthetic purposes', '2025-06-30 18:55:07'),
(43, 'Wetland', 'Protected wetland areas for ecological preservation or wildlife viewing', '2025-06-30 18:55:07'),
(44, 'Rock Climbing Wall', 'Artificial walls for climbing, with varying difficulty or safety features', '2025-06-30 18:55:07'),
(45, 'Mini Golf Course', 'Courses for miniature golf, with themed obstacles or lighting', '2025-06-30 18:55:07'),
(46, 'Equestrian Trail', 'Trails for horseback riding, with stables or hitching posts', '2025-06-30 18:55:07'),
(47, 'Pump Track', 'Looped tracks for BMX or mountain biking, with rollers and berms', '2025-06-30 18:55:07'),
(48, 'Splash Pad', 'Water play areas with fountains or sprayers for children', '2025-06-30 18:55:07'),
(49, 'Barbecue Area', 'Designated spaces with large grills or fire pits for group cooking', '2025-06-30 18:55:07'),
(50, 'Public Art Installation', 'Sculptures, murals, or interactive art pieces for cultural enrichment', '2025-06-30 18:55:07'),
(51, 'Labyrinth', 'Paved or landscaped paths for meditative walking or reflection', '2025-06-30 18:55:07'),
(52, 'Outdoor Classroom', 'Spaces for educational programs or workshops, with seating or whiteboards', '2025-06-30 18:55:07'),
(53, 'Star Gazing Area', 'Open areas with low light pollution for astronomical observation', '2025-06-30 18:55:07'),
(54, 'Multi-Use Field', 'Open fields for various sports or activities, with flexible markings', '2025-06-30 18:55:07'),
(55, 'Waterfall', 'Natural or artificial waterfalls for scenic or recreational purposes', '2025-06-30 18:55:07'),
(56, 'Stream', 'Natural or managed streams for wading, fishing, or ecological study', '2025-06-30 18:55:07'),
(57, 'Orchard', 'Areas with fruit trees for picking, education, or conservation', '2025-06-30 18:55:07'),
(58, 'Historic Site', 'Preserved structures or areas of historical or cultural significance', '2025-06-30 18:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `type` enum('text','number','select','radio','checkbox','boolean') NOT NULL,
  `default_value` text,
  `options` text,
  `is_required` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `description`, `type`, `default_value`, `options`, `is_required`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pool Depth', 'Depth of swimming pool in feet', 'number', NULL, NULL, 0, 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(2, 'Court Surface', 'Type of court surface material', 'select', NULL, NULL, 0, 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(3, 'Lighting Available', 'Whether facility has lighting', 'boolean', NULL, NULL, 0, 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(4, 'Capacity', 'Maximum capacity or number of people', 'number', NULL, NULL, 0, 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(5, 'Age Group', 'Recommended age group for equipment', 'text', NULL, NULL, 0, 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(6, 'Accessibility Features', 'ADA accessibility features available', 'text', NULL, NULL, 0, 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_types`
--

CREATE TABLE `attribute_types` (
  `attribute_type_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attribute_types`
--

INSERT INTO `attribute_types` (`attribute_type_id`, `name`, `description`, `created_at`) VALUES
(1, 'court_count', 'Number of courts available for the amenity', '2025-06-30 19:37:31'),
(2, 'accessible', 'Whether the amenity is accessible for people with disabilities: yes or no', '2025-06-30 19:37:31'),
(3, 'dog_size', 'Size of dogs allowed in the dog park: small, large, all', '2025-06-30 19:37:31'),
(4, 'playground_type', 'Type of playground equipment or theme', '2025-06-30 19:40:06'),
(5, 'trail_type', 'Type of trail surface: paved, gravel, dirt', '2025-06-30 19:40:06'),
(6, 'sprayground_theme', 'Theme of the sprayground or splash pad', '2025-06-30 19:43:32');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'City Park', 'Municipal parks maintained by local government', 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(2, 'State Park', 'Parks maintained by state government', 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(3, 'National Park', 'Parks maintained by federal government', 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(4, 'Regional Park', 'Parks serving multiple communities', 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(5, 'Beach Park', 'Parks located on beaches or waterfront', 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32'),
(6, 'Sports Complex', 'Parks focused on sports and recreation facilities', 1, '2025-06-30 23:03:32', '2025-06-30 23:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `state_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `name`, `state_id`, `created_at`) VALUES
(1, 'Kansas City', 1, '2025-06-30 19:37:31'),
(2, 'Kansas City', 2, '2025-06-30 19:37:31'),
(3, 'Blue Springs', 1, '2025-06-30 19:40:06'),
(4, 'Merriam', 2, '2025-06-30 19:40:06'),
(5, 'Lenexa', 2, '2025-06-30 19:40:06'),
(6, 'Lee\'s Summit', 1, '2025-06-30 19:40:06'),
(7, 'Leawood', 2, '2025-06-30 19:40:06'),
(8, 'Overland Park', 2, '2025-06-30 19:40:06'),
(9, 'Independence', 1, '2025-06-30 19:43:32'),
(10, 'Raymore', 1, '2025-06-30 19:43:32'),
(11, 'Gladstone', 1, '2025-06-30 19:43:32'),
(12, 'Prairie Village', 2, '2025-06-30 19:43:32'),
(13, 'Olathe', 2, '2025-06-30 19:43:32'),
(14, 'Shawnee', 2, '2025-06-30 19:43:32'),
(15, 'North Kansas City', 1, '2025-06-30 19:43:32'),
(16, 'Parkville', 1, '2025-06-30 19:43:32'),
(25, 'Mission', 2, '2025-07-01 03:06:55'),
(26, 'Fairway', 2, '2025-07-01 03:14:44'),
(27, 'Bonner Springs', 2, '2025-07-01 03:14:44'),
(28, 'Roeland Park', 2, '2025-07-01 03:14:44'),
(29, 'Lawrence', 2, '2025-07-01 05:41:46'),
(30, 'Lawson', 1, '2025-07-01 05:41:46'),
(31, 'Weston', 1, '2025-07-01 05:41:46'),
(32, 'Knob Noster', 1, '2025-07-01 05:41:46'),
(33, 'El Dorado', 2, '2025-07-01 05:44:41'),
(34, 'Trenton', 1, '2025-07-01 05:44:41'),
(35, 'Kirksville', 1, '2025-07-01 05:44:41'),
(38, 'Marquette', 2, '2025-07-01 06:08:53'),
(39, 'Cheney', 2, '2025-07-01 06:08:53'),
(40, 'White Cloud', 2, '2025-07-01 06:08:53'),
(41, 'Columbia', 1, '2025-07-01 06:08:53'),
(42, 'Kaiser', 1, '2025-07-01 06:08:53'),
(43, 'Cameron', 1, '2025-07-01 06:08:53'),
(46, 'Toronto', 2, '2025-07-01 06:14:04'),
(47, 'Ozawkie', 2, '2025-07-01 06:14:04'),
(48, 'Larned', 2, '2025-07-01 06:14:04'),
(49, 'Laclede', 1, '2025-07-01 06:14:04'),
(50, 'Park Hills', 1, '2025-07-01 06:14:04'),
(51, 'Montgomery City', 1, '2025-07-01 06:14:04'),
(52, 'Shubert', 9, '2025-07-01 06:14:04'),
(53, 'Wilburton', 10, '2025-07-01 06:14:04'),
(54, 'West Siloam Springs', 10, '2025-07-01 06:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parks`
--

CREATE TABLE `parks` (
  `park_id` int NOT NULL,
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
  `is_featured` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `parks`
--

INSERT INTO `parks` (`park_id`, `name`, `address`, `zip_code_id`, `latitude`, `longitude`, `hours_open`, `hours_close`, `created_at`, `updated_at`, `category_id`, `description`, `city`, `state`, `phone`, `website`, `email`, `is_active`, `is_featured`) VALUES
(1, 'Swope Park', '6600 Swope Pkwy', 1, '39.003600', '-94.535800', '05:00:00', '00:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(2, 'Loose Park', '5200 Wornall Rd', 2, '39.033400', '-94.595300', '05:00:00', '00:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(3, 'Wyandotte County Lake Park', '9100 Leavenworth Rd', 3, '39.141700', '-94.797500', '06:00:00', '22:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(4, 'Penn Valley Park', '2500 Southwest Trfy', 4, '39.073600', '-94.589400', '05:00:00', '00:00:00', '2025-06-30 19:37:31', '2025-06-30 19:37:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(5, 'Mill Creek Park', '47th St & J C Nichols Pkwy', 5, '39.042500', '-94.587800', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(6, 'Kessler Park', '3000 Cliff Dr', 6, '39.110200', '-94.553100', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(7, 'Longview Lake Park', '11100 View High Dr', 7, '38.913600', '-94.475600', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(8, 'Fleming Park', '22807 Woods Chapel Rd', 12, '39.002800', '-94.312500', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(9, 'Penguin Park', '4125 NE Vivion Rd', 8, '39.184700', '-94.542300', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(10, 'Antioch Park', '6501 Antioch Rd', 9, '39.009200', '-94.685700', '06:00:00', '22:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(11, 'Sar-Ko-Par Trails Park', '14905 W 87th St', 10, '38.970700', '-94.759800', '06:00:00', '22:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(12, 'Legacy Park', '1201 NE Legacy Park Dr', 11, '38.905100', '-94.349700', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(13, 'Hidden Valley Park', '4029 Bellaire Ave', 5, '39.055600', '-94.598200', '05:00:00', '00:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(14, 'Gezer Park', '13251 Mission Rd', 13, '38.888500', '-94.628100', '06:00:00', '22:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(15, 'Pierson Park', '1800 S 55th St', 14, '39.126400', '-94.704200', '06:00:00', '22:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(16, 'Tomahawk Creek Park', '11950 S Pflumm Rd', 15, '38.911600', '-94.742100', '06:00:00', '22:00:00', '2025-06-30 19:40:06', '2025-06-30 19:40:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(17, 'Waterfall Park', '4501 NE 47th St', 21, '39.180700', '-94.529800', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(18, 'Blue Valley Park', '2301 Topping Ave', 36, '39.083300', '-94.516700', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(19, 'Budd Park', '5600 Budd Park Esplanade', 33, '39.111700', '-94.526700', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(20, 'Dr. Martin Luther King Jr. Park', '1900 E 51st St', 26, '39.035800', '-94.558900', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(21, 'Tower Park', '7500 Holmes Rd', 24, '38.991700', '-94.581100', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(22, 'Parade Park', '1700 John Buck O\'Neil Way', 25, '39.093100', '-94.561400', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(23, 'Gillham Park', '4100 Gillham Rd', 27, '39.053600', '-94.576900', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(24, 'Shawnee Mission Park', '7900 Renner Rd', 28, '38.983900', '-94.776900', '06:00:00', '22:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(25, 'Hodge Park', '7000 NE Barry Rd', 39, '39.242800', '-94.503600', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(26, 'The Grove Park', '1500 Benton Blvd', 25, '39.093900', '-94.548600', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(27, 'Spring Valley Park', '2700 E 27th St', 35, '39.076400', '-94.548100', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(28, 'Roanoke Park', '3601 Roanoke Rd', NULL, '39.058600', '-94.598600', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(29, 'Minor Park', '11100 Holmes Rd', 32, '38.925300', '-94.583900', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(30, 'Harmon Park', '7700 Mission Rd', 19, '38.987500', '-94.630800', '06:00:00', '22:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(31, 'Hawk\'s Nest Inclusive Park', '701 SW Lee Ann Dr', 17, '38.812500', '-94.468900', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(32, 'Arno Park', '7400 Arno Rd', 37, '39.001400', '-94.592800', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(33, 'Lea McKeighan Park', '1501 SW 3rd St', 11, '38.912800', '-94.395600', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(34, 'Macken Park', '2700 E 32nd Ave', 21, '39.162500', '-94.548900', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(35, 'English Landing Park', '870 W 2nd St', 39, '39.193600', '-94.685800', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(36, 'Gum Springs Park', '1100 NE Independence Ave', 38, '38.950300', '-94.353100', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(37, 'Cedar Lake Park', '15500 S Lone Elm Rd', 20, '38.843600', '-94.833600', '06:00:00', '22:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(38, 'Meadowbrook Park', '9101 Nall Ave', NULL, '38.962800', '-94.649700', '06:00:00', '22:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(39, 'Harmony Park', '3000 Agnes Ave', 35, '39.071900', '-94.551400', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(40, 'Ashland Square Park', '2300 Elmwood Ave', 34, '39.108600', '-94.533100', '05:00:00', '00:00:00', '2025-06-30 19:43:32', '2025-06-30 19:43:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(41, 'Waterfall Park', '4501 NE 47th St', 21, '39.180700', '-94.529800', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(42, 'Blue Valley Park', '2301 Topping Ave', 36, '39.083300', '-94.516700', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(43, 'Budd Park', '5600 Budd Park Esplanade', 33, '39.111700', '-94.526700', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(44, 'Dr. Martin Luther King Jr. Park', '1900 E 51st St', 26, '39.035800', '-94.558900', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(45, 'Tower Park', '7500 Holmes Rd', 24, '38.991700', '-94.581100', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(46, 'Parade Park', '1700 John Buck O\'Neil Way', 25, '39.093100', '-94.561400', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(47, 'Gillham Park', '4100 Gillham Rd', 27, '39.053600', '-94.576900', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(48, 'Shawnee Mission Park', '7900 Renner Rd', 28, '38.983900', '-94.776900', '06:00:00', '22:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(49, 'Hodge Park', '7000 NE Barry Rd', 39, '39.242800', '-94.503600', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(50, 'The Grove Park', '1500 Benton Blvd', 25, '39.093900', '-94.548600', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(51, 'Spring Valley Park', '2700 E 27th St', 35, '39.076400', '-94.548100', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(52, 'Roanoke Park', '3601 Roanoke Rd', NULL, '39.058600', '-94.598600', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(53, 'Minor Park', '11100 Holmes Rd', 32, '38.925300', '-94.583900', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(54, 'Harmon Park', '7700 Mission Rd', 19, '38.987500', '-94.630800', '06:00:00', '22:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(55, 'Hawk\'s Nest Inclusive Park', '701 SW Lee Ann Dr', 17, '38.812500', '-94.468900', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(56, 'Arno Park', '7400 Arno Rd', 37, '39.001400', '-94.592800', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(57, 'Lea McKeighan Park', '1501 SW 3rd St', 11, '38.912800', '-94.395600', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(58, 'Macken Park', '2700 E 32nd Ave', 21, '39.162500', '-94.548900', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(59, 'English Landing Park', '870 W 2nd St', 39, '39.193600', '-94.685800', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(60, 'Gum Springs Park', '1100 NE Independence Ave', 38, '38.950300', '-94.353100', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(61, 'Cedar Lake Park', '15500 S Lone Elm Rd', 20, '38.843600', '-94.833600', '06:00:00', '22:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(62, 'Meadowbrook Park', '9101 Nall Ave', NULL, '38.962800', '-94.649700', '06:00:00', '22:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(63, 'Harmony Park', '3000 Agnes Ave', 35, '39.071900', '-94.551400', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(64, 'Ashland Square Park', '2300 Elmwood Ave', 34, '39.108600', '-94.533100', '05:00:00', '00:00:00', '2025-06-30 19:45:21', '2025-06-30 19:45:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(65, 'Indian Creek Greenway', '10300 Indian Creek Pkwy', 64, '38.941200', '-94.702100', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(66, 'Sapling Grove Park', '8210 W 83rd St', 71, '38.978300', '-94.681400', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(67, 'Black Hoof Park', '9053 Monticello Rd', 65, '38.963700', '-94.840300', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(68, 'Stump Park', '4751 Woodland Dr', 66, '39.040200', '-94.695600', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(69, 'Santa Fe Trail Park', '2900 Nall Ave', 19, '38.973100', '-94.649800', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(70, 'Ironwoods Park', '14701 Mission Rd', 68, '38.863400', '-94.628900', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(71, 'Klamm Park', '2701 N 10th St', 69, '39.110800', '-94.632700', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(72, 'Heritage Park', '16050 Pflumm Rd', 70, '38.835600', '-94.744200', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(73, 'Bluejacket Park', '10150 Halsey St', 10, '38.945200', '-94.733100', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(74, 'Quail Creek Park', '7024 W 71st St', 71, '38.999700', '-94.667800', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(75, 'Franklin Park', '5101 W 67th St', 19, '39.006400', '-94.642300', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(76, 'City Park', '2008 N 5th St', 69, '39.123400', '-94.627100', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(77, 'Two Rivers Park', '8801 W 79th St', 65, '38.982700', '-94.789300', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(78, 'I-Lan Park', '12600 W 130th St', 68, '38.892300', '-94.632500', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(79, 'Swarner Park', '6300 W 87th St', 64, '38.972100', '-94.657800', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(80, 'Mahaffie Stagecoach Stop & Farm', '1200 E Kansas City Rd', 20, '38.898700', '-94.803200', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(81, 'Emerson Park', '1100 S Ridgeview Rd', 20, '38.867500', '-94.797800', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(82, 'Listowel Park', '1017 N 24th St', 69, '39.104200', '-94.652300', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(83, 'Porter Park', '3700 W 77th St', 19, '38.987600', '-94.628400', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(84, 'Oak Hill Park', '14700 W 79th St', 65, '38.984200', '-94.756700', '06:00:00', '22:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(85, 'Jacob L. Loose Park', '5100 Wornall Rd', 2, '39.033400', '-94.595300', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(86, 'Brookside Park', '5600 Brookside Blvd', 37, '39.025100', '-94.586700', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(87, 'Sunnyside Park', '8200 E 83rd St', 24, '38.975600', '-94.578900', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(88, 'Waterwell Park', '6000 E 117th St', 7, '38.910300', '-94.523400', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(89, 'Park Forest Park', '7500 E 107th St', 7, '38.933100', '-94.495200', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(90, 'Little Blue Valley Park', '8259 S Raytown Rd', NULL, '38.975800', '-94.463200', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(91, 'Happy Rock Park', '7600 NE Antioch Rd', 23, '39.233400', '-94.548700', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(92, 'George Owens Nature Park', '1601 S Speck Rd', 74, '39.081700', '-94.342100', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(93, 'Line Creek Park', '5900 NW Waukomis Dr', NULL, '39.201400', '-94.614200', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(94, 'Lakewood Greenway', '900 NE Lakewood Blvd', 38, '38.997300', '-94.368900', '05:00:00', '00:00:00', '2025-07-01 03:06:55', '2025-07-01 03:06:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(145, 'Burcham Park', '200 Indiana St', 91, '38.975800', '-95.229200', '06:00:00', '22:00:00', '2025-07-01 05:41:46', '2025-07-01 05:41:46', 1, 'A scenic park along the Kansas River with trails and picnic areas.', NULL, NULL, '785-832-3450', 'https://lawrenceks.org/lprd/parks/burchampark/', NULL, 1, 0),
(146, 'Clinton Lake State Park', '798 N 1415 Rd', 91, '38.921700', '-95.372500', '06:00:00', '22:00:00', '2025-07-01 05:41:46', '2025-07-01 05:41:46', 2, 'A state park offering camping, hiking, and water recreation on Clinton Lake.', NULL, NULL, '785-842-8562', 'https://ksoutdoors.com/State-Parks/Locations/Clinton', NULL, 1, 0),
(147, 'Watkins Mill State Park', '26600 Park Rd N', 92, '39.404300', '-94.260300', '06:00:00', '22:00:00', '2025-07-01 05:41:46', '2025-07-01 05:41:46', 2, 'A historic state park with a 19th-century woolen mill, camping, and trails.', NULL, NULL, '816-580-3381', 'https://mostateparks.com/park/watkins-mill-state-park', NULL, 1, 0),
(148, 'Weston Bend State Park', '16600 MO-45', 93, '39.394400', '-94.896300', '06:00:00', '22:00:00', '2025-07-01 05:41:46', '2025-07-01 05:41:46', 2, 'A state park along the Missouri River with hiking trails and scenic overlooks.', NULL, NULL, '816-640-5440', 'https://mostateparks.com/park/weston-bend-state-park', NULL, 1, 0),
(149, 'Knob Noster State Park', '873 SE 10 Rd', 94, '38.756200', '-93.569400', '06:00:00', '22:00:00', '2025-07-01 05:41:46', '2025-07-01 05:41:46', 2, 'A state park with hiking, camping, and diverse ecosystems near Whiteman AFB.', NULL, NULL, '660-563-2463', 'https://mostateparks.com/park/knob-noster-state-park', NULL, 1, 0),
(150, 'South Park', '1141 Massachusetts St', 95, '38.962100', '-95.236200', '06:00:00', '22:00:00', '2025-07-01 05:44:41', '2025-07-01 05:44:41', 1, 'A historic park in downtown Lawrence with a gazebo, playground, and community events.', NULL, NULL, '785-832-3450', 'https://lawrenceks.org/lprd/parks/southpark/', NULL, 1, 0),
(151, 'El Dorado State Park', '618 NE Bluestem Rd', 96, '37.863900', '-96.781400', '06:00:00', '22:00:00', '2025-07-01 05:44:41', '2025-07-01 05:44:41', 2, 'A large state park with camping, boating, and trails around El Dorado Lake.', NULL, NULL, '316-321-7180', 'https://ksoutdoors.com/State-Parks/Locations/El-Dorado', NULL, 1, 0),
(152, 'Crowder State Park', '76 MO-128', 97, '40.092500', '-93.663100', '06:00:00', '22:00:00', '2025-07-01 05:44:41', '2025-07-01 05:44:41', 2, 'A state park with hiking, camping, and a lake for fishing and canoeing.', NULL, NULL, '660-359-7400', 'https://mostateparks.com/park/crowder-state-park', NULL, 1, 0),
(153, 'Thousand Hills State Park', '20431 MO-157', 98, '40.153200', '-92.628300', '06:00:00', '22:00:00', '2025-07-01 05:44:41', '2025-07-01 05:44:41', 2, 'A state park with a lake, marina, and trails, known for Native American petroglyphs.', NULL, NULL, '660-665-7119', 'https://mostateparks.com/park/thousand-hills-state-park', NULL, 1, 0),
(154, 'Swope Memorial Park', '1000 E Meyer Blvd', 74, '39.062800', '-94.523900', '05:00:00', '23:00:00', '2025-07-01 05:44:41', '2025-07-01 05:44:41', 1, 'A city park with a playground, trails, and scenic views in Independence.', NULL, NULL, '816-325-7115', 'https://www.ci.independence.mo.us/ParksandRec/ParkDetail/25', NULL, 1, 0),
(155, 'Buford M. Watson Jr. Park', '727 Kentucky St', 95, '38.970600', '-95.236800', '06:00:00', '22:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 1, 'A downtown Lawrence park with a playground, picnic areas, and a historic gazebo.', NULL, NULL, '785-832-3450', 'https://lawrenceks.org/lprd/parks/bufordwatsonpark/', NULL, 1, 0),
(156, 'Kanopolis State Park', '200 Horsethief Rd', 100, '38.611400', '-97.975200', '06:00:00', '22:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 2, 'A state park with hiking, horseback riding, and water activities on Kanopolis Lake.', NULL, NULL, '785-546-2565', 'https://ksoutdoors.com/State-Parks/Locations/Kanopolis', NULL, 1, 0),
(157, 'Cheney State Park', '16000 NE 50th St', 101, '37.724100', '-97.779800', '06:00:00', '22:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 2, 'A state park offering camping, fishing, and boating on Cheney Reservoir.', NULL, NULL, '316-542-3664', 'https://ksoutdoors.com/State-Parks/Locations/Cheney', NULL, 1, 0),
(158, 'Lewis and Clark Interpretive Pavilion', '100 Main St', 102, '39.849200', '-95.146700', '08:00:00', '17:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 1, 'A historic site commemorating the Lewis and Clark expedition with an open-air pavilion.', NULL, NULL, '785-595-3204', 'https://www.nps.gov/lecl/planyourvisit/lewis-and-clark-interpretive-pavilion.htm', NULL, 1, 0),
(159, 'Finger Lakes State Park', '1505 E Peabody Rd', 103, '39.094700', '-92.318600', '06:00:00', '22:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 2, 'A state park with off-road vehicle trails, lakes for fishing, and camping.', NULL, NULL, '573-443-5315', 'https://mostateparks.com/park/finger-lakes-state-park', NULL, 1, 0),
(160, 'Lake of the Ozarks State Park', '403 MO-134', 104, '38.103400', '-92.666100', '06:00:00', '22:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 2, 'Missouri’s largest state park with boating, hiking, and cave tours.', NULL, NULL, '573-348-2694', 'https://mostateparks.com/park/lake-ozarks-state-park', NULL, 1, 0),
(161, 'Wallace State Park', '10621 NE Hwy 121', 105, '39.660300', '-94.216800', '06:00:00', '22:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 2, 'A family-friendly park with camping, fishing, and hiking trails.', NULL, NULL, '816-632-3745', 'https://mostateparks.com/park/wallace-state-park', NULL, 1, 0),
(162, 'McBaine Park', '2601 S Sterling Ave', 74, '39.069800', '-94.466700', '05:00:00', '23:00:00', '2025-07-01 06:08:53', '2025-07-01 06:08:53', 1, 'A neighborhood park in Independence with a playground and picnic facilities.', NULL, NULL, '816-325-7115', 'https://www.ci.independence.mo.us/ParksandRec/ParkDetail/14', NULL, 1, 0),
(163, 'Lyons Park', '700 N Iowa St', 95, '38.977200', '-95.250300', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 1, 'A neighborhood park in Lawrence with a playground, sports facilities, and picnic areas.', NULL, NULL, '785-832-3450', 'https://lawrenceks.org/lprd/parks/lyonspark/', NULL, 1, 0),
(164, 'Fall River State Park', '144 Hwy 105', 108, '37.663500', '-96.090200', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park with camping, hiking, and water recreation on Fall River Lake.', NULL, NULL, '620-637-2213', 'https://ksoutdoors.com/State-Parks/Locations/Fall-River', NULL, 1, 0),
(165, 'Perry State Park', '5441 Westlake Rd', 109, '39.146800', '-95.492600', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park offering camping, hiking, and boating on Perry Lake.', NULL, NULL, '785-246-3449', 'https://ksoutdoors.com/State-Parks/Locations/Perry', NULL, 1, 0),
(166, 'Fort Larned National Historic Site', '1767 KS Hwy 156', 110, '38.183900', '-99.218300', '08:30:00', '16:30:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 3, 'A preserved military post protecting the Santa Fe Trail, with historic buildings and interpretive programs.', NULL, NULL, '620-285-6911', 'https://www.nps.gov/fols/index.htm', NULL, 1, 0),
(167, 'Pershing State Park', '29277 Hwy 130', 111, '39.767200', '-93.622900', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park with wetlands, hiking trails, and camping opportunities.', NULL, NULL, '660-963-2299', 'https://mostateparks.com/park/pershing-state-park', NULL, 1, 0),
(168, 'St. Joe State Park', '2800 Pimville Rd', 112, '37.808600', '-90.516900', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park with off-road vehicle trails, camping, and lake recreation.', NULL, NULL, '573-431-1069', 'https://mostateparks.com/park/st-joe-state-park', NULL, 1, 0),
(169, 'Graham Cave State Park', '217 Hwy TT', 113, '38.904700', '-91.577800', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park featuring a historic cave and hiking trails.', NULL, NULL, '573-564-3476', 'https://mostateparks.com/park/graham-cave-state-park', NULL, 1, 0),
(170, 'Indian Cave State Park', '65296 720 Rd', 114, '40.263100', '-95.577400', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park with hiking trails, camping, and scenic views along the Missouri River.', NULL, NULL, '402-883-2575', 'https://outdoornebraska.gov/locations/indian-cave-state-park/', NULL, 1, 0),
(171, 'Robbers Cave State Park', '4628 OK-2', 115, '34.974600', '-95.675900', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park with caves, hiking trails, and camping in the Sans Bois Mountains.', NULL, NULL, '918-465-2562', 'https://www.travelok.com/state-parks/robbers-cave-state-park', NULL, 1, 0),
(172, 'Natural Falls State Park', '19225 E 578 Rd', 116, '36.173300', '-94.668400', '06:00:00', '22:00:00', '2025-07-01 06:14:04', '2025-07-01 06:14:04', 2, 'A state park featuring a 77-foot waterfall, hiking trails, and camping.', NULL, NULL, '918-422-5802', 'https://www.travelok.com/state-parks/natural-falls-state-park', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `state_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` char(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `name`, `code`, `created_at`) VALUES
(1, 'Missouri', 'MO', '2025-06-30 19:37:31'),
(2, 'Kansas', 'KS', '2025-06-30 19:37:31'),
(9, 'Nebraska', 'NE', '2025-07-01 06:14:03'),
(10, 'Oklahoma', 'OK', '2025-07-01 06:14:03'),
(13, 'Alabama', 'AL', '2025-07-01 06:20:31'),
(14, 'Alaska', 'AK', '2025-07-01 06:20:31'),
(15, 'Arizona', 'AZ', '2025-07-01 06:20:31'),
(16, 'Arkansas', 'AR', '2025-07-01 06:20:31'),
(17, 'California', 'CA', '2025-07-01 06:20:31'),
(18, 'Colorado', 'CO', '2025-07-01 06:20:31'),
(19, 'Connecticut', 'CT', '2025-07-01 06:20:31'),
(20, 'Delaware', 'DE', '2025-07-01 06:20:31'),
(21, 'Florida', 'FL', '2025-07-01 06:20:31'),
(22, 'Georgia', 'GA', '2025-07-01 06:20:31'),
(23, 'Hawaii', 'HI', '2025-07-01 06:20:31'),
(24, 'Idaho', 'ID', '2025-07-01 06:20:31'),
(25, 'Illinois', 'IL', '2025-07-01 06:20:31'),
(26, 'Indiana', 'IN', '2025-07-01 06:20:31'),
(27, 'Iowa', 'IA', '2025-07-01 06:20:31'),
(28, 'Kentucky', 'KY', '2025-07-01 06:20:31'),
(29, 'Louisiana', 'LA', '2025-07-01 06:20:31'),
(30, 'Maine', 'ME', '2025-07-01 06:20:31'),
(31, 'Maryland', 'MD', '2025-07-01 06:20:31'),
(32, 'Massachusetts', 'MA', '2025-07-01 06:20:31'),
(33, 'Michigan', 'MI', '2025-07-01 06:20:31'),
(34, 'Minnesota', 'MN', '2025-07-01 06:20:31'),
(35, 'Mississippi', 'MS', '2025-07-01 06:20:31'),
(36, 'Montana', 'MT', '2025-07-01 06:20:31'),
(37, 'Nevada', 'NV', '2025-07-01 06:20:31'),
(38, 'New Hampshire', 'NH', '2025-07-01 06:20:31'),
(39, 'New Jersey', 'NJ', '2025-07-01 06:20:31'),
(40, 'New Mexico', 'NM', '2025-07-01 06:20:31'),
(41, 'New York', 'NY', '2025-07-01 06:20:31'),
(42, 'North Carolina', 'NC', '2025-07-01 06:20:31'),
(43, 'North Dakota', 'ND', '2025-07-01 06:20:31'),
(44, 'Ohio', 'OH', '2025-07-01 06:20:31'),
(45, 'Oregon', 'OR', '2025-07-01 06:20:31'),
(46, 'Pennsylvania', 'PA', '2025-07-01 06:20:31'),
(47, 'Rhode Island', 'RI', '2025-07-01 06:20:31'),
(48, 'South Carolina', 'SC', '2025-07-01 06:20:31'),
(49, 'South Dakota', 'SD', '2025-07-01 06:20:31'),
(50, 'Tennessee', 'TN', '2025-07-01 06:20:31'),
(51, 'Texas', 'TX', '2025-07-01 06:20:31'),
(52, 'Utah', 'UT', '2025-07-01 06:20:31'),
(53, 'Vermont', 'VT', '2025-07-01 06:20:31'),
(54, 'Virginia', 'VA', '2025-07-01 06:20:31'),
(55, 'Washington', 'WA', '2025-07-01 06:20:31'),
(56, 'West Virginia', 'WV', '2025-07-01 06:20:31'),
(57, 'Wisconsin', 'WI', '2025-07-01 06:20:31'),
(58, 'Wyoming', 'WY', '2025-07-01 06:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `websites`
--

CREATE TABLE `websites` (
  `website_id` int NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `state_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `websites`
--

INSERT INTO `websites` (`website_id`, `url`, `description`, `state_id`, `created_at`, `updated_at`) VALUES
(1, 'https://lawrenceks.org', 'Official website for the City of Lawrence, Kansas, providing park and recreation information for city parks like Lyons Park and Buford M. Watson Jr. Park.', 2, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(2, 'https://ksoutdoors.com', 'Official Kansas state parks website, managed by the Kansas Department of Wildlife, Parks and Tourism, with details on parks like Fall River, Perry, and Kanopolis.', 2, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(3, 'https://mostateparks.com', 'Official Missouri state parks website, managed by the Missouri Department of Natural Resources, with information on parks like Pershing, St. Joe, and Graham Cave.', 1, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(4, 'https://www.nps.gov', 'Official National Park Service website, providing information on national parks and historic sites like Fort Larned National Historic Site.', NULL, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(5, 'https://outdoornebraska.gov', 'Official Nebraska state parks website, managed by the Nebraska Game and Parks Commission, with details on parks like Indian Cave State Park.', 9, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(6, 'https://www.travelok.com', 'Official Oklahoma state parks website, managed by Oklahoma Tourism and Recreation Department, with information on parks like Robbers Cave and Natural Falls.', 10, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(7, 'https://www.ci.independence.mo.us', 'Official website for the City of Independence, Missouri, providing park and recreation information for city parks like McBaine Park and Swope Memorial Park.', 1, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(8, 'https://www.alapark.com', 'Official Alabama state parks website, managed by the Alabama Department of Conservation and Natural Resources.', 13, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(9, 'https://dnr.alaska.gov/parks', 'Official Alaska state parks website, managed by the Alaska Department of Natural Resources.', 14, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(10, 'https://azstateparks.com', 'Official Arizona state parks website, managed by Arizona State Parks and Trails.', 15, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(11, 'https://www.arkansasstateparks.com', 'Official Arkansas state parks website, managed by the Arkansas Department of Parks, Heritage, and Tourism.', 16, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(12, 'https://www.parks.ca.gov', 'Official California state parks website, managed by the California Department of Parks and Recreation.', 17, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(13, 'https://cpw.state.co.us', 'Official Colorado state parks website, managed by Colorado Parks and Wildlife.', 18, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(14, 'https://www.ct.gov/deep/stateparks', 'Official Connecticut state parks website, managed by the Connecticut Department of Energy and Environmental Protection.', 19, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(15, 'https://destateparks.com', 'Official Delaware state parks website, managed by the Delaware Department of Natural Resources and Environmental Control.', 20, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(16, 'https://www.floridastateparks.org', 'Official Florida state parks website, managed by the Florida Department of Environmental Protection.', 21, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(17, 'https://gastateparks.org', 'Official Georgia state parks website, managed by the Georgia Department of Natural Resources.', 22, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(18, 'https://dlnr.hawaii.gov/dsp', 'Official Hawaii state parks website, managed by the Hawaii Department of Land and Natural Resources.', 23, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(19, 'https://parksandrecreation.idaho.gov', 'Official Idaho state parks website, managed by the Idaho Department of Parks and Recreation.', 24, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(20, 'https://www2.illinois.gov/dnr/parks', 'Official Illinois state parks website, managed by the Illinois Department of Natural Resources.', 25, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(21, 'https://www.in.gov/dnr/state-parks', 'Official Indiana state parks website, managed by the Indiana Department of Natural Resources.', 26, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(22, 'https://www.iowadnr.gov/Places-to-Go/State-Parks', 'Official Iowa state parks website, managed by the Iowa Department of Natural Resources.', 27, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(23, 'https://parks.ky.gov', 'Official Kentucky state parks website, managed by the Kentucky Department of Parks.', 28, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(24, 'https://www.crt.state.la.us/louisiana-state-parks', 'Official Louisiana state parks website, managed by the Louisiana Office of State Parks.', 29, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(25, 'https://www.maine.gov/dacf/parks', 'Official Maine state parks website, managed by the Maine Department of Agriculture, Conservation and Forestry.', 30, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(26, 'https://dnr.maryland.gov/Publiclands/Pages/default.aspx', 'Official Maryland state parks website, managed by the Maryland Department of Natural Resources.', 31, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(27, 'https://www.mass.gov/orgs/department-of-conservation-and-recreation', 'Official Massachusetts state parks website, managed by the Massachusetts Department of Conservation and Recreation.', 32, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(28, 'https://www.michigan.gov/dnr/places/state-parks', 'Official Michigan state parks website, managed by the Michigan Department of Natural Resources.', 33, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(29, 'https://www.dnr.state.mn.us/state_parks', 'Official Minnesota state parks website, managed by the Minnesota Department of Natural Resources.', 34, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(30, 'https://www.mdwfp.com/parks-recreation/state-parks', 'Official Mississippi state parks website, managed by the Mississippi Department of Wildlife, Fisheries, and Parks.', 35, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(31, 'https://www.mt.gov/stateparks', 'Official Montana state parks website, managed by Montana Fish, Wildlife & Parks.', 36, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(32, 'https://www.parks.nv.gov', 'Official Nevada state parks website, managed by the Nevada Division of State Parks.', 37, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(33, 'https://www.nhstateparks.org', 'Official New Hampshire state parks website, managed by the New Hampshire Division of Parks and Recreation.', 38, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(34, 'https://www.nj.gov/dep/parksandforests', 'Official New Jersey state parks website, managed by the New Jersey Department of Environmental Protection.', 39, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(35, 'https://www.emnrd.nm.gov/state-parks', 'Official New Mexico state parks website, managed by the New Mexico Energy, Minerals and Natural Resources Department.', 40, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(36, 'https://parks.ny.gov', 'Official New York state parks website, managed by the New York State Office of Parks, Recreation and Historic Preservation.', 41, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(37, 'https://www.ncparks.gov', 'Official North Carolina state parks website, managed by the North Carolina Division of Parks and Recreation.', 42, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(38, 'https://www.parkrec.nd.gov', 'Official North Dakota state parks website, managed by the North Dakota Parks and Recreation Department.', 43, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(39, 'https://ohiodnr.gov/go-and-do/plan-a-visit/find-a-property', 'Official Ohio state parks website, managed by the Ohio Department of Natural Resources.', 44, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(40, 'https://www.oregon.gov/oprd/parks', 'Official Oregon state parks website, managed by the Oregon Parks and Recreation Department.', 45, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(41, 'https://www.dcnr.pa.gov/StateParks', 'Official Pennsylvania state parks website, managed by the Pennsylvania Department of Conservation and Natural Resources.', 46, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(42, 'https://riparks.com', 'Official Rhode Island state parks website, managed by the Rhode Island Department of Environmental Management.', 47, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(43, 'https://www.southcarolinaparks.com', 'Official South Carolina state parks website, managed by the South Carolina Department of Parks, Recreation & Tourism.', 48, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(44, 'https://gfp.sd.gov/parks', 'Official South Dakota state parks website, managed by the South Dakota Game, Fish and Parks Department.', 49, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(45, 'https://www.tnstateparks.com', 'Official Tennessee state parks website, managed by the Tennessee Department of Environment and Conservation.', 50, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(46, 'https://tpwd.texas.gov/state-parks', 'Official Texas state parks website, managed by the Texas Parks and Wildlife Department.', 51, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(47, 'https://stateparks.utah.gov', 'Official Utah state parks website, managed by the Utah Division of State Parks.', 52, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(48, 'https://vtstateparks.com', 'Official Vermont state parks website, managed by the Vermont Department of Forests, Parks and Recreation.', 53, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(49, 'https://www.dcr.virginia.gov/state-parks', 'Official Virginia state parks website, managed by the Virginia Department of Conservation and Recreation.', 54, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(50, 'https://parks.wa.gov', 'Official Washington state parks website, managed by the Washington State Parks and Recreation Commission.', 55, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(51, 'https://wvstateparks.com', 'Official West Virginia state parks website, managed by the West Virginia Division of Natural Resources.', 56, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(52, 'https://dnr.wisconsin.gov/topic/parks', 'Official Wisconsin state parks website, managed by the Wisconsin Department of Natural Resources.', 57, '2025-07-01 06:20:31', '2025-07-01 06:20:31'),
(53, 'https://wyoparks.wyo.gov', 'Official Wyoming state parks website, managed by the Wyoming State Parks, Historic Sites & Trails.', 58, '2025-07-01 06:20:31', '2025-07-01 06:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `zip_codes`
--

CREATE TABLE `zip_codes` (
  `zip_code_id` int NOT NULL,
  `code` varchar(20) NOT NULL,
  `city_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zip_codes`
--

INSERT INTO `zip_codes` (`zip_code_id`, `code`, `city_id`, `created_at`) VALUES
(1, '64132', 1, '2025-06-30 19:37:31'),
(2, '64112', 1, '2025-06-30 19:37:31'),
(3, '66109', 2, '2025-06-30 19:37:31'),
(4, '64108', 1, '2025-06-30 19:37:31'),
(5, '64105', 1, '2025-06-30 19:40:06'),
(6, '64106', 1, '2025-06-30 19:40:06'),
(7, '64134', 1, '2025-06-30 19:40:06'),
(8, '64116', 1, '2025-06-30 19:40:06'),
(9, '66202', 4, '2025-06-30 19:40:06'),
(10, '66215', 5, '2025-06-30 19:40:06'),
(11, '64081', 6, '2025-06-30 19:40:06'),
(12, '64139', 1, '2025-06-30 19:40:06'),
(13, '66211', 7, '2025-06-30 19:40:06'),
(14, '66104', 2, '2025-06-30 19:40:06'),
(15, '66223', 8, '2025-06-30 19:40:06'),
(16, '64055', 9, '2025-06-30 19:43:32'),
(17, '64083', 10, '2025-06-30 19:43:32'),
(18, '64118', 11, '2025-06-30 19:43:32'),
(19, '66208', 12, '2025-06-30 19:43:32'),
(20, '66061', 13, '2025-06-30 19:43:32'),
(21, '64117', 15, '2025-06-30 19:43:32'),
(22, '64133', 1, '2025-06-30 19:43:32'),
(23, '64119', 1, '2025-06-30 19:43:32'),
(24, '64131', 1, '2025-06-30 19:43:32'),
(25, '64127', 1, '2025-06-30 19:43:32'),
(26, '64130', 1, '2025-06-30 19:43:32'),
(27, '64110', 1, '2025-06-30 19:43:32'),
(28, '66216', 14, '2025-06-30 19:43:32'),
(29, '64155', 1, '2025-06-30 19:43:32'),
(30, '64109', 1, '2025-06-30 19:43:32'),
(31, '64114', 1, '2025-06-30 19:43:32'),
(32, '64137', 1, '2025-06-30 19:43:32'),
(33, '64123', 1, '2025-06-30 19:43:32'),
(34, '64124', 1, '2025-06-30 19:43:32'),
(35, '64128', 1, '2025-06-30 19:43:32'),
(36, '64129', 1, '2025-06-30 19:43:32'),
(37, '64113', 1, '2025-06-30 19:43:32'),
(38, '64064', 6, '2025-06-30 19:43:32'),
(39, '64156', 1, '2025-06-30 19:43:32'),
(64, '66212', 8, '2025-07-01 03:06:55'),
(65, '66219', 5, '2025-07-01 03:06:55'),
(66, '66203', 14, '2025-07-01 03:06:55'),
(67, '66207', 12, '2025-07-01 03:06:55'),
(68, '66209', 7, '2025-07-01 03:06:55'),
(69, '66106', 2, '2025-07-01 03:06:55'),
(70, '66062', 13, '2025-07-01 03:06:55'),
(71, '66204', 8, '2025-07-01 03:06:55'),
(72, '64146', 1, '2025-07-01 03:06:55'),
(73, '64145', 1, '2025-07-01 03:06:55'),
(74, '64050', 9, '2025-07-01 03:06:55'),
(75, '66205', 25, '2025-07-01 03:06:55'),
(77, '66210', 8, '2025-07-01 03:14:45'),
(78, '66213', 8, '2025-07-01 03:14:45'),
(79, '66214', 5, '2025-07-01 03:14:45'),
(80, '66205', 26, '2025-07-01 03:14:45'),
(81, '66007', 27, '2025-07-01 03:14:45'),
(82, '64102', 1, '2025-07-01 03:14:45'),
(83, '64126', 1, '2025-07-01 03:14:45'),
(84, '64138', 1, '2025-07-01 03:14:45'),
(85, '64120', 1, '2025-07-01 03:14:45'),
(86, '64111', 1, '2025-07-01 03:14:45'),
(87, '64052', 9, '2025-07-01 03:14:45'),
(88, '64151', 1, '2025-07-01 03:14:45'),
(89, '66205', 28, '2025-07-01 03:14:45'),
(91, '66047', 29, '2025-07-01 05:41:46'),
(92, '64062', 30, '2025-07-01 05:41:46'),
(93, '64098', 31, '2025-07-01 05:41:46'),
(94, '65336', 32, '2025-07-01 05:41:46'),
(95, '66044', 29, '2025-07-01 05:44:41'),
(96, '67042', 33, '2025-07-01 05:44:41'),
(97, '64683', 34, '2025-07-01 05:44:41'),
(98, '63501', 35, '2025-07-01 05:44:41'),
(100, '67456', 38, '2025-07-01 06:08:53'),
(101, '67025', 39, '2025-07-01 06:08:53'),
(102, '66002', 40, '2025-07-01 06:08:53'),
(103, '65203', 41, '2025-07-01 06:08:53'),
(104, '65047', 42, '2025-07-01 06:08:53'),
(105, '64429', 43, '2025-07-01 06:08:53'),
(108, '66777', 46, '2025-07-01 06:14:04'),
(109, '66070', 47, '2025-07-01 06:14:04'),
(110, '67550', 48, '2025-07-01 06:14:04'),
(111, '64651', 49, '2025-07-01 06:14:04'),
(112, '63601', 50, '2025-07-01 06:14:04'),
(113, '63361', 51, '2025-07-01 06:14:04'),
(114, '68437', 52, '2025-07-01 06:14:04'),
(115, '74578', 53, '2025-07-01 06:14:04'),
(116, '74338', 54, '2025-07-01 06:14:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`amenity_id`),
  ADD KEY `idx_amenities_park_id` (`park_id`),
  ADD KEY `idx_amenities_category_id` (`category_id`);

--
-- Indexes for table `amenity_attributes`
--
ALTER TABLE `amenity_attributes`
  ADD PRIMARY KEY (`attribute_id`),
  ADD UNIQUE KEY `unique_amenity_attribute` (`amenity_id`,`attribute_type_id`),
  ADD KEY `idx_amenity_attributes_amenity_id` (`amenity_id`),
  ADD KEY `idx_amenity_attributes_attribute_type_id` (`attribute_type_id`);

--
-- Indexes for table `amenity_categories`
--
ALTER TABLE `amenity_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `attribute_types`
--
ALTER TABLE `attribute_types`
  ADD PRIMARY KEY (`attribute_type_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `unique_city_state` (`name`,`state_id`),
  ADD KEY `idx_cities_state_id` (`state_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parks`
--
ALTER TABLE `parks`
  ADD PRIMARY KEY (`park_id`),
  ADD KEY `idx_parks_zip_code_id` (`zip_code_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `websites`
--
ALTER TABLE `websites`
  ADD PRIMARY KEY (`website_id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `idx_websites_state_id` (`state_id`);

--
-- Indexes for table `zip_codes`
--
ALTER TABLE `zip_codes`
  ADD PRIMARY KEY (`zip_code_id`),
  ADD UNIQUE KEY `unique_zip_city` (`code`,`city_id`),
  ADD KEY `idx_zip_codes_city_id` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `amenity_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `amenity_attributes`
--
ALTER TABLE `amenity_attributes`
  MODIFY `attribute_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `amenity_categories`
--
ALTER TABLE `amenity_categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `attribute_types`
--
ALTER TABLE `attribute_types`
  MODIFY `attribute_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parks`
--
ALTER TABLE `parks`
  MODIFY `park_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `state_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `websites`
--
ALTER TABLE `websites`
  MODIFY `website_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `zip_codes`
--
ALTER TABLE `zip_codes`
  MODIFY `zip_code_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `amenities`
--
ALTER TABLE `amenities`
  ADD CONSTRAINT `amenities_ibfk_1` FOREIGN KEY (`park_id`) REFERENCES `parks` (`park_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `amenities_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `amenity_categories` (`category_id`) ON DELETE RESTRICT;

--
-- Constraints for table `amenity_attributes`
--
ALTER TABLE `amenity_attributes`
  ADD CONSTRAINT `amenity_attributes_ibfk_1` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `amenity_attributes_ibfk_2` FOREIGN KEY (`attribute_type_id`) REFERENCES `attribute_types` (`attribute_type_id`) ON DELETE RESTRICT;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`state_id`) ON DELETE RESTRICT;

--
-- Constraints for table `parks`
--
ALTER TABLE `parks`
  ADD CONSTRAINT `parks_ibfk_1` FOREIGN KEY (`zip_code_id`) REFERENCES `zip_codes` (`zip_code_id`) ON DELETE SET NULL;

--
-- Constraints for table `websites`
--
ALTER TABLE `websites`
  ADD CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`state_id`) ON DELETE SET NULL;

--
-- Constraints for table `zip_codes`
--
ALTER TABLE `zip_codes`
  ADD CONSTRAINT `zip_codes_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
