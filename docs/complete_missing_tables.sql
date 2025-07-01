-- Complete missing tables SQL for AmyoParks database
-- This creates all missing tables based on the expected code structure

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

-- Create zip_codes table (for locations admin)
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

-- Create amenity_categories table (for amenities structure)
CREATE TABLE IF NOT EXISTS `amenity_categories` (
    `category_id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create attribute_types table
CREATE TABLE IF NOT EXISTS `attribute_types` (
    `attribute_type_id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL UNIQUE,
    `description` text,
    `data_type` enum('text','number','boolean','select') NOT NULL DEFAULT 'text',
    `options` text,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`attribute_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create amenities table (with proper structure for park-details.php)
CREATE TABLE IF NOT EXISTS `amenities` (
    `amenity_id` int NOT NULL AUTO_INCREMENT,
    `park_id` int NOT NULL,
    `category_id` int NOT NULL,
    `instance_name` varchar(200),
    `description` text,
    `is_active` boolean DEFAULT TRUE,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`amenity_id`),
    FOREIGN KEY (`park_id`) REFERENCES `parks`(`park_id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `amenity_categories`(`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create amenity_attributes table
CREATE TABLE IF NOT EXISTS `amenity_attributes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `amenity_id` int NOT NULL,
    `attribute_type_id` int NOT NULL,
    `attribute_value` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`amenity_id`) ON DELETE CASCADE,
    FOREIGN KEY (`attribute_type_id`) REFERENCES `attribute_types`(`attribute_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create simple attributes table for admin (compatible with existing admin code)
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

-- Insert sample data
INSERT INTO `states` (`name`, `abbreviation`) VALUES 
('California', 'CA'),
('Texas', 'TX'),
('Florida', 'FL'),
('New York', 'NY'),
('Illinois', 'IL'),
('Pennsylvania', 'PA'),
('Ohio', 'OH'),
('Georgia', 'GA'),
('North Carolina', 'NC'),
('Michigan', 'MI')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO `categories` (`name`, `description`) VALUES 
('City Park', 'Municipal parks maintained by local government'),
('State Park', 'Parks maintained by state government'),
('National Park', 'Parks maintained by federal government'),
('Regional Park', 'Parks serving multiple communities'),
('Beach Park', 'Parks located on beaches or waterfront'),
('Sports Complex', 'Parks focused on sports and recreation facilities')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO `amenity_categories` (`name`, `description`) VALUES 
('Sports Facilities', 'Athletic courts, fields, and sports equipment'),
('Aquatic Facilities', 'Swimming pools, water features, and aquatic amenities'),
('Recreation Areas', 'General recreation and leisure facilities'),
('Children Facilities', 'Playgrounds and child-focused amenities'),
('Accessibility Features', 'ADA compliant and accessible facilities'),
('Natural Features', 'Trails, gardens, and natural amenities'),
('Utilities', 'Restrooms, parking, and utility facilities'),
('Events & Gatherings', 'Pavilions, picnic areas, and event spaces')
ON DUPLICATE KEY UPDATE name = name;

INSERT INTO `attribute_types` (`name`, `description`, `data_type`) VALUES 
('Length', 'Length measurement in feet', 'number'),
('Width', 'Width measurement in feet', 'number'),
('Depth', 'Depth measurement in feet', 'number'),
('Surface Type', 'Type of surface material', 'select'),
('Lighting', 'Whether facility has lighting', 'boolean'),
('Fence Height', 'Height of fence in feet', 'number'),
('Capacity', 'Maximum capacity or number of people', 'number'),
('Age Group', 'Recommended age group', 'select'),
('Accessibility', 'ADA accessibility features', 'text'),
('Hours', 'Operating hours or availability', 'text')
ON DUPLICATE KEY UPDATE name = name;

-- Insert default admin user (username: admin, password: Admin123!)
INSERT INTO `admin_users` (`username`, `password_hash`, `email`, `is_active`) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@amyoparks.com', 1)
ON DUPLICATE KEY UPDATE username = username;
