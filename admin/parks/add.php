<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /amyoparks/admin/login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'])) {
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $address = sanitizeInput($_POST['address']);
    $city_id = (int)$_POST['city_id'];
    $state_id = (int)$_POST['state_id'];
    $zip_code = sanitizeInput($_POST['zip_code']);
    $phone = sanitizeInput($_POST['phone']);
    $website = sanitizeInput($_POST['website']);
    $email = sanitizeInput($_POST['email']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);
    $category_id = (int)$_POST['category_id'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Validation
    if (empty($name)) {
        $errors[] = "Park name is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }
    if ($city_id <= 0) {
        $errors[] = "Please select a city.";
    }
    if ($state_id <= 0) {
        $errors[] = "Please select a state.";
    }
    if ($category_id <= 0) {
        $errors[] = "Please select a category.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO parks (name, description, address, city_id, state_id, zip_code, phone, website, email, latitude, longitude, category_id, is_active, is_featured, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $description, $address, $city_id, $state_id, $zip_code, $phone, $website, $email, $latitude, $longitude, $category_id, $is_active, $is_featured]);
            
            $success = "Park added successfully!";
            
            // Clear form data
            $_POST = [];
        } catch (PDOException $e) {
            $errors[] = "Error adding park: " . $e->getMessage();
        }
    }
}

// Get categories for dropdown
$categories = getDropdownOptions($pdo, 'categories');

// Get states for dropdown
$states = getDropdownOptions($pdo, 'states');

// Get cities for dropdown (we'll populate this via AJAX based on state selection)
$cities = [];

$title = "Add New Park";
require_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="index.php" class="text-blue-600 hover:text-blue-800 mr-4">
            ← Back to Parks
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Add New Park</h1>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white shadow-md rounded-lg p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRF(); ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Park Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select id="category_id" name="category_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea id="description" name="description" required rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <!-- Location Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Location Information</h3>
            </div>

            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <input type="text" id="address" name="address" required 
                       value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="state_id" class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                <select id="state_id" name="state_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select State</option>
                    <?php foreach ($states as $state): ?>
                        <option value="<?php echo $state['id']; ?>" 
                                <?php echo (isset($_POST['state_id']) && $_POST['state_id'] == $state['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($state['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                <select id="city_id" name="city_id" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select City</option>
                </select>
            </div>

            <div>
                <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                <input type="text" id="zip_code" name="zip_code" 
                       value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input type="number" id="latitude" name="latitude" step="any" 
                       value="<?php echo htmlspecialchars($_POST['latitude'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input type="number" id="longitude" name="longitude" step="any" 
                       value="<?php echo htmlspecialchars($_POST['longitude'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Contact Information -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Contact Information</h3>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                <input type="url" id="website" name="website" 
                       value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Status</h3>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       <?php echo (isset($_POST['is_active']) || !isset($_POST['name'])) ? 'checked' : ''; ?>
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active</label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_featured" name="is_featured" value="1" 
                       <?php echo isset($_POST['is_featured']) ? 'checked' : ''; ?>
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Featured</label>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="index.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Add Park
            </button>
        </div>
    </form>
</div>

<script>
// Load cities when state changes
document.getElementById('state_id').addEventListener('change', function() {
    const stateId = this.value;
    const citySelect = document.getElementById('city_id');
    
    // Clear existing options
    citySelect.innerHTML = '<option value="">Select City</option>';
    
    if (stateId) {
        fetch('/amyoparks/admin/ajax/get_cities.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'state_id=' + stateId
        })
        .then(response => response.json())
        .then(data => {
            data.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading cities:', error));
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
