<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /amyoparks/admin/login.php');
    exit;
}

$parkId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($parkId <= 0) {
    header('Location: index.php');
    exit;
}

// Get park details
$stmt = $pdo->prepare("SELECT * FROM parks WHERE park_id = ?");
$stmt->execute([$parkId]);
$park = $stmt->fetch();

if (!$park) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'])) {
    $name = sanitizeInput($_POST['name']);
    $address = sanitizeInput($_POST['address']);
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);
    $hours_open = sanitizeInput($_POST['hours_open']);
    $hours_close = sanitizeInput($_POST['hours_close']);
    
    // Validation
    if (empty($name)) {
        $errors[] = "Park name is required.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE parks SET name = ?, address = ?, latitude = ?, longitude = ?, hours_open = ?, hours_close = ?, updated_at = NOW() WHERE park_id = ?");
            $stmt->execute([$name, $address, $latitude, $longitude, $hours_open, $hours_close, $parkId]);
            
            $success = "Park updated successfully!";
            
            // Refresh park data
            $stmt = $pdo->prepare("SELECT * FROM parks WHERE park_id = ?");
            $stmt->execute([$parkId]);
            $park = $stmt->fetch();
        } catch (PDOException $e) {
            $errors[] = "Error updating park: " . $e->getMessage();
        }
    }
}

$title = "Edit Park";
require_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="index.php" class="text-blue-600 hover:text-blue-800 mr-4">
            ← Back to Parks
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Park</h1>
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
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Park Information</h3>
            </div>

            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Park Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($park['name']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <input type="text" id="address" name="address" 
                       value="<?php echo htmlspecialchars($park['address']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input type="number" id="latitude" name="latitude" step="any" 
                       value="<?php echo htmlspecialchars($park['latitude']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input type="number" id="longitude" name="longitude" step="any" 
                       value="<?php echo htmlspecialchars($park['longitude']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="hours_open" class="block text-sm font-medium text-gray-700 mb-2">Opening Time</label>
                <input type="time" id="hours_open" name="hours_open" 
                       value="<?php echo htmlspecialchars($park['hours_open']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="hours_close" class="block text-sm font-medium text-gray-700 mb-2">Closing Time</label>
                <input type="time" id="hours_close" name="hours_close" 
                       value="<?php echo htmlspecialchars($park['hours_close']); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2 mt-4">
                <p class="text-sm text-gray-600">
                    <strong>Note:</strong> This park is currently linked to zip code ID: <?php echo $park['zip_code_id'] ?: 'None'; ?>
                    <br>Location data (city/state) is managed through the zip codes system.
                </p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="index.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update Park
            </button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
