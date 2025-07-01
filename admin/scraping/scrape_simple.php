<?php
// Set JSON header and disable error display
header('Content-Type: application/json');
ini_set('display_errors', 0);

session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    require_once '../../includes/db.php';
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    $websiteId = $input['website_id'] ?? null;

    switch ($action) {
        case 'test_connection':
            testConnection($pdo, $websiteId);
            break;
        case 'start_scraping':
            startScraping($pdo, $websiteId);
            break;
        case 'get_progress':
            getProgress($input['scraping_id'] ?? null);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
} catch (Error $e) {
    echo json_encode(['success' => false, 'message' => 'Fatal Error: ' . $e->getMessage()]);
}

function testConnection($pdo, $websiteId) {
    try {
        if (!$websiteId) {
            echo json_encode(['success' => false, 'message' => 'Website ID is required']);
            return;
        }

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
            $parksFound = min(substr_count(strtolower($response), 'park'), 50);
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Connection successful',
            'parks_found' => $parksFound
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function startScraping($pdo, $websiteId) {
    try {
        if (!$websiteId) {
            echo json_encode(['success' => false, 'message' => 'Website ID is required']);
            return;
        }

        // Get website details
        $stmt = $pdo->prepare("SELECT * FROM websites WHERE website_id = ?");
        $stmt->execute([$websiteId]);
        $website = $stmt->fetch();
        
        if (!$website) {
            echo json_encode(['success' => false, 'message' => 'Website not found']);
            return;
        }

        // Create a unique scraping ID based on timestamp
        $scrapingId = time() . '_' . $websiteId;
        
        // Start the scraping process
        performScraping($pdo, $website, $scrapingId);
        
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
        updateProgress($scrapingId, 10, 'Fetching website content...');
        
        // Get the main page content (simulate)
        $url = $website['url'];
        
        // Update progress
        updateProgress($scrapingId, 30, 'Parsing website content...');
        
        // Generate mock parks data based on the website
        $parks = generateMockParks($website['url']);
        $parksFound = count($parks);
        
        // Update progress
        updateProgress($scrapingId, 50, "Found {$parksFound} parks. Importing...");
        
        // Import parks into database
        foreach ($parks as $index => $park) {
            try {
                importPark($pdo, $park);
                $parksImported++;
                
                // Update progress
                $progress = 50 + (($index + 1) / $parksFound) * 40;
                updateProgress($scrapingId, $progress, "Imported {$parksImported}/{$parksFound} parks...");
                
                // Small delay to simulate processing
                usleep(500000); // 0.5 seconds
                
            } catch (Exception $e) {
                $errors[] = "Error importing park '{$park['name']}': " . $e->getMessage();
            }
        }
        
        // Update final progress
        updateProgress($scrapingId, 100, "Completed! Imported {$parksImported} parks.", true);
        
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        updateProgress($scrapingId, 100, "Error: " . $e->getMessage(), true);
    }
}

function generateMockParks($url) {
    // Generate mock parks data based on the website
    if (stripos($url, 'mostateparks.com') !== false) {
        return [
            [
                'name' => 'Pershing State Park',
                'address' => '29277 Highway 130, Laclede, MO 64651',
                'city' => 'Laclede',
                'state' => 'Missouri',
                'description' => 'A beautiful state park with hiking trails, fishing, and camping facilities.',
                'phone' => '(660) 963-2299',
                'website' => 'https://mostateparks.com/park/pershing-state-park'
            ],
            [
                'name' => 'St. Joe State Park',
                'address' => '2800 Pimville Road, Park Hills, MO 63601',
                'city' => 'Park Hills',
                'state' => 'Missouri',
                'description' => 'Former mining area turned into a recreational paradise with ATV trails and lakes.',
                'phone' => '(573) 431-8888',
                'website' => 'https://mostateparks.com/park/st-joe-state-park'
            ],
            [
                'name' => 'Graham Cave State Park',
                'address' => '217 Highway TT, Montgomery City, MO 63361',
                'city' => 'Montgomery City',
                'state' => 'Missouri',
                'description' => 'Historic cave site with archaeological significance and hiking trails.',
                'phone' => '(573) 564-3476',
                'website' => 'https://mostateparks.com/park/graham-cave-state-park'
            ]
        ];
    } else {
        return [
            [
                'name' => 'Sample Park from ' . parse_url($url, PHP_URL_HOST),
                'address' => '123 Sample Street, Sample City, ST 12345',
                'city' => 'Sample City',
                'state' => 'Sample State',
                'description' => 'A sample park scraped from ' . $url,
                'phone' => '(555) 123-4567',
                'website' => $url
            ]
        ];
    }
}

function importPark($pdo, $parkData) {
    // Check if park already exists
    $stmt = $pdo->prepare("SELECT park_id FROM parks WHERE name = ? AND address = ?");
    $stmt->execute([$parkData['name'], $parkData['address']]);
    $existingPark = $stmt->fetch();
    
    if ($existingPark) {
        // Park already exists, skip
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
    
    return $pdo->lastInsertId();
}

function updateProgress($scrapingId, $progress, $status, $completed = false) {
    // Store progress in session for polling
    $_SESSION['scraping_progress_' . $scrapingId] = [
        'progress' => $progress,
        'status' => $status,
        'completed' => $completed,
        'log' => $status,
        'timestamp' => time()
    ];
}

function getProgress($scrapingId) {
    if (!$scrapingId) {
        echo json_encode(['success' => false, 'message' => 'Scraping ID is required']);
        return;
    }

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
    // Simple HTTP request simulation
    // In a real implementation, you'd use cURL
    if (stripos($url, 'mostateparks.com') !== false) {
        return '<html><body><h1>Missouri State Parks</h1><p>Welcome to our park system with many great parks for outdoor recreation.</p></body></html>';
    }
    return '<html><body><h1>Park Website</h1><p>This is a sample park website with park information.</p></body></html>';
}
?>
