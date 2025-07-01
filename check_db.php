<?php
// Simple database checker for MySQL
try {
    $pdo = new PDO('mysql:host=localhost;dbname=amyoparks', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Connection: SUCCESS</h2>\n";
    
    // Check what tables exist
    echo "<h3>Existing Tables:</h3>\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p>No tables found in database.</p>\n";
    } else {
        echo "<ul>\n";
        foreach ($tables as $table) {
            echo "<li>$table</li>\n";
        }
        echo "</ul>\n";
    }
    
    // Check specific tables needed by admin
    $needed_tables = ['parks', 'amenities', 'categories', 'attributes', 'contacts', 'admin_users', 'states', 'cities', 'zip_codes'];
    
    echo "<h3>Table Status Check:</h3>\n";
    foreach ($needed_tables as $table) {
        if (in_array($table, $tables)) {
            echo "<p>✅ $table - EXISTS</p>\n";
            
            // Check row count
            try {
                $count_stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
                $count = $count_stmt->fetchColumn();
                echo "<p>   - Rows: $count</p>\n";
            } catch (Exception $e) {
                echo "<p>   - Error counting rows: " . $e->getMessage() . "</p>\n";
            }
        } else {
            echo "<p>❌ $table - MISSING</p>\n";
        }
    }
    
    // Check parks table structure
    if (in_array('parks', $tables)) {
        echo "<h3>Parks Table Columns:</h3>\n";
        $stmt = $pdo->query("DESCRIBE parks");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<ul>\n";
        foreach ($columns as $col) {
            echo "<li>{$col['Field']} ({$col['Type']})</li>\n";
        }
        echo "</ul>\n";
    }
    
} catch (PDOException $e) {
    echo "<h2>Database Connection: FAILED</h2>\n";
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
}
?>
