<?php
$page_title = 'All Parks - AmyoParks';
$page_description = 'Browse all parks with amenities like pools, fields, and trails. Filter by location and amenity type.';

require_once 'includes/header.php';

// Pagination settings
$records_per_page = 10;
$current_page = max(1, intval($_GET['page'] ?? 1));

// Get filter parameters
$filter_state = sanitize_input($_GET['state'] ?? '');
$filter_city = sanitize_input($_GET['city'] ?? '');
$filter_zip = sanitize_input($_GET['zip'] ?? '');
$filter_categories = isset($_GET['categories']) ? (array)$_GET['categories'] : [];
$filter_categories = array_map('sanitize_input', $filter_categories);

// Build WHERE clause
$where_conditions = [];
$params = [];

if (!empty($filter_state)) {
    $where_conditions[] = "s.code = :state";
    $params[':state'] = $filter_state;
}

if (!empty($filter_city)) {
    $where_conditions[] = "c.name LIKE :city";
    $params[':city'] = '%' . $filter_city . '%';
}

if (!empty($filter_zip)) {
    $where_conditions[] = "z.code = :zip";
    $params[':zip'] = $filter_zip;
}

if (!empty($filter_categories)) {
    $placeholders = implode(',', array_fill(0, count($filter_categories), '?'));
    $where_conditions[] = "a.category_id IN ($placeholders)";
    $params = array_merge($params, $filter_categories);
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

try {
    // Count total records
    $count_sql = "
        SELECT COUNT(DISTINCT p.park_id) as total
        FROM parks p
        LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
        LEFT JOIN cities c ON z.city_id = c.city_id
        LEFT JOIN states s ON c.state_id = s.state_id
        LEFT JOIN amenities a ON p.park_id = a.park_id
        $where_clause
    ";
    
    if (!empty($filter_categories)) {
        $count_stmt = $pdo->prepare($count_sql);
        $count_stmt->execute(array_values($params));
    } else {
        $count_stmt = $pdo->prepare($count_sql);
        $count_stmt->execute($params);
    }
    
    $total_records = $count_stmt->fetch()['total'];
    
    // Calculate pagination
    $pagination = paginate($total_records, $records_per_page, $current_page);
    
    // Get parks with pagination
    $sql = "
        SELECT p.park_id, p.name, c.name AS city, s.code AS state, s.name AS state_name,
               COUNT(DISTINCT a.amenity_id) AS amenity_count
        FROM parks p
        LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
        LEFT JOIN cities c ON z.city_id = c.city_id
        LEFT JOIN states s ON c.state_id = s.state_id
        LEFT JOIN amenities a ON p.park_id = a.park_id
        $where_clause
        GROUP BY p.park_id
        ORDER BY p.name
        LIMIT $records_per_page OFFSET {$pagination['offset']}
    ";
    
    if (!empty($filter_categories)) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($params));
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
    
    $parks = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Error fetching parks: " . $e->getMessage());
    $parks = [];
    $total_records = 0;
    $pagination = paginate(0, $records_per_page, $current_page);
}

// Get data for filters
$amenity_categories = get_amenity_categories($pdo);
$states = get_states($pdo);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">All Parks</h1>
        <p class="text-gray-600">Discover parks with the amenities you're looking for</p>
    </div>
    
    <!-- Filters -->
    <div class="card mb-8">
        <div class="card-header">
            <h2 class="text-lg font-semibold">Filter Parks</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="/amyoparks/parks.php" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- State Filter -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                        <select name="state" id="state" class="form-select">
                            <option value="">All States</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?php echo sanitize_input($state['code']); ?>" 
                                        <?php echo $filter_state === $state['code'] ? 'selected' : ''; ?>>
                                    <?php echo sanitize_input($state['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- City Filter -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" id="city" value="<?php echo sanitize_input($filter_city); ?>" 
                               placeholder="City name" class="form-input">
                    </div>
                    
                    <!-- Zip Code Filter -->
                    <div>
                        <label for="zip" class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                        <input type="text" name="zip" id="zip" value="<?php echo sanitize_input($filter_zip); ?>" 
                               placeholder="Zip code" class="form-input">
                    </div>
                    
                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
                
                <!-- Amenity Categories -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amenity Types</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php foreach ($amenity_categories as $category): ?>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" 
                                       value="<?php echo sanitize_input($category['category_id']); ?>"
                                       <?php echo in_array($category['category_id'], $filter_categories) ? 'checked' : ''; ?>
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700"><?php echo sanitize_input($category['name']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Reset Filters -->
                <?php if (!empty($filter_state) || !empty($filter_city) || !empty($filter_zip) || !empty($filter_categories)): ?>
                    <div class="flex justify-end">
                        <a href="/amyoparks/parks.php" class="btn-outline">Clear Filters</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Results -->
    <div class="mb-4 flex justify-between items-center">
        <p class="text-gray-600">
            Showing <?php echo number_format($total_records); ?> parks
            <?php if ($pagination['total_pages'] > 1): ?>
                (Page <?php echo $current_page; ?> of <?php echo $pagination['total_pages']; ?>)
            <?php endif; ?>
        </p>
    </div>
    
    <?php if (!empty($parks)): ?>
        <!-- Parks List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <?php foreach ($parks as $park): ?>
                <div class="card hover:shadow-lg transition-shadow">                        <div class="card-body">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-semibold text-gray-900">
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
                                <?php echo sanitize_input($park['city'] . ', ' . $park['state_name']); ?>
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
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <div class="pagination-info">
                    Showing <?php echo (($current_page - 1) * $records_per_page) + 1; ?> to 
                    <?php echo min($current_page * $records_per_page, $total_records); ?> of 
                    <?php echo number_format($total_records); ?> results
                </div>
                
                <div class="pagination-nav">
                    <?php if ($pagination['has_prev']): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>" 
                           class="pagination-btn">
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($pagination['total_pages'], $current_page + 2);
                    
                    for ($i = $start_page; $i <= $end_page; $i++):
                    ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                           class="pagination-btn <?php echo $i === $current_page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>" 
                           class="pagination-btn">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- No Results -->
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.463-.64-6.291-1.709M15 3.45a13.85 13.85 0 0112 9.55v0a13.85 13.85 0 01-12 9.55A13.85 13.85 0 013 12.55a13.85 13.85 0 0112-9.1z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Parks Found</h3>
                <p class="text-gray-600 mb-4">
                    We couldn't find any parks matching your criteria. Try adjusting your filters or 
                    <a href="/amyoparks/parks.php" class="text-primary hover:text-accent">browse all parks</a>.
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
