<?php
// Database checker script to see what tables exist and what's missing
require_once 'includes/db.php';

echo "<h2>Database Structure Check</h2>\n";
echo "<pre>\n";

try {
    // Get list of all tables in the database
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "=== EXISTING TABLES ===\n";
    foreach ($tables as $table) {
        echo "✓ $table\n";
        
        // Show structure of each table
        $desc_stmt = $pdo->query("DESCRIBE `$table`");
        $columns = $desc_stmt->fetchAll();
        foreach ($columns as $column) {
            echo "    - {$column['Field']} ({$column['Type']}) {$column['Key']}\n";
        }
        echo "\n";
    }
    
    // Check for required tables
    $required_tables = [
        'parks',
        'amenities', 
        'categories',
        'attributes',
        'contacts',
        'admin_users',
        'states',
        'cities',
        'zip_codes'
    ];
    
    echo "\n=== MISSING TABLES ===\n";
    $missing_tables = [];
    foreach ($required_tables as $table) {
        if (!in_array($table, $tables)) {
            echo "✗ $table (MISSING)\n";
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "✓ All required tables exist!\n";
    }
    
    // Check parks table for required columns
    if (in_array('parks', $tables)) {
        echo "\n=== PARKS TABLE COLUMN CHECK ===\n";
        $parks_stmt = $pdo->query("DESCRIBE parks");
        $parks_columns = $parks_stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $required_parks_columns = [
            'park_id', 'name', 'address', 'category_id', 'description', 
            'city', 'state', 'phone', 'website', 'email', 'is_active', 'is_featured'
        ];
        
        foreach ($required_parks_columns as $column) {
            if (in_array($column, $parks_columns)) {
                echo "✓ $column\n";
            } else {
                echo "✗ $column (MISSING)\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>\n";
?>
