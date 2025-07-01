-- MySQL compatible SQL for AmyoParks missing tables
-- Run this in phpMyAdmin or MySQL client

-- First, add missing columns to existing parks table (use ADD COLUMN, not ADD COLUMN IF NOT EXISTS for MySQL compatibility)
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'category_id') = 0,
    'ALTER TABLE `parks` ADD COLUMN `category_id` int DEFAULT NULL',
    'SELECT "category_id column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'description') = 0,
    'ALTER TABLE `parks` ADD COLUMN `description` text',
    'SELECT "description column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'city') = 0,
    'ALTER TABLE `parks` ADD COLUMN `city` varchar(100)',
    'SELECT "city column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'state') = 0,
    'ALTER TABLE `parks` ADD COLUMN `state` varchar(50)',
    'SELECT "state column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'phone') = 0,
    'ALTER TABLE `parks` ADD COLUMN `phone` varchar(20)',
    'SELECT "phone column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'website') = 0,
    'ALTER TABLE `parks` ADD COLUMN `website` varchar(255)',
    'SELECT "website column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'email') = 0,
    'ALTER TABLE `parks` ADD COLUMN `email` varchar(100)',
    'SELECT "email column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'is_active') = 0,
    'ALTER TABLE `parks` ADD COLUMN `is_active` boolean DEFAULT TRUE',
    'SELECT "is_active column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parks' AND COLUMN_NAME = 'is_featured') = 0,
    'ALTER TABLE `parks` ADD COLUMN `is_featured` boolean DEFAULT FALSE',
    'SELECT "is_featured column already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create admin_users table
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

-- Create contacts table
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

-- Create amenities table
CREATE TABLE IF NOT EXISTS `amenities` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text,
    `icon` varchar(100),
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
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

-- Create zip_codes table
CREATE TABLE IF NOT EXISTS `zip_codes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `zip_code` varchar(10) NOT NULL UNIQUE,
    `city_id` int,
    `state_id` int,
    `latitude` decimal(10,8),
    `longitude` decimal(11,8),
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`state_id`) REFERENCES `states`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert sample data
INSERT IGNORE INTO `states` (`name`, `abbreviation`) VALUES 
('California', 'CA'),
('Texas', 'TX'),
('Florida', 'FL'),
('New York', 'NY'),
('Illinois', 'IL');

INSERT IGNORE INTO `categories` (`name`, `description`) VALUES 
('City Park', 'Municipal parks maintained by local government'),
('State Park', 'Parks maintained by state government'),
('National Park', 'Parks maintained by federal government'),
('Regional Park', 'Parks serving multiple communities');

INSERT IGNORE INTO `amenities` (`name`, `description`, `icon`) VALUES 
('Swimming Pool', 'Public swimming pool facility', 'fas fa-swimming-pool'),
('Basketball Court', 'Basketball court for recreational play', 'fas fa-basketball-ball'),
('Tennis Court', 'Tennis court for recreational play', 'fas fa-table-tennis'),
('Playground', 'Children playground equipment', 'fas fa-child'),
('Picnic Area', 'Designated picnic areas with tables', 'fas fa-utensils'),
('Walking Trail', 'Walking and hiking trails', 'fas fa-walking'),
('Baseball Field', 'Baseball or softball field', 'fas fa-baseball-ball'),
('Soccer Field', 'Soccer field for recreational play', 'fas fa-futbol'),
('Dog Park', 'Off-leash area for dogs', 'fas fa-dog'),
('Restrooms', 'Public restroom facilities', 'fas fa-restroom');

-- Insert default admin user (username: admin, password: Admin123!)
INSERT IGNORE INTO `admin_users` (`username`, `password_hash`, `email`, `is_active`) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@amyoparks.com', 1);
