<?php
session_start();
require_once '../../includes/db.php';

// Get all websites for the dropdown
$stmt = $pdo->prepare("SELECT * FROM websites ORDER BY description");
$stmt->execute();
$websites = $stmt->fetchAll();

// Get recent scraping logs (if table exists)
$recent_logs = [];
try {
    $stmt = $pdo->prepare("
        SELECT sl.*, w.description as website_name 
        FROM scraping_logs sl 
        JOIN websites w ON sl.website_id = w.website_id 
        ORDER BY sl.created_at DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $recent_logs = $stmt->fetchAll();
} catch (PDOException $e) {
    // Table doesn't exist yet - ignore error
    $recent_logs = [];
}

$title = 'Scraping Portal - AmyoParks Admin';
?>

<?php include '../includes/header.php'; ?>

<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <svg class="inline w-8 h-8 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                </svg>
                Scraping Portal
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Import park data from external websites
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="manage.php" class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Manage Websites
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Scraping Control Panel -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Start Scraping
                    </h3>
                    
                    <form id="scrapingForm" class="space-y-4">
                        <div>
                            <label for="website_id" class="block text-sm font-medium text-gray-700">
                                Select Website
                            </label>
                            <select id="website_id" name="website_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                                <option value="">Choose a website...</option>
                                <?php foreach ($websites as $website): ?>
                                    <option value="<?= $website['website_id'] ?>" <?= $website['website_id'] == 3 ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($website['description']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="websiteInfo" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">Website Details</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div><strong>Base URL:</strong> <span id="baseUrl"></span></div>
                                    <div><strong>Description:</strong> <span id="description"></span></div>
                                    <div><strong>Last Scraped:</strong> <span id="lastScraped"></span></div>
                                    <div><strong>Status:</strong> <span id="status"></span></div>
                                    <div><strong>Notes:</strong> <span id="notes"></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="submit" id="startScraping" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m6-6V7a2 2 0 00-2-2H5a2 2 0 00-2 2v3m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H5a2 2 0 00-2 2v3z"></path>
                                </svg>
                                Start Scraping
                            </button>
                            <button type="button" id="testConnection" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Test Connection
                            </button>
                        </div>
                    </form>

                    <!-- Progress Section -->
                    <div id="progressSection" class="hidden mt-6">
                        <div class="border-t pt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Scraping Progress</h4>
                            <div class="bg-gray-200 rounded-full h-2 mb-2">
                                <div id="progressBar" class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <div id="progressText" class="text-sm text-gray-600"></div>
                            <div id="progressLog" class="mt-4 bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-xs max-h-64 overflow-y-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics & Recent Activity -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Scraping Statistics
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Websites</span>
                            <span class="text-sm font-medium"><?= count($websites) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Active Websites</span>
                            <span class="text-sm font-medium"><?= count(array_filter($websites, function($w) { return isset($w['status']) && $w['status'] === 'active'; })) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Parks Scraped</span>
                            <span class="text-sm font-medium"><?= array_sum(array_map(function($w) { return $w['total_parks_imported'] ?? 0; }, $websites)) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Recent Activity
                    </h3>
                    <div class="space-y-3">
                        <?php if (empty($recent_logs)): ?>
                            <p class="text-sm text-gray-500">No scraping activity yet.</p>
                        <?php else: ?>
                            <?php foreach ($recent_logs as $log): ?>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <?php if ($log['errors']): ?>
                                            <div class="w-2 h-2 bg-red-400 rounded-full mt-2"></div>
                                        <?php else: ?>
                                            <div class="w-2 h-2 bg-green-400 rounded-full mt-2"></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">
                                            <?= htmlspecialchars($log['website_name']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <?= htmlspecialchars($log['action']) ?> - 
                                            <?= $log['parks_imported'] ?> parks imported
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            <?= date('M j, Y g:i A', strtotime($log['created_at'])) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const websiteSelect = document.getElementById('website_id');
    const websiteInfo = document.getElementById('websiteInfo');
    const scrapingForm = document.getElementById('scrapingForm');
    const progressSection = document.getElementById('progressSection');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressLog = document.getElementById('progressLog');

    // Website data
    const websites = <?= json_encode($websites) ?>;

    // Handle website selection
    websiteSelect.addEventListener('change', function() {
        const selectedId = this.value;
        if (selectedId) {
            const website = websites.find(w => w.website_id == selectedId);
            if (website) {
                document.getElementById('baseUrl').textContent = website.url;
                document.getElementById('description').textContent = website.description || 'Not specified';
                document.getElementById('lastScraped').textContent = website.last_scraped ? new Date(website.last_scraped).toLocaleString() : 'Never';
                document.getElementById('status').textContent = website.status || 'Unknown';
                document.getElementById('notes').textContent = website.notes || 'No notes';
                websiteInfo.classList.remove('hidden');
            }
        } else {
            websiteInfo.classList.add('hidden');
        }
    });

    // Trigger change event for pre-selected option
    if (websiteSelect.value) {
        websiteSelect.dispatchEvent(new Event('change'));
    }

    // Handle form submission
    scrapingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const websiteId = websiteSelect.value;
        if (!websiteId) {
            alert('Please select a website to scrape.');
            return;
        }

        startScraping(websiteId);
    });

    // Test connection button
    document.getElementById('testConnection').addEventListener('click', function() {
        const websiteId = websiteSelect.value;
        if (!websiteId) {
            alert('Please select a website first.');
            return;
        }
        testConnection(websiteId);
    });

    function startScraping(websiteId) {
        progressSection.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = 'Initializing scraping...';
        progressLog.textContent = '';

        const button = document.getElementById('startScraping');
        button.disabled = true;
        button.textContent = 'Scraping...';

        // Make AJAX request to start scraping
        fetch('scrape.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'start_scraping',
                website_id: websiteId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Start polling for progress
                pollProgress(data.scraping_id);
            } else {
                progressText.textContent = 'Error: ' + data.message;
                progressLog.textContent += '\nError: ' + data.message;
                button.disabled = false;
                button.textContent = 'Start Scraping';
            }
        })
        .catch(error => {
            progressText.textContent = 'Error: ' + error.message;
            progressLog.textContent += '\nError: ' + error.message;
            button.disabled = false;
            button.textContent = 'Start Scraping';
        });
    }

    function testConnection(websiteId) {
        const button = document.getElementById('testConnection');
        button.disabled = true;
        button.textContent = 'Testing...';

        fetch('scrape.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'test_connection',
                website_id: websiteId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Connection successful! Found ' + data.parks_found + ' parks.');
            } else {
                alert('Connection failed: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.textContent = 'Test Connection';
        });
    }

    function pollProgress(scrapingId) {
        const interval = setInterval(() => {
            fetch('scrape.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_progress',
                    scraping_id: scrapingId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    progressBar.style.width = data.progress + '%';
                    progressText.textContent = data.status;
                    
                    if (data.log) {
                        progressLog.textContent += '\n' + data.log;
                        progressLog.scrollTop = progressLog.scrollHeight;
                    }

                    if (data.completed) {
                        clearInterval(interval);
                        const button = document.getElementById('startScraping');
                        button.disabled = false;
                        button.textContent = 'Start Scraping';
                        
                        // Refresh the page to update statistics
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                }
            })
            .catch(error => {
                console.error('Error polling progress:', error);
                clearInterval(interval);
                const button = document.getElementById('startScraping');
                button.disabled = false;
                button.textContent = 'Start Scraping';
            });
        }, 2000);
    }
});
</script>

<?php include '../includes/footer.php'; ?>
