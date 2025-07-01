<?php
/**
 * Database Setup Script for AmyoParks
 * This script creates the necessary database tables and inserts sample data
 */

require_once '../includes/db.php';

function setupDatabase($pdo) {
    $queries = [
        // Create states table
        "CREATE TABLE IF NOT EXISTS states (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            abbreviation CHAR(2) NOT NULL UNIQUE,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Create cities table
        "CREATE TABLE IF NOT EXISTS cities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            state_id INT NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE,
            UNIQUE KEY unique_city_state (name, state_id)
        )",

        // Create zip_codes table
        "CREATE TABLE IF NOT EXISTS zip_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(10) NOT NULL UNIQUE,
            city_id INT,
            state_id INT NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL,
            FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE
        )",

        // Create categories table
        "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Create amenities table
        "CREATE TABLE IF NOT EXISTS amenities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            icon VARCHAR(100),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Create attributes table
        "CREATE TABLE IF NOT EXISTS attributes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            type ENUM('text', 'number', 'select', 'radio', 'checkbox', 'boolean') NOT NULL,
            default_value TEXT,
            options TEXT,
            is_required BOOLEAN DEFAULT FALSE,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",

        // Create parks table
        "CREATE TABLE IF NOT EXISTS parks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(200) NOT NULL,
            description TEXT,
            address VARCHAR(255),
            city_id INT,
            state_id INT,
            zip_code VARCHAR(10),
            phone VARCHAR(20),
            website VARCHAR(255),
            email VARCHAR(100),
            latitude DECIMAL(10, 8),
            longitude DECIMAL(11, 8),
            category_id INT,
            is_active BOOLEAN DEFAULT TRUE,
            is_featured BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL,
            FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        )",

        // Create park_amenities junction table
        "CREATE TABLE IF NOT EXISTS park_amenities (
            park_id INT,
            amenity_id INT,
            PRIMARY KEY (park_id, amenity_id),
            FOREIGN KEY (park_id) REFERENCES parks(id) ON DELETE CASCADE,
            FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
        )",

        // Create park_attributes table
        "CREATE TABLE IF NOT EXISTS park_attributes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            park_id INT NOT NULL,
            attribute_id INT NOT NULL,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (park_id) REFERENCES parks(id) ON DELETE CASCADE,
            FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE,
            UNIQUE KEY unique_park_attribute (park_id, attribute_id)
        )",

        // Create contacts table
        "CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(200),
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",

        // Create admin_users table
        "CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];

    echo "Creating database tables...\n";
    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
            echo "✓ Table created successfully\n";
        } catch (PDOException $e) {
            echo "✗ Error creating table: " . $e->getMessage() . "\n";
        }
    }
}

function insertSampleData($pdo) {
    echo "\nInserting sample data...\n";

    // Insert sample states
    $states = [
        ['California', 'CA'],
        ['New York', 'NY'],
        ['Texas', 'TX'],
        ['Florida', 'FL'],
        ['Illinois', 'IL']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO states (name, abbreviation) VALUES (?, ?)");
    foreach ($states as $state) {
        $stmt->execute($state);
    }
    echo "✓ Sample states inserted\n";

    // Insert sample cities
    $cities = [
        ['Los Angeles', 1],
        ['San Francisco', 1],
        ['New York City', 2],
        ['Buffalo', 2],
        ['Houston', 3],
        ['Dallas', 3],
        ['Miami', 4],
        ['Tampa', 4],
        ['Chicago', 5]
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO cities (name, state_id) VALUES (?, ?)");
    foreach ($cities as $city) {
        $stmt->execute($city);
    }
    echo "✓ Sample cities inserted\n";

    // Insert sample categories
    $categories = [
        ['City Park', 'Urban parks within city limits'],
        ['State Park', 'State-managed recreational areas'],
        ['National Park', 'Federally protected natural areas'],
        ['Recreation Center', 'Community recreational facilities'],
        ['Beach Park', 'Coastal recreational areas'],
        ['Nature Preserve', 'Protected natural habitats']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, description) VALUES (?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    echo "✓ Sample categories inserted\n";

    // Insert sample amenities
    $amenities = [
        ['Playground', 'Children\'s play equipment', 'fas fa-child'],
        ['Restrooms', 'Public restroom facilities', 'fas fa-restroom'],
        ['Parking', 'Vehicle parking areas', 'fas fa-parking'],
        ['Picnic Area', 'Tables and grills for picnicking', 'fas fa-utensils'],
        ['Walking Trails', 'Paved or unpaved walking paths', 'fas fa-walking'],
        ['Swimming Pool', 'Public swimming facilities', 'fas fa-swimming-pool'],
        ['Basketball Court', 'Basketball playing area', 'fas fa-basketball-ball'],
        ['Tennis Court', 'Tennis playing area', 'fas fa-tennis-ball'],
        ['Dog Park', 'Off-leash area for dogs', 'fas fa-dog'],
        ['Fishing', 'Fishing allowed', 'fas fa-fish']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO amenities (name, description, icon) VALUES (?, ?, ?)");
    foreach ($amenities as $amenity) {
        $stmt->execute($amenity);
    }
    echo "✓ Sample amenities inserted\n";

    // Insert default admin user
    $username = 'admin';
    $password = 'Admin123!';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT IGNORE INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $passwordHash, 'admin@amyoparks.com']);
    echo "✓ Default admin user created (username: admin, password: Admin123!)\n";
}

// Run setup
try {
    echo "AmyoParks Database Setup\n";
    echo "========================\n\n";
    
    setupDatabase($pdo);
    insertSampleData($pdo);
    
    echo "\n✓ Database setup completed successfully!\n";
    echo "\nYou can now:\n";
    echo "1. Access the admin panel at: /amyoparks/admin/\n";
    echo "2. Login with username: admin, password: Admin123!\n";
    echo "3. Start adding parks and managing content\n\n";
    
} catch (Exception $e) {
    echo "✗ Setup failed: " . $e->getMessage() . "\n";
}
?>
