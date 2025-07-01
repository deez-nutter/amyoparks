<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Redirect to login if not authenticated
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// Redirect to dashboard if authenticated
header('Location: dashboard.php');
exit;
?>
