<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Create admin_users table if it doesn't exist
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Check if default admin exists, if not create one
    $check_admin = $pdo->query("SELECT COUNT(*) FROM admin_users WHERE username = 'admin'");
    if ($check_admin->fetchColumn() == 0) {
        $password_hash = password_hash('Admin123!', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash, email, is_active) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $password_hash, 'admin@amyoparks.com', 1]);
    }
} catch (PDOException $e) {
    error_log("Error creating admin table: " . $e->getMessage());
}

// Redirect to dashboard if already logged in
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error_message = 'Security validation failed. Please try again.';
    } else {
        $username = sanitize_input($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $error_message = 'Please enter both username and password.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id, password_hash FROM admin_users WHERE username = ? AND is_active = 1");
                $stmt->execute([$username]);
                $admin = $stmt->fetch();
                
                if ($admin && password_verify($password, $admin['password_hash'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $username;
                    $_SESSION['admin_logged_in'] = true;
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error_message = 'Invalid username or password.';
                }
            } catch (PDOException $e) {
                error_log("Login error: " . $e->getMessage());
                $error_message = 'Login failed. Please try again.';
            }
        }
    }
}

$page_title = 'Admin Login - AmyoParks';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize_input($page_title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/amyoparks/css/styles.css">
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
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                AmyoParks Admin
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sign in to manage parks and amenities
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo sanitize_input($error_message); ?>
                </div>
            <?php endif; ?>
            
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input id="username" name="username" type="text" required 
                           class="form-input" placeholder="Username"
                           value="<?php echo sanitize_input($_POST['username'] ?? ''); ?>">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="form-input" placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary w-full py-3">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"></path>
                    </svg>
                    Sign In
                </button>
            </div>
            
            <div class="text-center">
                <a href="/amyoparks/" class="text-primary hover:text-accent text-sm">
                    ← Back to Website
                </a>
            </div>
        </form>
        
        <div class="mt-6 text-center text-xs text-gray-500">
            <p>Default credentials: admin / Admin123!</p>
        </div>
    </div>
</body>
</html>
