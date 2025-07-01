<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$page_title = 'Dashboard - AmyoParks Admin';
require_once 'includes/header.php';

// Get statistics
try {
    $stats_stmt = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM parks) AS park_count,
            (SELECT COUNT(*) FROM amenities) AS amenity_count,
            (SELECT COUNT(*) FROM amenity_categories) AS category_count,
            (SELECT COUNT(DISTINCT s.state_id) FROM states s 
             JOIN cities c ON s.state_id = c.state_id 
             JOIN zip_codes z ON c.city_id = z.city_id 
             JOIN parks p ON z.zip_code_id = p.zip_code_id) AS attribute_count,
            (SELECT COUNT(*) FROM contacts) AS contact_count,
            (SELECT COUNT(*) FROM contacts WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) AS recent_contacts
    ");
    $stats = $stats_stmt->fetch();
} catch (PDOException $e) {
    error_log("Error fetching stats: " . $e->getMessage());
    $stats = [
        'park_count' => 0, 
        'amenity_count' => 0, 
        'category_count' => 0, 
        'attribute_count' => 0,
        'contact_count' => 0,
        'recent_contacts' => 0
    ];
}

// Get recent contacts
try {
    $contacts_stmt = $pdo->query("
        SELECT id, name, email, message, created_at 
        FROM contacts 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $recent_contacts = $contacts_stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching contacts: " . $e->getMessage());
    $recent_contacts = [];
}

// Handle contact action (mark as read, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        redirect_with_message('/amyoparks/admin/dashboard.php', 'Security validation failed.', 'error');
    }
    
    $contact_id = intval($_POST['contact_id'] ?? 0);
    $action = $_POST['action'];
    
    try {
        if ($action === 'delete' && $contact_id) {
            $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
            $stmt->execute([$contact_id]);
            redirect_with_message('/amyoparks/admin/dashboard.php', 'Contact inquiry deleted successfully.');
        }
    } catch (PDOException $e) {
        error_log("Error handling contact action: " . $e->getMessage());
        redirect_with_message('/amyoparks/admin/dashboard.php', 'Action failed. Please try again.', 'error');
    }
}
?>

<div class="px-4 py-6 sm:px-0">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-gray-600">Overview of your park management system</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Parks -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Parks</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo number_format(intval($stats['park_count'])); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="/amyoparks/admin/parks/" class="font-medium text-primary hover:text-accent">View all parks</a>
                </div>
            </div>
        </div>
        
        <!-- Amenities -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Amenities</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo number_format(intval($stats['amenity_count'])); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="/amyoparks/admin/amenities/" class="font-medium text-secondary hover:text-blue-700">View all amenities</a>
                </div>
            </div>
        </div>
        
        <!-- Categories -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo number_format(intval($stats['category_count'])); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="/amyoparks/admin/categories/" class="font-medium text-green-600 hover:text-green-700">Manage categories</a>
                </div>
            </div>
        </div>
        
        <!-- Contact Inquiries -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Contact Inquiries</dt>
                            <dd class="text-lg font-medium text-gray-900"><?php echo number_format(intval($stats['contact_count'])); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-yellow-600"><?php echo intval($stats['recent_contacts']); ?> this week</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="/amyoparks/admin/parks/create.php" class="btn-primary text-center text-sm py-3">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Park
                    </a>
                    <a href="/amyoparks/admin/amenities/create.php" class="btn-secondary text-center text-sm py-3">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Amenity
                    </a>
                    <a href="/amyoparks/admin/categories/create.php" class="btn-outline text-center text-sm py-3">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Add Category
                    </a>
                    <a href="/amyoparks/" target="_blank" class="btn-outline text-center text-sm py-3">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        View Website
                    </a>
                </div>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">System Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Attributes</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo number_format(intval($stats['attribute_count'])); ?> types</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Database</dt>
                        <dd class="mt-1 text-sm text-gray-900">MySQL Connected</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo date('M j, Y g:i A'); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Admin User</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo sanitize_input($_SESSION['admin_username']); ?></dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- Recent Contact Inquiries -->
    <?php if (!empty($recent_contacts)): ?>
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Contact Inquiries</h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        <?php foreach ($recent_contacts as $contact): ?>
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo sanitize_input($contact['name']); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            <?php echo sanitize_input($contact['email']); ?>
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <?php echo sanitize_input(substr($contact['message'], 0, 100)); ?>
                                            <?php if (strlen($contact['message']) > 100): ?>...<?php endif; ?>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <?php echo date('M j, Y g:i A', strtotime($contact['created_at'])); ?>
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form method="POST" action="dashboard.php" class="inline">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                    onclick="return confirm('Delete this inquiry?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php if (count($recent_contacts) >= 10): ?>
                    <div class="mt-6">
                        <p class="text-sm text-gray-500">Showing latest 10 inquiries</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No contact inquiries</h3>
                <p class="mt-1 text-sm text-gray-500">No one has contacted you yet.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
