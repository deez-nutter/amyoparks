<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page_title = 'AmyoParks - Find Your Perfect Park';
$page_description = 'Discover parks with pools, fields, trails, and more. Find the perfect park for your outdoor adventure.';

require_once 'includes/header.php';

// Get featured parks (3 random parks)
try {
    $featured_stmt = $pdo->query("
        SELECT p.park_id, p.name, c.name AS city, s.code AS state
        FROM parks p
        LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
        LEFT JOIN cities c ON z.city_id = c.city_id
        LEFT JOIN states s ON c.state_id = s.state_id
        ORDER BY RAND() 
        LIMIT 3
    ");
    $featured_parks = $featured_stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching featured parks: " . $e->getMessage());
    $featured_parks = [];
}

// Get amenity categories for search dropdown
$amenity_categories = get_amenity_categories($pdo);
$states = get_states($pdo);
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-accent text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
            Find Your Perfect Park
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">
            Discover parks with the amenities you need - pools, fields, trails, and more
        </p>
        <a href="#search" class="btn-primary text-lg px-8 py-3 inline-block">
            Start Searching
        </a>
    </div>
</section>

<!-- Search Section -->
<section id="search" class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Search Parks</h2>
            <p class="text-gray-600">Find parks by location or amenity type</p>
        </div>
        
        <form method="POST" action="/amyoparks/search.php" class="card" data-validate>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- State Selection -->
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <select name="state" id="state" class="form-select state-select">
                            <option value="">All States</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?php echo sanitize_input($state['code']); ?>">
                                    <?php echo sanitize_input($state['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- City Selection -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city" id="city" placeholder="Enter city name" class="form-input">
                    </div>
                    
                    <!-- Amenity Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Amenity Type</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Amenities</option>
                            <?php foreach ($amenity_categories as $category): ?>
                                <option value="<?php echo sanitize_input($category['category_id']); ?>">
                                    <?php echo sanitize_input($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <button type="submit" class="btn-primary px-8 py-3">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search Parks
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Featured Parks Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Parks</h2>
            <p class="text-gray-600">Explore some of our amazing parks</p>
        </div>
        
        <?php if (!empty($featured_parks)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($featured_parks as $park): ?>
                    <div class="card hover:shadow-lg transition-shadow">
                        <div class="h-48 bg-gradient-to-br from-primary to-accent rounded-t-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="card-body">
                            <h3 class="text-xl font-semibold mb-2">
                                <a href="/amyoparks/park-details.php?park_id=<?php echo sanitize_input($park['park_id']); ?>" 
                                   class="text-gray-900 hover:text-primary transition-colors">
                                    <?php echo sanitize_input($park['name']); ?>
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-4">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <?php echo sanitize_input($park['city'] . ', ' . $park['state']); ?>
                            </p>
                            <a href="/amyoparks/park-details.php?park_id=<?php echo sanitize_input($park['park_id']); ?>" 
                               class="btn-outline w-full text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-gray-600">
                <p>No featured parks available at this time.</p>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-8">
            <a href="/amyoparks/parks.php" class="btn-primary px-8 py-3">
                View All Parks
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose AmyO Parks?</h2>
            <p class="text-gray-600">Everything you need to find the perfect outdoor destination</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Easy Search</h3>
                <p class="text-gray-600">Find parks by location, amenities, or specific features you're looking for.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Detailed Information</h3>
                <p class="text-gray-600">Get comprehensive details about amenities, hours, and park features.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Location Based</h3>
                <p class="text-gray-600">Find parks near you or in your destination city with accurate location data.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Explore?</h2>
        <p class="text-xl mb-8 opacity-90">Start your adventure by finding the perfect park for your needs.</p>
        <div class="space-x-4">
            <a href="/amyoparks/parks.php" class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-md transition-colors">
                Browse All Parks
            </a>
            <a href="/amyoparks/about.php" class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-md transition-colors">
                Learn More
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
