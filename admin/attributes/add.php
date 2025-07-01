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
    $type = sanitizeInput($_POST['type']);
    $default_value = sanitizeInput($_POST['default_value']);
    $options = sanitizeInput($_POST['options']);
    $is_required = isset($_POST['is_required']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($name)) {
        $errors[] = "Attribute name is required.";
    }
    
    if (empty($type)) {
        $errors[] = "Attribute type is required.";
    }
    
    // Check for duplicate name
    if (!empty($name)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM attributes WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "An attribute with this name already exists.";
        }
    }
    
    // Validate options for select/radio types
    if (in_array($type, ['select', 'radio', 'checkbox']) && empty($options)) {
        $errors[] = "Options are required for " . $type . " type attributes.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO attributes (name, description, type, default_value, options, is_required, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $description, $type, $default_value, $options, $is_required, $is_active]);
            
            $success = "Attribute added successfully!";
            
            // Clear form data
            $_POST = [];
        } catch (PDOException $e) {
            $errors[] = "Error adding attribute: " . $e->getMessage();
        }
    }
}

$title = "Add New Attribute";
require_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="index.php" class="text-blue-600 hover:text-blue-800 mr-4">
            ← Back to Attributes
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Add New Attribute</h1>
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
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Attribute Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select id="type" name="type" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Type</option>
                    <option value="text" <?php echo (isset($_POST['type']) && $_POST['type'] === 'text') ? 'selected' : ''; ?>>Text</option>
                    <option value="number" <?php echo (isset($_POST['type']) && $_POST['type'] === 'number') ? 'selected' : ''; ?>>Number</option>
                    <option value="select" <?php echo (isset($_POST['type']) && $_POST['type'] === 'select') ? 'selected' : ''; ?>>Select</option>
                    <option value="radio" <?php echo (isset($_POST['type']) && $_POST['type'] === 'radio') ? 'selected' : ''; ?>>Radio</option>
                    <option value="checkbox" <?php echo (isset($_POST['type']) && $_POST['type'] === 'checkbox') ? 'selected' : ''; ?>>Checkbox</option>
                    <option value="boolean" <?php echo (isset($_POST['type']) && $_POST['type'] === 'boolean') ? 'selected' : ''; ?>>Boolean</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>

            <div>
                <label for="default_value" class="block text-sm font-medium text-gray-700 mb-2">Default Value</label>
                <input type="text" id="default_value" name="default_value" 
                       value="<?php echo htmlspecialchars($_POST['default_value'] ?? ''); ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div id="options-field" style="display: none;">
                <label for="options" class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <textarea id="options" name="options" rows="3" 
                          placeholder="Enter options separated by new lines"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($_POST['options'] ?? ''); ?></textarea>
                <p class="text-sm text-gray-500 mt-1">Enter each option on a new line (for select, radio, checkbox types)</p>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="is_required" name="is_required" value="1" 
                       <?php echo isset($_POST['is_required']) ? 'checked' : ''; ?>
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_required" class="ml-2 text-sm font-medium text-gray-700">Required</label>
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
                Add Attribute
            </button>
        </div>
    </form>
</div>

<script>
// Show/hide options field based on type
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const optionsField = document.getElementById('options-field');
    
    if (['select', 'radio', 'checkbox'].includes(type)) {
        optionsField.style.display = 'block';
    } else {
        optionsField.style.display = 'none';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const type = document.getElementById('type').value;
    const optionsField = document.getElementById('options-field');
    
    if (['select', 'radio', 'checkbox'].includes(type)) {
        optionsField.style.display = 'block';
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
