<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (!is_logged_in()) {
    header('Location: /amyoparks/admin/login.php');
    exit;
}

// Handle delete
if (isset($_POST['delete']) && isset($_POST['amenity_id']) && verify_csrf_token($_POST['csrf_token'])) {
    $amenityId = (int)$_POST['amenity_id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM amenities WHERE amenity_id = ?");
        $stmt->execute([$amenityId]);
        $success = "Amenity deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting amenity: " . $e->getMessage();
    }
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchWhere = '';
$searchParams = [];

if ($search) {
    $searchWhere = " WHERE a.instance_name LIKE ? OR ac.name LIKE ?";
    $searchParams = ["%$search%", "%$search%"];
}

// Get total count
$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM amenities a 
    LEFT JOIN amenity_categories ac ON a.category_id = ac.category_id 
    " . $searchWhere
);
$countStmt->execute($searchParams);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get amenities with park and category information
$stmt = $pdo->prepare("
    SELECT 
        a.amenity_id,
        a.instance_name,
        a.created_at,
        p.name as park_name,
        ac.name as category_name,
        1 as is_active
    FROM amenities a 
    LEFT JOIN parks p ON a.park_id = p.park_id 
    LEFT JOIN amenity_categories ac ON a.category_id = ac.category_id 
    " . $searchWhere . " 
    ORDER BY a.instance_name 
    LIMIT ? OFFSET ?
");
$stmt->execute(array_merge($searchParams, [$limit, $offset]));
$amenities = $stmt->fetchAll();

$title = "Manage Amenities";
require_once '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Amenities</h1>
        <a href="add.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            Add New Amenity
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Search amenities..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Search
            </button>
            <?php if ($search): ?>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Amenities Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Instance Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Park
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Category
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($amenities)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No amenities found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($amenities as $amenity): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo sanitize_input($amenity['instance_name'] ?: 'Unnamed'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo sanitize_input($amenity['park_name'] ?: 'Unknown Park'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo sanitize_input($amenity['category_name'] ?: 'Uncategorized'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M j, Y', strtotime($amenity['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="edit.php?id=<?php echo $amenity['amenity_id']; ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this amenity?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="amenity_id" value="<?php echo $amenity['amenity_id']; ?>">
                                    <button type="submit" name="delete" 
                                            class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-6 flex justify-center">
            <nav class="flex space-x-2">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Previous
                    </a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="px-3 py-2 <?php echo $i === $page ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?> rounded-lg">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Next
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
