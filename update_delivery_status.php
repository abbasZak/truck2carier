<?php
// Prevent any HTML output and capture all errors
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors as HTML

// Start session and set JSON header immediately
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Function to send JSON response and exit
function sendJsonResponse($success, $message, $data = []) {
    // Clear any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    $response = array_merge([
        'success' => $success,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ], $data);
    
    echo json_encode($response);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['farmer_id'])) {
    sendJsonResponse(false, 'User not logged in. Please log in and try again.');
}

// Get JSON input
$json_input = file_get_contents('php://input');
$input = json_decode($json_input, true);

// Validate input
if (!$input) {
    sendJsonResponse(false, 'Invalid JSON data received', ['raw_input' => $json_input]);
}

if (!isset($input['request_id']) || !isset($input['status'])) {
    sendJsonResponse(false, 'Missing required fields: request_id and status', ['received_data' => $input]);
}

$request_id = intval($input['request_id']);
$farmer_id = intval($_SESSION['farmer_id']);

if ($request_id <= 0) {
    sendJsonResponse(false, 'Invalid request ID');
}

// Database connection with error handling
try {
    // Check if connect.php exists
    if (!file_exists('connect.php')) {
        sendJsonResponse(false, 'Database configuration file not found');
    }
    
    include('connect.php');
    
    // Check if PDO connection exists
    if (!isset($pdo)) {
        sendJsonResponse(false, 'Database connection not established');
    }
    
} catch (Exception $e) {
    sendJsonResponse(false, 'Database connection failed: ' . $e->getMessage());
}

try {
    // First, verify the request exists and belongs to the user
    $check_sql = "SELECT id, status, farmer_id FROM transport_requests WHERE id = ? AND farmer_id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$request_id, $farmer_id]);
    $request = $check_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$request) {
        sendJsonResponse(false, 'Request not found or you do not have permission to modify it', [
            'request_id' => $request_id,
            'farmer_id' => $farmer_id
        ]);
    }
    
    // Check if request is in the right status
    if ($request['status'] !== 'matched') {
        sendJsonResponse(false, 'Request must be in "matched" status to mark as delivered', [
            'current_status' => $request['status'],
            'required_status' => 'matched'
        ]);
    }
    
    // Update the status to completed
    $update_sql = "UPDATE transport_requests SET status = 'completed' WHERE id = ? AND farmer_id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $result = $update_stmt->execute([$request_id, $farmer_id]);
    
    if ($result && $update_stmt->rowCount() > 0) {
        sendJsonResponse(true, 'Request marked as delivered successfully', [
            'request_id' => $request_id,
            'old_status' => $request['status'],
            'new_status' => 'completed'
        ]);
    } else {
        sendJsonResponse(false, 'Failed to update request status');
    }
    
} catch (PDOException $e) {
    sendJsonResponse(false, 'Database error occurred', ['error_code' => $e->getCode()]);
} catch (Exception $e) {
    sendJsonResponse(false, 'Unexpected error occurred: ' . $e->getMessage());
}

// This should never be reached, but just in case
sendJsonResponse(false, 'Unknown error occurred');
?>