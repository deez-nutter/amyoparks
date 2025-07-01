<?php
session_start();
require_once '../../includes/db.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_website') {
        $websiteId = $_POST['website_id'];
        $scrapingConfig = $_POST['scraping_config'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        
        try {
            $stmt = $pdo->prepare("
                UPDATE websites 
                SET scraping_config = ?, status = ?, notes = ?, updated_at = NOW() 
                WHERE website_id = ?
            ");
            $stmt->execute([$scrapingConfig, $status, $notes, $websiteId]);
            $success = "Website configuration updated successfully!";
        } catch (Exception $e) {
            $error = "Error updating website: " . $e->getMessage();
        }
    }
}

// Get website for editing
$editWebsite = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM websites WHERE website_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editWebsite = $stmt->fetch();
}

// Get all websites
$stmt = $pdo->prepare("SELECT * FROM websites ORDER BY description");
$stmt->execute();
$websites = $stmt->fetchAll();

$title = 'Website Management - Scraping Portal';
?>

<?php include '../includes/header.php'; ?>

<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <a href="/amyoparks/admin/scraping/" class="text-primary hover:text-primary-600">
                    <svg class="inline w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                Website Management
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Configure scraping settings for websites
            </p>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="mt-4 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800"><?= htmlspecialchars($success) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="mt-4 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Website List -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Available Websites
                </h3>
                
                <div class="space-y-4">
                    <?php foreach ($websites as $website): ?>
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">
                                        <?= htmlspecialchars($website['description']) ?>
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <?= htmlspecialchars($website['url']) ?>
                                    </p>
                                    <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                        <span>Status: 
                                            <span class="<?= ($website['status'] ?? 'unknown') === 'active' ? 'text-green-600' : 'text-gray-600' ?>">
                                                <?= htmlspecialchars($website['status'] ?? 'Unknown') ?>
                                            </span>
                                        </span>
                                        <?php if ($website['last_scraped']): ?>
                                            <span>Last scraped: <?= date('M j, Y', strtotime($website['last_scraped'])) ?></span>
                                        <?php endif; ?>
                                        <?php if ($website['total_parks_imported'] > 0): ?>
                                            <span>Parks: <?= $website['total_parks_imported'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-4">
                                    <a href="?edit=<?= $website['website_id'] ?>" class="text-primary hover:text-primary-600 text-sm font-medium">
                                        Configure
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Configuration Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <?= $editWebsite ? 'Configure Website' : 'Select a Website to Configure' ?>
                </h3>
                
                <?php if ($editWebsite): ?>
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="update_website">
                        <input type="hidden" name="website_id" value="<?= $editWebsite['website_id'] ?>">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Website
                            </label>
                            <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                <?= htmlspecialchars($editWebsite['description']) ?>
                            </p>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="active" <?= ($editWebsite['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($editWebsite['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="error" <?= ($editWebsite['status'] ?? '') === 'error' ? 'selected' : '' ?>>Error</option>
                            </select>
                        </div>

                        <div>
                            <label for="scraping_config" class="block text-sm font-medium text-gray-700">
                                Scraping Configuration (JSON)
                            </label>
                            <textarea id="scraping_config" name="scraping_config" rows="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm font-mono text-xs"><?= htmlspecialchars($editWebsite['scraping_config'] ?? json_encode([
                                'park_name_selector' => '.park-title, h1, .entry-title',
                                'park_address_selector' => '.park-address, .address',
                                'park_description_selector' => '.park-description, .entry-content',
                                'amenities_selector' => '.amenities-list li, .facilities li',
                                'hours_selector' => '.hours, .park-hours',
                                'phone_selector' => '.phone, .contact-phone'
                            ], JSON_PRETTY_PRINT)) ?></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                JSON configuration for CSS selectors used to extract park data
                            </p>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">
                                Notes
                            </label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"><?= htmlspecialchars($editWebsite['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="manage.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                Save Configuration
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">
                        Select a website from the list to configure its scraping settings.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
