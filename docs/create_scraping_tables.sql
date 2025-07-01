-- Create websites table for scraping portal
CREATE TABLE IF NOT EXISTS `websites` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `base_url` varchar(500) NOT NULL,
    `parks_list_url` varchar(500),
    `park_detail_url_pattern` varchar(500),
    `scraping_config` JSON,
    `last_scraped` timestamp NULL,
    `total_parks_found` int DEFAULT 0,
    `total_parks_imported` int DEFAULT 0,
    `status` enum('active','inactive','error') DEFAULT 'active',
    `notes` text,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Create scraping_logs table to track scraping activities
CREATE TABLE IF NOT EXISTS `scraping_logs` (
    `id` int NOT NULL AUTO_INCREMENT,
    `website_id` int NOT NULL,
    `action` varchar(100) NOT NULL,
    `url_scraped` varchar(500),
    `parks_found` int DEFAULT 0,
    `parks_imported` int DEFAULT 0,
    `errors` text,
    `execution_time` decimal(10,3),
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`website_id`) REFERENCES `websites`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert sample website configurations
INSERT INTO `websites` (`name`, `base_url`, `parks_list_url`, `scraping_config`, `notes`) VALUES 
(
    'Kansas City Parks',
    'https://www.kcparks.org',
    'https://www.kcparks.org/places/parks/',
    JSON_OBJECT(
        'park_name_selector', '.park-title, h1, .entry-title',
        'park_address_selector', '.park-address, .address',
        'park_description_selector', '.park-description, .entry-content',
        'amenities_selector', '.amenities-list li, .facilities li',
        'hours_selector', '.hours, .park-hours',
        'phone_selector', '.phone, .contact-phone'
    ),
    'Kansas City Parks and Recreation Department'
),
(
    'Johnson County Parks',
    'https://www.jcprd.com',
    'https://www.jcprd.com/facilities/parks/',
    JSON_OBJECT(
        'park_name_selector', '.facility-title, h1',
        'park_address_selector', '.facility-address',
        'park_description_selector', '.facility-description',
        'amenities_selector', '.amenities li',
        'hours_selector', '.hours-info',
        'phone_selector', '.contact-info .phone'
    ),
    'Johnson County Park and Recreation District'
),
(
    'Jackson County Parks',
    'https://www.jacksongov.org',
    'https://www.jacksongov.org/parks',
    JSON_OBJECT(
        'park_name_selector', '.park-name, h1',
        'park_address_selector', '.location, .address',
        'park_description_selector', '.description, .content',
        'amenities_selector', '.features li, .amenities li',
        'hours_selector', '.operating-hours',
        'phone_selector', '.contact .phone'
    ),
    'Jackson County Parks + Recreation'
);
