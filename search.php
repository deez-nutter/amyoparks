<?php
$page_title = 'Search Parks - AmyoParks';
$page_description = 'Search for parks by location, amenities, and features. Find the perfect outdoor destination.';

require_once 'includes/header.php';

$search_performed = false;
$search_results = [];
$search_city = '';
$search_state = '';
$search_category = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_performed = true;
    $search_city = sanitize_input($_POST['city'] ?? '');
    $search_state = sanitize_input($_POST['state'] ?? '');
    $search_category = sanitize_input($_POST['category'] ?? '');
    
    // Handle AJAX requests
    if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
        header('Content-Type: application/json');
        
        try {
            $search_term = sanitize_input($_POST['search'] ?? '');
            
            $sql = "
                SELECT DISTINCT p.park_id, p.name, c.name AS city, s.code AS state,
                       COUNT(DISTINCT a.amenity_id) AS amenity_count
                FROM parks p
                LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
                LEFT JOIN cities c ON z.city_id = c.city_id
                LEFT JOIN states s ON c.state_id = s.state_id
                LEFT JOIN amenities a ON p.park_id = a.park_id
                WHERE (p.name LIKE ? OR c.name LIKE ?)
                GROUP BY p.park_id
                ORDER BY p.name
                LIMIT 10
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["%$search_term%", "%$search_term%"]);
            $results = $stmt->fetchAll();
            
            echo json_encode(['parks' => $results]);
        } catch (PDOException $e) {
            error_log("AJAX search error: " . $e->getMessage());
            echo json_encode(['error' => 'Search failed']);
        }
        exit;
    }
    
    // Regular form submission
    try {
        $where_conditions = [];
        $params = [];
        
        if (!empty($search_city)) {
            $where_conditions[] = "c.name LIKE ?";
            $params[] = "%$search_city%";
        }
        
        if (!empty($search_state)) {
            $where_conditions[] = "s.code = ?";
            $params[] = $search_state;
        }
        
        if (!empty($search_category)) {
            $where_conditions[] = "a.category_id = ?";
            $params[] = $search_category;
        }
        
        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
        
        $sql = "
            SELECT DISTINCT p.park_id, p.name, c.name AS city, s.code AS state, s.name AS state_name,
                   COUNT(DISTINCT a.amenity_id) AS amenity_count
            FROM parks p
            LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
            LEFT JOIN cities c ON z.city_id = c.city_id
            LEFT JOIN states s ON c.state_id = s.state_id
            LEFT JOIN amenities a ON p.park_id = a.park_id
            $where_clause
            GROUP BY p.park_id
            ORDER BY p.name
            LIMIT 50
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $search_results = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Search error: " . $e->getMessage());
        $search_results = [];
    }
}

// Get data for form dropdowns
$amenity_categories = get_amenity_categories($pdo);
$states = get_states($pdo);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Search Parks</h1>
        <p class="text-gray-600 text-lg">Find the perfect park for your outdoor adventure</p>
    </div>
    
    <!-- Search Form -->
    <div class="max-w-4xl mx-auto mb-12">
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Search Criteria</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/amyoparks/search.php" data-validate>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <select name="state" id="state" class="form-select">
                                <option value="">All States</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?php echo sanitize_input($state['code']); ?>"
                                            <?php echo $search_state === $state['code'] ? 'selected' : ''; ?>>
                                        <?php echo sanitize_input($state['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="city" id="city" 
                                   value="<?php echo sanitize_input($search_city); ?>"
                                   placeholder="Enter city name" 
                                   class="form-input search-input">
                        </div>
                        
                        <!-- Amenity Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Amenity Type</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">All Amenities</option>
                                <?php foreach ($amenity_categories as $category): ?>
                                    <option value="<?php echo sanitize_input($category['category_id']); ?>"
                                            <?php echo $search_category === $category['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo sanitize_input($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn-primary px-8 py-3 text-lg">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search Parks
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Live Search Results (for AJAX) -->
    <div id="search-results" class="mb-8"></div>
    
    <!-- Search Results -->
    <?php if ($search_performed): ?>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Search Results</h2>
            
            <!-- Search Summary -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                    <span>Searched for:</span>
                    <?php if ($search_city): ?>
                        <span class="badge badge-secondary">City: <?php echo sanitize_input($search_city); ?></span>
                    <?php endif; ?>
                    <?php if ($search_state): ?>
                        <span class="badge badge-secondary">State: <?php echo sanitize_input($search_state); ?></span>
                    <?php endif; ?>
                    <?php if ($search_category): ?>
                        <?php
                        $category_name = '';
                        foreach ($amenity_categories as $cat) {
                            if ($cat['category_id'] == $search_category) {
                                $category_name = $cat['name'];
                                break;
                            }
                        }
                        ?>
                        <span class="badge badge-secondary">Amenity: <?php echo sanitize_input($category_name); ?></span>
                    <?php endif; ?>
                    <?php if (!$search_city && !$search_state && !$search_category): ?>
                        <span class="badge badge-secondary">All Parks</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($search_results)): ?>
                <div class="mb-4">
                    <p class="text-gray-600">Found <?php echo count($search_results); ?> parks</p>
                </div>
                
                <!-- Results Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <?php foreach ($search_results as $park): ?>
                        <div class="card hover:shadow-lg transition-shadow">
                            <div class="card-body">
                                <div class="flex justify-between items-start mb-3">                            <h3 class="text-xl font-semibold text-gray-900">
                                <a href="/amyoparks/park-details.php?park_id=<?php echo sanitize_input($park['park_id']); ?>" 
                                   class="hover:text-primary transition-colors">
                                    <?php echo sanitize_input($park['name']); ?>
                                </a>
                            </h3>
                            <span class="badge badge-primary">
                                <?php echo intval($park['amenity_count']); ?> amenities
                            </span>
                        </div>
                        
                        <div class="flex items-center text-gray-600 mb-4">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?php echo sanitize_input($park['city'] . ', ' . $park['state_name'] ?? $park['state']); ?>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <a href="/amyoparks/park-details.php?park_id=<?php echo sanitize_input($park['park_id']); ?>" 
                               class="btn-primary">
                                View Details
                            </a>
                        </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            <?php else: ?>
                <!-- No Results -->
                <div class="card">
                    <div class="card-body text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.463-.64-6.291-1.709"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Parks Found</h3>
                        <p class="text-gray-600 mb-4">
                            We couldn't find any parks matching your search criteria. Try:
                        </p>
                        <ul class="text-gray-600 text-left max-w-md mx-auto mb-6">
                            <li>• Expanding your search area</li>
                            <li>• Removing some filters</li>
                            <li>• Trying different amenity types</li>
                            <li>• Checking spelling of city names</li>
                        </ul>
                        <a href="/amyoparks/parks.php" class="btn-primary">Browse All Parks</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Quick Search Tips -->
    <div class="card mt-12">
        <div class="card-header">
            <h3 class="text-lg font-semibold">Search Tips</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Location Search</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Use partial city names (e.g., "Spring" finds Springfield)</li>
                        <li>• State selection helps narrow results</li>
                        <li>• Leave fields blank to search all locations</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Amenity Search</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Select specific amenity types you need</li>
                        <li>• Combine with location for targeted results</li>
                        <li>• Browse all amenities on individual park pages</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
