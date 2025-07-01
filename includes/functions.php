<?php
// Helper functions for the application

/**
 * Check if admin is logged in
 */
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Sanitize input data
 */
function sanitize_input($input) {
    if (is_array($input)) {
        return array_map('sanitize_input', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize input (alternative naming)
 */
function sanitizeInput($input) {
    return sanitize_input($input);
}

/**
 * Generate CSRF token
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * CSRF functions with multiple naming conventions for compatibility
 */
function generateCSRF() {
    return generate_csrf_token();
}

/**
 * Validate CSRF token (alternative naming)  
 */
function validateCSRF($token) {
    return verify_csrf_token($token);
}

/**
 * Get amenity categories for dropdowns
 */
function get_amenity_categories($pdo) {
    try {
        $stmt = $pdo->query("SELECT category_id, name, description FROM amenity_categories ORDER BY name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching amenity categories: " . $e->getMessage());
        return [];
    }
}

/**
 * Get attribute types for admin forms
 */
function get_attribute_types($pdo) {
    try {
        $stmt = $pdo->query("SELECT attribute_type_id, name, description FROM attribute_types ORDER BY name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching attribute types: " . $e->getMessage());
        return [];
    }
}

/**
 * Get states for dropdowns
 */
function get_states($pdo) {
    try {
        $stmt = $pdo->query("SELECT state_id, name, code FROM states ORDER BY name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching states: " . $e->getMessage());
        return [];
    }
}

/**
 * Get cities by state for dropdowns
 */
function get_cities_by_state($pdo, $state_id = null) {
    try {
        if ($state_id) {
            $stmt = $pdo->prepare("SELECT city_id, name FROM cities WHERE state_id = ? ORDER BY name");
            $stmt->execute([$state_id]);
        } else {
            $stmt = $pdo->query("SELECT city_id, name, state_id FROM cities ORDER BY name");
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching cities: " . $e->getMessage());
        return [];
    }
}

/**
 * Get zip codes by city for dropdowns
 */
function get_zip_codes_by_city($pdo, $city_id = null) {
    try {
        if ($city_id) {
            $stmt = $pdo->prepare("SELECT zip_code_id, code FROM zip_codes WHERE city_id = ? ORDER BY code");
            $stmt->execute([$city_id]);
        } else {
            $stmt = $pdo->query("SELECT zip_code_id, code, city_id FROM zip_codes ORDER BY code");
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching zip codes: " . $e->getMessage());
        return [];
    }
}

/**
 * Get parks for dropdowns
 */
function get_parks($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT p.park_id, p.name, c.name AS city, s.code AS state 
            FROM parks p
            LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
            LEFT JOIN cities c ON z.city_id = c.city_id
            LEFT JOIN states s ON c.state_id = s.state_id
            ORDER BY p.name
        ");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching parks: " . $e->getMessage());
        return [];
    }
}

/**
 * Format hours for display
 */
function format_hours($open, $close) {
    if (!$open || !$close) return 'Hours not specified';
    
    $open_formatted = date('g:i A', strtotime($open));
    $close_formatted = date('g:i A', strtotime($close));
    
    return $open_formatted . ' - ' . $close_formatted;
}

/**
 * Redirect with message
 */
function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit;
}

/**
 * Display flash message
 */
function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'success';
        $bg_color = $type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700';
        
        echo "<div class='$bg_color px-4 py-3 rounded border mb-4' role='alert'>
                <span class='block sm:inline'>$message</span>
              </div>";
        
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

/**
 * Get dropdown options for forms
 */
function getDropdownOptions($pdo, $table) {
    try {
        $id_col = $table === 'states' ? 'state_id' : 
                 ($table === 'cities' ? 'city_id' : 
                 ($table === 'categories' ? 'category_id' : 'id'));
        
        $stmt = $pdo->query("SELECT $id_col as id, name FROM $table ORDER BY name");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching $table: " . $e->getMessage());
        return [];
    }
}

/**
 * Paginate results
 */
function paginate($total_records, $records_per_page, $current_page) {
    $total_pages = ceil($total_records / $records_per_page);
    $offset = ($current_page - 1) * $records_per_page;
    
    return [
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages
    ];
}
?>
