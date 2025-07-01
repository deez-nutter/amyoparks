<?php
$page_title = 'About - AmyoParks';
$page_description = 'Learn about AmyoParks and discover the amenities available in parks across the country.';

require_once 'includes/header.php';

// Get amenity categories for display
$amenity_categories = get_amenity_categories($pdo);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">About AmyoParks</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Your comprehensive guide to finding parks with the amenities you need for the perfect outdoor experience.
        </p>
    </div>
    
    <!-- Mission Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
            <div class="prose prose-lg text-gray-600">
                <p class="mb-4">
                    AmyoParks was created to help outdoor enthusiasts, families, and recreation seekers 
                    find the perfect parks for their needs. Whether you're looking for a park with a 
                    swimming pool for the kids, fields for sports activities, or trails for hiking, 
                    we make it easy to discover what's available in your area.
                </p>
                <p class="mb-4">
                    We believe that access to quality outdoor spaces is essential for community health 
                    and well-being. Our platform provides detailed information about park amenities, 
                    helping you make informed decisions about where to spend your outdoor time.
                </p>
                <p>
                    From neighborhood parks to regional recreation areas, we're building a comprehensive 
                    database that puts the information you need right at your fingertips.
                </p>
            </div>
        </div>
        
        <div class="flex items-center justify-center">
            <div class="w-full h-80 bg-gradient-to-br from-primary to-accent rounded-lg flex items-center justify-center">
                <div class="text-center text-white">
                    <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-lg opacity-75">Connecting People with Nature</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="mb-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">What We Offer</h2>
            <p class="text-gray-600">Comprehensive park information to help you find your perfect outdoor destination</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Smart Search</h3>
                <p class="text-gray-600">
                    Find parks by location, specific amenities, or features. Our advanced search 
                    helps you discover exactly what you're looking for.
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m-6 8h6m-3-3v6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Detailed Information</h3>
                <p class="text-gray-600">
                    Get comprehensive details about each park including amenities, specifications, 
                    hours of operation, and location information.
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Location-Based</h3>
                <p class="text-gray-600">
                    Discover parks near you or in your destination city with accurate location 
                    data and easy-to-use mapping features.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Amenities Section -->
    <div class="mb-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Available Amenities</h2>
            <p class="text-gray-600">
                We track a wide variety of park amenities to help you find exactly what you need
            </p>
        </div>
        
        <?php if (!empty($amenity_categories)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($amenity_categories as $category): ?>
                    <div class="card hover:shadow-lg transition-shadow">
                        <div class="card-body">
                            <div class="flex items-center mb-3">
                                <div class="w-3 h-3 bg-primary rounded-full mr-3"></div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <?php echo sanitize_input($category['name']); ?>
                                </h3>
                            </div>
                            <?php if (!empty($category['description'])): ?>
                                <p class="text-gray-600 text-sm">
                                    <?php echo sanitize_input($category['description']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-gray-600">
                <p>Amenity categories are being updated. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- How It Works Section -->
    <div class="bg-gray-50 rounded-2xl p-8 mb-16">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-gray-600">Finding your perfect park is easy with our simple process</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                    1
                </div>
                <h3 class="text-lg font-semibold mb-2">Search</h3>
                <p class="text-gray-600">
                    Enter your location or desired amenities using our search tools
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                    2
                </div>
                <h3 class="text-lg font-semibold mb-2">Browse</h3>
                <p class="text-gray-600">
                    Review park listings with detailed amenity information and locations
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                    3
                </div>
                <h3 class="text-lg font-semibold mb-2">Explore</h3>
                <p class="text-gray-600">
                    Visit your chosen park and enjoy the outdoor activities you love
                </p>
            </div>
        </div>
    </div>
    
    <!-- Statistics Section -->
    <?php
    try {
        $stats_stmt = $pdo->query("
            SELECT 
                (SELECT COUNT(*) FROM parks) AS park_count,
                (SELECT COUNT(*) FROM amenities) AS amenity_count,
                (SELECT COUNT(*) FROM amenity_categories) AS category_count,
                (SELECT COUNT(DISTINCT s.state_id) FROM states s 
                 JOIN cities c ON s.state_id = c.state_id 
                 JOIN zip_codes z ON c.city_id = z.city_id 
                 JOIN parks p ON z.zip_code_id = p.zip_code_id) AS state_count
        ");
        $stats = $stats_stmt->fetch();
    } catch (PDOException $e) {
        $stats = ['park_count' => 0, 'amenity_count' => 0, 'category_count' => 0, 'state_count' => 0];
    }
    ?>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">
                <?php echo number_format(intval($stats['park_count'])); ?>
            </div>
            <div class="text-gray-600">Parks Listed</div>
        </div>
        
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">
                <?php echo number_format(intval($stats['amenity_count'])); ?>
            </div>
            <div class="text-gray-600">Amenities Tracked</div>
        </div>
        
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">
                <?php echo number_format(intval($stats['category_count'])); ?>
            </div>
            <div class="text-gray-600">Amenity Types</div>
        </div>
        
        <div class="text-center">
            <div class="text-3xl font-bold text-primary mb-2">
                <?php echo number_format(intval($stats['state_count'])); ?>
            </div>
            <div class="text-gray-600">States Covered</div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="text-center bg-primary text-white rounded-2xl p-8">
        <h2 class="text-3xl font-bold mb-4">Ready to Explore?</h2>
        <p class="text-xl mb-6 opacity-90">
            Start discovering amazing parks with the amenities you're looking for
        </p>
        <div class="space-x-4">
            <a href="/amyoparks/search.php" class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-md transition-colors inline-block">
                Search Parks
            </a>
            <a href="/amyoparks/parks.php" class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-md transition-colors inline-block">
                Browse All Parks
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
