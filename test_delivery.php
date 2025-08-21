<?php
// Test file to debug delivery status issues
session_start();
header('Content-Type: application/json');

echo json_encode([
    'test' => 'success',
    'session_exists' => isset($_SESSION['farmer_id']),
    'farmer_id' => isset($_SESSION['farmer_id']) ? $_SESSION['farmer_id'] : null,
    'php_version' => phpversion(),
    'connect_file_exists' => file_exists('connect.php'),
    'post_data' => file_get_contents('php://input'),
    'request_method' => $_SERVER['REQUEST_METHOD'],
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set'
]);
?>