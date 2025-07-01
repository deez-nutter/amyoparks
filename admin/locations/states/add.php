<?php
session_start();
require_once '../../../includes/db.php';
require_once '../../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /amyoparks/admin/login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCSRF($_POST['csrf_token'])) {
    $name = sanitizeInput($_POST['name']);
    $abbreviation = strtoupper(sanitizeInput($_POST['abbreviation']));
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($name)) {
        $errors[] = "State name is required.";
    }
    
    if (empty($abbreviation)) {
        $errors[] = "State abbreviation is required.";
    } elseif (strlen($abbreviation) !== 2) {
        $errors[] = "State abbreviation must be exactly 2 characters.";
    }
    
    // Check for duplicate name
    if (!empty($name)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM states WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "A state with this name already exists.";
        }
    }
    
    // Check for duplicate abbreviation
    if (!empty($abbreviation)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM states WHERE abbreviation = ?");
        $stmt->execute([$abbreviation]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "A state with this abbreviation already exists.";
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO states (name, abbreviation, is_active, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $abbreviation, $is_active]);
            
            $success = "State added successfully!";
            
            // Clear form data
            $_POST = [];
        } catch (PDOException $e) {
            $errors[] = "Error adding state: " . $e->getMessage();
        }
    }
}

$title = "Add New State";
require_once '../../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="index.php" class="text-blue-600 hover:text-blue-800 mr-4">
            ← Back to States
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Add New State</h1>
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
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">State Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="abbreviation" class="block text-sm font-medium text-gray-700 mb-2">Abbreviation *</label>
                <input type="text" id="abbreviation" name="abbreviation" required maxlength="2" 
                       value="<?php echo htmlspecialchars($_POST['abbreviation'] ?? ''); ?>"
                       placeholder="e.g., CA, NY, TX"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">2-letter state code (will be converted to uppercase)</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       <?php echo (isset($_POST['is_active']) || !isset($_POST['name'])) ? 'checked' : ''; ?>
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active</label>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="index.php" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Add State
            </button>
        </div>
    </form>
</div>

<script>
// Auto-uppercase abbreviation
document.getElementById('abbreviation').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?php require_once '../../includes/footer.php'; ?>
