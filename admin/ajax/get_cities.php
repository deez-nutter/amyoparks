<?php
session_start();
require_once '../../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    exit('Unauthorized');
}

// Check if state_id is provided
if (!isset($_POST['state_id']) || empty($_POST['state_id'])) {
    http_response_code(400);
    exit('State ID is required');
}

$stateId = (int)$_POST['state_id'];

try {
    $stmt = $pdo->prepare("SELECT id, name FROM cities WHERE state_id = ? ORDER BY name");
    $stmt->execute([$stateId]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($cities);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>
