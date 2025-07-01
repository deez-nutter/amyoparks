-- Add scraping functionality to existing AmyoParks database
-- Run this script in phpMyAdmin or MySQL command line

-- Step 1: Add scraping columns to existing websites table
ALTER TABLE `websites` 
ADD COLUMN `scraping_config` JSON,
ADD COLUMN `last_scraped` timestamp NULL,
ADD COLUMN `total_parks_found` int DEFAULT 0,
ADD COLUMN `total_parks_imported` int DEFAULT 0,
ADD COLUMN `status` enum('active','inactive','error') DEFAULT 'active',
ADD COLUMN `notes` text;

-- Step 2: Create scraping_logs table
CREATE TABLE `scraping_logs` (
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
    FOREIGN KEY (`website_id`) REFERENCES `websites`(`website_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Step 3: Add default scraping configurations to existing websites
UPDATE websites SET 
    scraping_config = JSON_OBJECT(
        'park_name_selector', '.park-title, h1, .entry-title',
        'park_address_selector', '.park-address, .address',
        'park_description_selector', '.park-description, .entry-content',
        'amenities_selector', '.amenities-list li, .facilities li',
        'hours_selector', '.hours, .park-hours',
        'phone_selector', '.phone, .contact-phone'
    ),
    status = 'active',
    notes = CONCAT('Scraping configuration for: ', description)
WHERE scraping_config IS NULL;

-- Step 4: Add specific configuration for Missouri state parks (website_id = 3)
UPDATE websites SET 
    scraping_config = JSON_OBJECT(
        'park_name_selector', '.park-name, h1.park-title',
        'park_address_selector', '.park-address, .location-info',
        'park_description_selector', '.park-description, .park-overview',
        'amenities_selector', '.amenities li, .facilities-list li',
        'hours_selector', '.park-hours, .operating-hours',
        'phone_selector', '.contact-phone, .park-contact'
    ),
    notes = 'Missouri state parks website - priority scraping target'
WHERE website_id = 3;

-- Verification queries (optional - run these to check the setup)
-- SELECT 'Websites with scraping config' as info, COUNT(*) as count FROM websites WHERE scraping_config IS NOT NULL;
-- SELECT 'Scraping logs table exists' as info, COUNT(*) as count FROM scraping_logs;
-- SELECT website_id, description, status FROM websites ORDER BY website_id;
