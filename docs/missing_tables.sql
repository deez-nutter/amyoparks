-- SQL to create missing tables for AmyoParks admin system
-- Run this SQL to add the admin_users and contacts tables

-- First, add missing columns to existing parks table
ALTER TABLE `parks` 
ADD COLUMN IF NOT EXISTS `category_id` int DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `description` text,
ADD COLUMN IF NOT EXISTS `city` varchar(100),
ADD COLUMN IF NOT EXISTS `state` varchar(50),
ADD COLUMN IF NOT EXISTS `phone` varchar(20),
ADD COLUMN IF NOT EXISTS `website` varchar(255),
ADD COLUMN IF NOT EXISTS `email` varchar(100),
ADD COLUMN IF NOT EXISTS `is_active` boolean DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS `is_featured` boolean DEFAULT FALSE;

-- Create admin_users table for admin login
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL UNIQUE,
    `password_hash` varchar(255) NOT NULL,
    `email` varchar(100),
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create contacts table for contact form submissions
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `phone` varchar(20),
    `subject` varchar(200),
    `message` text NOT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create states table
CREATE TABLE IF NOT EXISTS `states` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `abbreviation` varchar(2) NOT NULL UNIQUE,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create cities table
CREATE TABLE IF NOT EXISTS `cities` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `state_id` int,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`state_id`) REFERENCES `states`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create categories table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create amenities table
CREATE TABLE IF NOT EXISTS `amenities` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `icon` varchar(100),
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create attributes table
CREATE TABLE IF NOT EXISTS `attributes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `type` enum('text','number','select','radio','checkbox','boolean') NOT NULL,
    `default_value` text,
    `options` text,
    `is_required` boolean DEFAULT FALSE,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create park_amenities junction table
CREATE TABLE IF NOT EXISTS `park_amenities` (
    `park_id` int NOT NULL,
    `amenity_id` int NOT NULL,
    PRIMARY KEY (`park_id`, `amenity_id`),
    FOREIGN KEY (`park_id`) REFERENCES `parks`(`park_id`) ON DELETE CASCADE,
    FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create park_attributes table
CREATE TABLE IF NOT EXISTS `park_attributes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `park_id` int NOT NULL,
    `attribute_id` int NOT NULL,
    `value` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`park_id`) REFERENCES `parks`(`park_id`) ON DELETE CASCADE,
    FOREIGN KEY (`attribute_id`) REFERENCES `attributes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert some sample data
INSERT INTO `states` (`name`, `abbreviation`) VALUES 
('California', 'CA'),
('Texas', 'TX'),
('Florida', 'FL'),
('New York', 'NY')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO `categories` (`name`, `description`) VALUES 
('City Park', 'Municipal parks maintained by local government'),
('State Park', 'Parks maintained by state government'),
('National Park', 'Parks maintained by federal government'),
('Regional Park', 'Parks serving multiple communities')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO `amenities` (`name`, `description`, `icon`) VALUES 
('Swimming Pool', 'Public swimming pool facility', 'fas fa-swimming-pool'),
('Basketball Court', 'Basketball court for recreational play', 'fas fa-basketball-ball'),
('Tennis Court', 'Tennis court for recreational play', 'fas fa-table-tennis'),
('Playground', 'Children playground equipment', 'fas fa-child'),
('Picnic Area', 'Designated picnic areas with tables', 'fas fa-utensils'),
('Walking Trail', 'Walking and hiking trails', 'fas fa-walking'),
('Baseball Field', 'Baseball or softball field', 'fas fa-baseball-ball'),
('Soccer Field', 'Soccer field for recreational play', 'fas fa-futbol'),
('Dog Park', 'Off-leash area for dogs', 'fas fa-dog'),
('Restrooms', 'Public restroom facilities', 'fas fa-restroom')
ON DUPLICATE KEY UPDATE name = name;

-- Insert default admin user (username: admin, password: Admin123!)
INSERT INTO `admin_users` (`username`, `password_hash`, `email`, `is_active`) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@amyoparks.com', 1)
ON DUPLICATE KEY UPDATE username = username;
