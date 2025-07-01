<?php
require_once 'includes/header.php';

$park_id = intval($_GET['park_id'] ?? 0);

if (!$park_id) {
    header('Location: /amyoparks/parks.php');
    exit;
}

try {
    // Get park details
    $park_stmt = $pdo->prepare("
        SELECT p.*, c.name AS city, s.code AS state_code, s.name AS state_name, z.code AS zip_code
        FROM parks p
        LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
        LEFT JOIN cities c ON z.city_id = c.city_id
        LEFT JOIN states s ON c.state_id = s.state_id
        WHERE p.park_id = ?
    ");
    $park_stmt->execute([$park_id]);
    $park = $park_stmt->fetch();
    
    if (!$park) {
        header('Location: /amyoparks/parks.php');
        exit;
    }
    
    // Set page title and description
    $page_title = sanitize_input($park['name']) . ' - AmyoParks';
    $page_description = 'Explore ' . sanitize_input($park['name']) . ' in ' . sanitize_input($park['city']) . ', ' . sanitize_input($park['state_name']) . '. View amenities, hours, and location details.';
    
    // Get amenities for this park
    $amenities_stmt = $pdo->prepare("
        SELECT a.amenity_id, a.instance_name, ac.name AS category_name, ac.category_id,
               at.name AS attribute_name, aa.attribute_value
        FROM amenities a
        JOIN amenity_categories ac ON a.category_id = ac.category_id
        LEFT JOIN amenity_attributes aa ON a.amenity_id = aa.amenity_id
        LEFT JOIN attribute_types at ON aa.attribute_type_id = at.attribute_type_id
        WHERE a.park_id = ?
        ORDER BY ac.name, a.instance_name, at.name
    ");
    $amenities_stmt->execute([$park_id]);
    $amenity_results = $amenities_stmt->fetchAll();
    
    // Group amenities by category and instance
    $amenities = [];
    foreach ($amenity_results as $row) {
        $category_id = $row['category_id'];
        $amenity_id = $row['amenity_id'];
        
        if (!isset($amenities[$category_id])) {
            $amenities[$category_id] = [
                'name' => $row['category_name'],
                'items' => []
            ];
        }
        
        if (!isset($amenities[$category_id]['items'][$amenity_id])) {
            $amenities[$category_id]['items'][$amenity_id] = [
                'instance_name' => $row['instance_name'],
                'attributes' => []
            ];
        }
        
        if ($row['attribute_name'] && $row['attribute_value']) {
            $amenities[$category_id]['items'][$amenity_id]['attributes'][] = [
                'name' => $row['attribute_name'],
                'value' => $row['attribute_value']
            ];
        }
    }
    
} catch (PDOException $e) {
    error_log("Error fetching park details: " . $e->getMessage());
    header('Location: /amyoparks/parks.php');
    exit;
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="/amyoparks/" class="hover:text-primary">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="/amyoparks/parks.php" class="hover:text-primary">Parks</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900"><?php echo sanitize_input($park['name']); ?></li>
        </ol>
    </nav>
    
    <!-- Park Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <?php echo sanitize_input($park['name']); ?>
                </h1>
                <div class="flex items-center text-gray-600 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-lg">
                        <?php echo sanitize_input($park['address'] ?? ''); ?>
                        <?php if ($park['address']): ?>, <?php endif; ?>
                        <?php echo sanitize_input($park['city']); ?>, 
                        <?php echo sanitize_input($park['state_name']); ?> 
                        <?php echo sanitize_input($park['zip_code']); ?>
                    </span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="window.print()" class="btn-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Park Image Placeholder -->
            <div class="mb-8">
                <div class="h-64 md:h-80 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center">
                    <div class="text-center text-white">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-lg opacity-75">Park Photo</p>
                    </div>
                </div>
            </div>
            
            <!-- Amenities -->
            <?php if (!empty($amenities)): ?>
                <div class="card mb-8">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold">Amenities</h2>
                    </div>
                    <div class="card-body">
                        <div class="space-y-6">
                            <?php foreach ($amenities as $category): ?>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                        <span class="w-2 h-2 bg-primary rounded-full mr-3"></span>
                                        <?php echo sanitize_input($category['name']); ?>
                                    </h3>
                                    
                                    <div class="ml-5 space-y-3">
                                        <?php foreach ($category['items'] as $item): ?>
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <h4 class="font-medium text-gray-900 mb-2">
                                                    <?php echo $item['instance_name'] ? sanitize_input($item['instance_name']) : 'General'; ?>
                                                </h4>
                                                
                                                <?php if (!empty($item['attributes'])): ?>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                                        <?php foreach ($item['attributes'] as $attribute): ?>
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600"><?php echo sanitize_input($attribute['name']); ?>:</span>
                                                                <span class="font-medium"><?php echo sanitize_input($attribute['value']); ?></span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card mb-8">
                    <div class="card-body text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Amenities Listed</h3>
                        <p class="text-gray-600">Amenity information is not currently available for this park.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Park Information -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Park Information</h3>
                </div>
                <div class="card-body space-y-4">
                    <?php if ($park['hours_open'] && $park['hours_close']): ?>
                        <div>
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium text-gray-900">Hours</span>
                            </div>
                            <p class="text-gray-600 ml-6">
                                <?php echo format_hours($park['hours_open'], $park['hours_close']); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($park['latitude'] && $park['longitude']): ?>
                        <div>
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium text-gray-900">Coordinates</span>
                            </div>
                            <p class="text-gray-600 ml-6 text-sm">
                                <?php echo number_format(floatval($park['latitude']), 6); ?>, 
                                <?php echo number_format(floatval($park['longitude']), 6); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium text-gray-900">Address</span>
                        </div>
                        <p class="text-gray-600 ml-6">
                            <?php echo sanitize_input($park['address'] ?? ''); ?><br>
                            <?php echo sanitize_input($park['city']); ?>, 
                            <?php echo sanitize_input($park['state_name']); ?> 
                            <?php echo sanitize_input($park['zip_code']); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Map Placeholder -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Location</h3>
                </div>
                <div class="h-64 bg-gray-100 rounded-b-lg flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <p>Interactive map will be available soon</p>
                        <?php if ($park['latitude'] && $park['longitude']): ?>
                            <p class="text-xs mt-1">
                                Lat: <?php echo number_format(floatval($park['latitude']), 6); ?><br>
                                Lng: <?php echo number_format(floatval($park['longitude']), 6); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card">
                <div class="card-body space-y-3">
                    <a href="/amyoparks/parks.php" class="btn-outline w-full text-center block">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Back to All Parks
                    </a>
                    
                    <a href="/amyoparks/search.php" class="btn-primary w-full text-center block">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Find Similar Parks
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
