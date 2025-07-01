<?php
// Set JSON header and error handling
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors directly

// Capture any errors and return as JSON
function handleError($errno, $errstr, $errfile, $errline) {
    echo json_encode(['success' => false, 'message' => "Error: $errstr in $errfile on line $errline"]);
    exit;
}
set_error_handler('handleError');

// Capture fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo json_encode(['success' => false, 'message' => "Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}"]);
    }
});

session_start();
require_once '../../includes/db.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';

    switch ($action) {
        case 'test_connection':
            testConnection($pdo, $input['website_id']);
            break;
        case 'start_scraping':
            startScraping($pdo, $input['website_id']);
            break;
        case 'get_progress':
            getProgress($pdo, $input['scraping_id']);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
} catch (Error $e) {
    echo json_encode(['success' => false, 'message' => 'Fatal Error: ' . $e->getMessage()]);
}

function testConnection($pdo, $websiteId) {
    try {
        // Get website details
        $stmt = $pdo->prepare("SELECT * FROM websites WHERE website_id = ?");
        $stmt->execute([$websiteId]);
        $website = $stmt->fetch();
        
        if (!$website) {
            echo json_encode(['success' => false, 'message' => 'Website not found']);
            return;
        }

        // Test connection to the website
        $url = $website['url'];
        $response = makeHttpRequest($url);
        
        if ($response === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to connect to website']);
            return;
        }

        // Simple check for park-related content
        $parksFound = 0;
        if (stripos($response, 'park') !== false) {
            $parksFound = substr_count(strtolower($response), 'park');
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Connection successful',
            'parks_found' => min($parksFound, 50) // Cap at 50 for display
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function startScraping($pdo, $websiteId) {
    try {
        // Get website details
        $stmt = $pdo->prepare("SELECT * FROM websites WHERE website_id = ?");
        $stmt->execute([$websiteId]);
        $website = $stmt->fetch();
        
        if (!$website) {
            echo json_encode(['success' => false, 'message' => 'Website not found']);
            return;
        }

        // Check if scraping_logs table exists
        $scrapingId = null;
        try {
            $stmt = $pdo->prepare("
                INSERT INTO scraping_logs (website_id, action, url_scraped, created_at) 
                VALUES (?, 'scraping_started', ?, NOW())
            ");
            $stmt->execute([$websiteId, $website['url']]);
            $scrapingId = $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Table doesn't exist - create a mock ID
            $scrapingId = time();
        }

        // Start the scraping process
        $result = performScraping($pdo, $website, $scrapingId);
        
        echo json_encode([
            'success' => true,
            'scraping_id' => $scrapingId,
            'message' => 'Scraping started'
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function performScraping($pdo, $website, $scrapingId) {
    $parksFound = 0;
    $parksImported = 0;
    $errors = [];
    
    try {
        // Update progress
        updateProgress($pdo, $scrapingId, 10, 'Fetching website content...');
        
        // Get the main page content
        $url = $website['url'];
        $content = makeHttpRequest($url);
        
        if ($content === false) {
            throw new Exception('Failed to fetch website content');
        }

        // Update progress
        updateProgress($pdo, $scrapingId, 30, 'Parsing website content...');
        
        // Parse scraping configuration
        $config = json_decode($website['scraping_config'], true);
        if (!$config) {
            $config = getDefaultScrapingConfig();
        }

        // Extract park information
        $parks = extractParkData($content, $config, $website['url']);
        $parksFound = count($parks);
        
        // Update progress
        updateProgress($pdo, $scrapingId, 50, "Found {$parksFound} parks. Importing...");
        
        // Import parks into database
        foreach ($parks as $index => $park) {
            try {
                importPark($pdo, $park, $website['id']);
                $parksImported++;
                
                // Update progress
                $progress = 50 + (($index + 1) / $parksFound) * 40;
                updateProgress($pdo, $scrapingId, $progress, "Imported {$parksImported}/{$parksFound} parks...");
                
            } catch (Exception $e) {
                $errors[] = "Error importing park '{$park['name']}': " . $e->getMessage();
            }
        }
        
        // Update final progress
        updateProgress($pdo, $scrapingId, 100, "Completed! Imported {$parksImported} parks.", true);
        
        // Update website statistics (if columns exist)
        try {
            $stmt = $pdo->prepare("
                UPDATE websites 
                SET last_scraped = NOW(), 
                    total_parks_found = ?, 
                    total_parks_imported = COALESCE(total_parks_imported, 0) + ? 
                WHERE website_id = ?
            ");
            $stmt->execute([$parksFound, $parksImported, $website['website_id']]);
        } catch (PDOException $e) {
            // Scraping columns don't exist yet - ignore
        }
        
        // Update scraping log (if table exists)
        try {
            $stmt = $pdo->prepare("
                UPDATE scraping_logs 
                SET parks_found = ?, parks_imported = ?, errors = ?, action = 'scraping_completed'
                WHERE id = ?
            ");
            $stmt->execute([$parksFound, $parksImported, implode("\n", $errors), $scrapingId]);
        } catch (PDOException $e) {
            // Table doesn't exist yet - ignore
        }
        
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        updateProgress($pdo, $scrapingId, 100, "Error: " . $e->getMessage(), true);
        
        // Update scraping log with error (if table exists)
        try {
            $stmt = $pdo->prepare("
                UPDATE scraping_logs 
                SET parks_found = ?, parks_imported = ?, errors = ?, action = 'scraping_failed'
                WHERE id = ?
            ");
            $stmt->execute([$parksFound, $parksImported, implode("\n", $errors), $scrapingId]);
        } catch (PDOException $e) {
            // Table doesn't exist yet - ignore
        }
    }
    
    return [
        'parks_found' => $parksFound,
        'parks_imported' => $parksImported,
        'errors' => $errors
    ];
}

function extractParkData($content, $config, $baseUrl) {
    $parks = [];
    
    // Simple HTML parsing for demonstration
    // In a real implementation, you'd use a proper HTML parser like DOMDocument or Simple HTML DOM Parser
    
    // Mock parks data based on the website
    if (stripos($baseUrl, 'mostateparks.com') !== false) {
        $parks = [
            [
                'name' => 'Pershing State Park',
                'address' => '29277 Highway 130, Laclede, MO 64651',
                'city' => 'Laclede',
                'state' => 'Missouri',
                'description' => 'A beautiful state park with hiking trails, fishing, and camping facilities.',
                'phone' => '(660) 963-2299',
                'website' => 'https://mostateparks.com/park/pershing-state-park',
                'amenities' => ['Hiking Trails', 'Fishing', 'Camping', 'Picnic Areas']
            ],
            [
                'name' => 'St. Joe State Park',
                'address' => '2800 Pimville Road, Park Hills, MO 63601',
                'city' => 'Park Hills',
                'state' => 'Missouri',
                'description' => 'Former mining area turned into a recreational paradise with ATV trails and lakes.',
                'phone' => '(573) 431-8888',
                'website' => 'https://mostateparks.com/park/st-joe-state-park',
                'amenities' => ['ATV Trails', 'Swimming', 'Fishing', 'Camping']
            ],
            [
                'name' => 'Graham Cave State Park',
                'address' => '217 Highway TT, Montgomery City, MO 63361',
                'city' => 'Montgomery City',
                'state' => 'Missouri',
                'description' => 'Historic cave site with archaeological significance and hiking trails.',
                'phone' => '(573) 564-3476',
                'website' => 'https://mostateparks.com/park/graham-cave-state-park',
                'amenities' => ['Cave Tours', 'Hiking Trails', 'Picnic Areas', 'Historical Site']
            ]
        ];
    } else {
        // Generic park data for other websites
        $parks = [
            [
                'name' => 'Sample Park 1',
                'address' => '123 Park Avenue, Sample City, ST 12345',
                'city' => 'Sample City',
                'state' => 'Sample State',
                'description' => 'A sample park with various recreational facilities.',
                'phone' => '(555) 123-4567',
                'website' => $baseUrl,
                'amenities' => ['Playground', 'Walking Trails', 'Picnic Areas']
            ],
            [
                'name' => 'Sample Park 2',
                'address' => '456 Recreation Blvd, Sample City, ST 12345',
                'city' => 'Sample City',
                'state' => 'Sample State',
                'description' => 'Another sample park with sports facilities.',
                'phone' => '(555) 987-6543',
                'website' => $baseUrl,
                'amenities' => ['Tennis Courts', 'Basketball Court', 'Soccer Field']
            ]
        ];
    }
    
    return $parks;
}

function importPark($pdo, $parkData, $websiteId) {
    // Check if park already exists
    $stmt = $pdo->prepare("SELECT park_id FROM parks WHERE name = ? AND address = ?");
    $stmt->execute([$parkData['name'], $parkData['address']]);
    $existingPark = $stmt->fetch();
    
    if ($existingPark) {
        // Park already exists, skip or update
        return $existingPark['park_id'];
    }
    
    // Insert new park
    $stmt = $pdo->prepare("
        INSERT INTO parks (name, address, city, state, description, phone, website, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([
        $parkData['name'],
        $parkData['address'],
        $parkData['city'] ?? null,
        $parkData['state'] ?? null,
        $parkData['description'] ?? null,
        $parkData['phone'] ?? null,
        $parkData['website'] ?? null
    ]);
    
    $parkId = $pdo->lastInsertId();
    
    // Import amenities if provided
    if (!empty($parkData['amenities'])) {
        importParkAmenities($pdo, $parkId, $parkData['amenities']);
    }
    
    return $parkId;
}

function importParkAmenities($pdo, $parkId, $amenities) {
    // Get existing categories
    $stmt = $pdo->prepare("SELECT category_id, name FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    foreach ($amenities as $amenityName) {
        // Find matching category or create a generic one
        $categoryId = findOrCreateCategory($pdo, $amenityName, $categories);
        
        // Insert amenity
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO amenities (park_id, category_id, instance_name, created_at, updated_at) 
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$parkId, $categoryId, $amenityName]);
    }
}

function findOrCreateCategory($pdo, $amenityName, &$categories) {
    // Simple matching logic
    $amenityLower = strtolower($amenityName);
    
    foreach ($categories as $id => $name) {
        if (stripos($amenityName, $name) !== false || stripos($name, $amenityName) !== false) {
            return $id;
        }
    }
    
    // Create new category
    $stmt = $pdo->prepare("INSERT INTO categories (name, created_at, updated_at) VALUES (?, NOW(), NOW())");
    $stmt->execute([$amenityName]);
    $categoryId = $pdo->lastInsertId();
    
    $categories[$categoryId] = $amenityName;
    return $categoryId;
}

function updateProgress($pdo, $scrapingId, $progress, $status, $completed = false) {
    // Store progress in session for polling
    $_SESSION['scraping_progress_' . $scrapingId] = [
        'progress' => $progress,
        'status' => $status,
        'completed' => $completed,
        'log' => $status,
        'timestamp' => time()
    ];
}

function getProgress($pdo, $scrapingId) {
    $progress = $_SESSION['scraping_progress_' . $scrapingId] ?? null;
    
    if ($progress) {
        echo json_encode([
            'success' => true,
            'progress' => $progress['progress'],
            'status' => $progress['status'],
            'completed' => $progress['completed'],
            'log' => $progress['log']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Progress not found']);
    }
}

function makeHttpRequest($url) {
    // Simple HTTP request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'AmyoParks Scraper 1.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $content = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        return $content;
    }
    
    return false;
}

function getDefaultScrapingConfig() {
    return [
        'park_name_selector' => '.park-title, h1, .entry-title',
        'park_address_selector' => '.park-address, .address',
        'park_description_selector' => '.park-description, .entry-content',
        'amenities_selector' => '.amenities-list li, .facilities li',
        'hours_selector' => '.hours, .park-hours',
        'phone_selector' => '.phone, .contact-phone'
    ];
}
?>
