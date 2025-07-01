-- Fix existing database for admin system compatibility
-- Run this on your existing parks_db database

-- Add missing columns to parks table
ALTER TABLE `parks` 
ADD COLUMN `category_id` int DEFAULT NULL,
ADD COLUMN `description` text,
ADD COLUMN `city` varchar(100),
ADD COLUMN `state` varchar(50),
ADD COLUMN `phone` varchar(20),
ADD COLUMN `website` varchar(255),
ADD COLUMN `email` varchar(100),
ADD COLUMN `is_active` tinyint(1) DEFAULT 1,
ADD COLUMN `is_featured` tinyint(1) DEFAULT 0;

-- Create simple categories table for admin (separate from amenity_categories)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create simple attributes table for admin
CREATE TABLE IF NOT EXISTS `attributes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text,
    `type` enum('text','number','select','radio','checkbox','boolean') NOT NULL,
    `default_value` text,
    `options` text,
    `is_required` tinyint(1) DEFAULT 0,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert sample data for categories
INSERT INTO `categories` (`name`, `description`) VALUES 
('City Park', 'Municipal parks maintained by local government'),
('State Park', 'Parks maintained by state government'),
('National Park', 'Parks maintained by federal government'),
('Regional Park', 'Parks serving multiple communities'),
('Beach Park', 'Parks located on beaches or waterfront'),
('Sports Complex', 'Parks focused on sports and recreation facilities')
ON DUPLICATE KEY UPDATE name = name;

-- Insert sample data for attributes
INSERT INTO `attributes` (`name`, `description`, `type`) VALUES 
('Pool Depth', 'Depth of swimming pool in feet', 'number'),
('Court Surface', 'Type of court surface material', 'select'),
('Lighting Available', 'Whether facility has lighting', 'boolean'),
('Capacity', 'Maximum capacity or number of people', 'number'),
('Age Group', 'Recommended age group for equipment', 'text'),
('Accessibility Features', 'ADA accessibility features available', 'text')
ON DUPLICATE KEY UPDATE name = name;
