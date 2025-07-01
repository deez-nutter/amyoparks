<?php
session_start();
require_once '../../../includes/db.php';
require_once '../../../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /amyoparks/admin/login.php');
    exit;
}

// Handle delete
if (isset($_POST['delete']) && isset($_POST['state_id']) && validateCSRF($_POST['csrf_token'])) {
    $stateId = (int)$_POST['state_id'];
    
    try {
        // Check if state is used by any parks or cities
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM parks WHERE state_id = ?");
        $checkStmt->execute([$stateId]);
        $parkCount = $checkStmt->fetchColumn();
        
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM cities WHERE state_id = ?");
        $checkStmt->execute([$stateId]);
        $cityCount = $checkStmt->fetchColumn();
        
        if ($parkCount > 0 || $cityCount > 0) {
            $error = "Cannot delete state. It is being used by $parkCount park(s) and $cityCount city/cities.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM states WHERE id = ?");
            $stmt->execute([$stateId]);
            $success = "State deleted successfully.";
        }
    } catch (PDOException $e) {
        $error = "Error deleting state: " . $e->getMessage();
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
    $searchWhere = " WHERE name LIKE ? OR abbreviation LIKE ?";
    $searchParams = ["%$search%", "%$search%"];
}

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM states" . $searchWhere);
$countStmt->execute($searchParams);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get states with counts
$stmt = $pdo->prepare("SELECT s.*, 
                               COUNT(DISTINCT p.id) as park_count,
                               COUNT(DISTINCT c.id) as city_count
                       FROM states s 
                       LEFT JOIN parks p ON s.id = p.state_id 
                       LEFT JOIN cities c ON s.id = c.state_id 
                       " . $searchWhere . " 
                       GROUP BY s.id 
                       ORDER BY s.name 
                       LIMIT ? OFFSET ?");
$stmt->execute(array_merge($searchParams, [$limit, $offset]));
$states = $stmt->fetchAll();

$title = "Manage States";
require_once '../../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="text-sm breadcrumbs mb-2">
                <a href="../index.php" class="text-blue-600 hover:text-blue-800">Locations</a>
                <span class="mx-2">/</span>
                <span class="text-gray-500">States</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-800">Manage States</h1>
        </div>
        <a href="add.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            Add New State
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
                   placeholder="Search states..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
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

    <!-- States Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Abbreviation
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cities
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Parks
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($states)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No states found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($states as $state): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($state['name']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">
                                    <?php echo htmlspecialchars($state['abbreviation']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    <?php echo $state['city_count']; ?> cities
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                    <?php echo $state['park_count']; ?> parks
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $state['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $state['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="edit.php?id=<?php echo $state['id']; ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this state?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRF(); ?>">
                                    <input type="hidden" name="state_id" value="<?php echo $state['id']; ?>">
                                    <button type="submit" name="delete" 
                                            class="text-red-600 hover:text-red-900 <?php echo ($state['park_count'] > 0 || $state['city_count'] > 0) ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                                            <?php echo ($state['park_count'] > 0 || $state['city_count'] > 0) ? 'disabled' : ''; ?>>
                                        Delete
                                    </button>
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

<?php require_once '../../includes/footer.php'; ?>
