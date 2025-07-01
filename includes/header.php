<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

$page_title = $page_title ?? 'AmyoParks - Find Your Perfect Park';
$page_description = $page_description ?? 'Find parks with amenities like pools, fields, and trails. Search and explore parks in your area.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize_input($page_title); ?></title>
    <meta name="description" content="<?php echo sanitize_input($page_description); ?>">
    <meta name="keywords" content="parks, amenities, outdoor, recreation, pools, fields, trails">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/amyoparks/css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/amyoparks/favicon.ico">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10B981',
                        secondary: '#3B82F6',
                        accent: '#059669'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/amyoparks/" class="text-2xl font-bold text-primary hover:text-accent transition-colors">
                        AmyoParks
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="/amyoparks/" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Home
                        </a>
                        <a href="/amyoparks/parks.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Parks
                        </a>
                        <a href="/amyoparks/search.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Search
                        </a>
                        <a href="/amyoparks/about.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            About
                        </a>
                        <a href="/amyoparks/contact.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Contact
                        </a>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="bg-gray-200 inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-primary hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="/amyoparks/" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="/amyoparks/parks.php" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Parks</a>
                <a href="/amyoparks/search.php" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Search</a>
                <a href="/amyoparks/about.php" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">About</a>
                <a href="/amyoparks/contact.php" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Contact</a>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <?php display_flash_message(); ?>
    </div>
    
    <!-- Main Content -->
    <main>
