<?php
$page_title = 'Contact Us - AmyoParks';
$page_description = 'Get in touch with AmyoParks. Send us your questions, suggestions, or feedback.';

require_once 'includes/header.php';

$success_message = '';
$error_message = '';

// Create contacts table if it doesn't exist
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contacts (
            contact_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {
    error_log("Error creating contacts table: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error_message = 'Security validation failed. Please try again.';
    } else {
        $name = sanitize_input($_POST['name'] ?? '');
        $email = sanitize_input($_POST['email'] ?? '');
        $message = sanitize_input($_POST['message'] ?? '');
        
        // Validate form data
        if (empty($name) || empty($email) || empty($message)) {
            $error_message = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Please enter a valid email address.';
        } elseif (strlen($message) < 10) {
            $error_message = 'Please provide a more detailed message (at least 10 characters).';
        } else {
            // Save to database
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO contacts (name, email, message) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$name, $email, $message]);
                
                $success_message = 'Thank you for your message! We\'ll get back to you soon.';
                
                // Clear form data
                $name = $email = $message = '';
                
            } catch (PDOException $e) {
                error_log("Error saving contact form: " . $e->getMessage());
                $error_message = 'Sorry, there was a problem sending your message. Please try again.';
            }
        }
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Have questions, suggestions, or feedback? We'd love to hear from you!
        </p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="text-xl font-semibold">Send us a Message</h2>
                </div>
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    <?php if ($success_message): ?>
                        <div class="alert alert-success mb-6">
                            <?php echo sanitize_input($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-error mb-6">
                            <?php echo sanitize_input($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/amyoparks/contact.php" data-validate>
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                   value="<?php echo sanitize_input($name ?? ''); ?>"
                                   class="form-input"
                                   placeholder="Your full name">
                        </div>
                        
                        <!-- Email Field -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" required
                                   value="<?php echo sanitize_input($email ?? ''); ?>"
                                   class="form-input"
                                   placeholder="your.email@example.com">
                        </div>
                        
                        <!-- Message Field -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" id="message" rows="6" required
                                      class="form-textarea"
                                      placeholder="Tell us how we can help you..."><?php echo sanitize_input($message ?? ''); ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn-primary px-8 py-3">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="space-y-8">
            <!-- Contact Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Get in Touch</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="flex items-start">
                        <div class="w-6 h-6 text-primary mr-4 mt-1">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Contact Form</h4>
                            <p class="text-gray-600">Use the contact form to reach us</p>
                            <p class="text-sm text-gray-500 mt-1">We typically respond within 24 hours</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-6 h-6 text-primary mr-4 mt-1">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Response Time</h4>
                            <p class="text-gray-600">Within 1-2 business days</p>
                            <p class="text-sm text-gray-500 mt-1">Monday - Friday, 9 AM - 5 PM</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">Frequently Asked Questions</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">How do I suggest a park to be added?</h4>
                        <p class="text-gray-600 text-sm">
                            Send us the park name, location, and any amenity information you have. 
                            We'll research and add it to our database.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Is the park information up to date?</h4>
                        <p class="text-gray-600 text-sm">
                            We strive to keep information current, but park amenities and hours can change. 
                            Always verify details with the park directly before visiting.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Can I report incorrect information?</h4>
                        <p class="text-gray-600 text-sm">
                            Absolutely! Please let us know if you find outdated or incorrect information 
                            so we can update our database.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">How do I contact you?</h4>
                        <p class="text-gray-600 text-sm">
                            Use our contact form above. All inquiries are stored and reviewed by our team.
                            We'll get back to you as soon as possible.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Feedback Types -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">We Welcome</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="text-gray-700">General questions about parks</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="text-gray-700">Suggestions for new features</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="text-gray-700">Reports of incorrect information</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="text-gray-700">Park recommendations to add</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="text-gray-700">Technical support requests</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-3"></div>
                            <span class="text-gray-700">General feedback and suggestions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alternative Contact Methods -->
    <div class="mt-16 text-center">
        <div class="card max-w-4xl mx-auto">
            <div class="card-body">
                <h3 class="text-xl font-semibold mb-4">Other Ways to Connect</h3>
                <p class="text-gray-600 mb-6">
                    While email is our preferred contact method, you can also find us through these channels:
                </p>
                <div class="flex justify-center space-x-8">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Search our FAQ</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Browse Help Topics</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Send Direct Message</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
