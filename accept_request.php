<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Start session BEFORE including connect.php
session_start();
include("connect.php");

// Debugging: Log the received POST data
file_put_contents('debug.txt', "Script accessed at ".date('Y-m-d H:i:s')."\n", FILE_APPEND);
file_put_contents('accept_debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents('accept_debug.log', "SESSION Data: " . print_r($_SESSION, true) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Get and validate request ID
    $request_id = $_POST['requestId'] ?? null;
    $farmer_id = $_POST['farmerId'] ?? null;
    
    // Remove any whitespace and ensure it's not empty
    $request_id = $request_id ? trim($request_id) : null;
    $farmer_id = $farmer_id ? trim($farmer_id) : null;
    
    $new_status = "matched";
    
    // Debugging: Verify the values
    file_put_contents('accept_debug.log', "\nCleaned Request ID: '$request_id', Status: '$new_status', Farmer ID: '$farmer_id'\n", FILE_APPEND);

    if ($request_id && $request_id !== '' && $farmer_id && $farmer_id !== '') {
        try {
            // Begin transaction
            $pdo->beginTransaction();
            
            // Get user ID from session (this is what you're calling 'tractor_id')
            $user_id = $_SESSION['tractor_id'] ?? null;
            
            // Debugging: Log user ID
            file_put_contents('accept_debug.log', "User ID from session: '$user_id'\n", FILE_APPEND);
            
            if (!isset($user_id)) {
                $pdo->rollBack();
                echo json_encode([
                    "success" => false,
                    "message" => "User ID not found. Please ensure you are logged in properly.",
                    "debug" => [
                        "session_tractor_id" => $_SESSION['tractor_id'] ?? null,
                        "session_keys" => array_keys($_SESSION ?? [])
                    ]
                ]);
                exit;
            }
            
            // ==== CRITICAL FIX: GET ACTUAL TRUCK ID FROM USER ID ====
            // Get the actual truck ID from the trucks table using the user ID
            $truck_sql = "SELECT id FROM trucks WHERE owner_id = ?";
            $truck_stmt = $pdo->prepare($truck_sql);
            $truck_stmt->execute([$user_id]);
            $truck_data = $truck_stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$truck_data) {
                $pdo->rollBack();
                echo json_encode([
                    "success" => false,
                    "message" => "No truck found for this user. Please register a truck first.",
                    "debug" => [
                        "user_id" => $user_id,
                        "truck_check_result" => $truck_data
                    ]
                ]);
                exit;
            }
            
            $truck_id = $truck_data['id'];
            file_put_contents('accept_debug.log', "Actual Truck ID found: '$truck_id' for User ID: '$user_id'\n", FILE_APPEND);
            // ==== END OF CRITICAL FIX ====
            
            // Verify the farmer exists in the farmers table
            $farmer_check_sql = "SELECT id FROM farmers WHERE user_id = ?";
            $farmer_check_stmt = $pdo->prepare($farmer_check_sql);
            $farmer_check_stmt->execute([$farmer_id]);
            $farmer_exists = $farmer_check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$farmer_exists) {
                $pdo->rollBack();
                echo json_encode([
                    "success" => false,
                    "message" => "Farmer not found in database. Please contact administrator.",
                    "debug" => [
                        "farmer_id" => $farmer_id,
                        "farmer_check_result" => $farmer_exists
                    ]
                ]);
                exit;
            }
            
            // Check if the request exists
            $check_sql = "SELECT id, status FROM transport_requests WHERE id = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$request_id]);
            $existing_record = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            file_put_contents('accept_debug.log', "Request search result: " . print_r($existing_record, true) . "\n", FILE_APPEND);
            
            if (!$existing_record) {
                $pdo->rollBack();
                echo json_encode([
                    "success" => false, 
                    "message" => "Request not found with ID: $request_id"
                ]);
                exit;
            }
            
            // Check if already matched
            if ($existing_record['status'] === 'matched') {
                $pdo->rollBack();
                echo json_encode([
                    "success" => false, 
                    "message" => "Request has already been matched",
                    "debug" => [
                        "request_id" => $request_id,
                        "current_status" => $existing_record['status']
                    ]
                ]);
                exit;
            }
            
            // Now update the record
            $sql = "UPDATE transport_requests SET status = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            // Debugging: Log the actual query being executed
            file_put_contents('accept_debug.log', "Query: $sql\nParams: ['$new_status', '$request_id']\n", FILE_APPEND);
            
            $result = $stmt->execute([$new_status, $request_id]);
            
            // Debugging: Check affected rows
            $affected = $stmt->rowCount();
            file_put_contents('accept_debug.log', "Execute result: " . ($result ? 'true' : 'false') . "\n", FILE_APPEND);
            file_put_contents('accept_debug.log', "Affected rows: $affected\n", FILE_APPEND);
            
            if ($result && $affected > 0) {
                // Insert into matches table using the ACTUAL truck ID
                $match_sql = "INSERT INTO matches (request_id, truck_id, farmers_id, matched_at) VALUES (?, ?, ?, NOW())";
                $match_stmt = $pdo->prepare($match_sql);
                $match_result = $match_stmt->execute([$request_id, $truck_id, $farmer_id]);
                
                file_put_contents('accept_debug.log', "Match insertion params: request_id='$request_id', truck_id='$truck_id', farmer_id='$farmer_id'\n", FILE_APPEND);
                
                if (!$match_result) {
                    $pdo->rollBack();
                    $error_info = $match_stmt->errorInfo();
                    file_put_contents('accept_debug.log', "Match insertion failed: " . print_r($error_info, true) . "\n", FILE_APPEND);
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to create match record: " . $error_info[2],
                        "debug" => [
                            "error_info" => $error_info,
                            "match_params" => [
                                "request_id" => $request_id,
                                "truck_id" => $truck_id,
                                "farmer_id" => $farmer_id
                            ]
                        ]
                    ]);
                    exit;
                }
                
                $match_id = $pdo->lastInsertId();
                file_put_contents('accept_debug.log', "Match created with ID: $match_id\n", FILE_APPEND);
                
                // Verify the update by selecting the record again
                $verify_stmt = $pdo->prepare("SELECT status FROM transport_requests WHERE id = ?");
                $verify_stmt->execute([$request_id]);
                $updated_record = $verify_stmt->fetch(PDO::FETCH_ASSOC);
                
                file_put_contents('accept_debug.log', "Verification - Updated record: " . print_r($updated_record, true) . "\n", FILE_APPEND);
                
                // Commit the transaction
                $pdo->commit();
                
                echo json_encode([
                    "success" => true, 
                    "message" => "Transport request accepted successfully! Match created.",
                    "data" => [
                        "request_id" => $request_id,
                        "match_id" => $match_id,
                        "truck_id" => $truck_id,
                        "farmer_id" => $farmer_id,
                        "status" => $new_status
                    ]
                ]);
            } else {
                $pdo->rollBack();
                // Get error info if available
                $error_info = $stmt->errorInfo();
                echo json_encode([
                    "success" => false, 
                    "message" => "Failed to update request. No rows affected.",
                    "debug" => [
                        "request_id" => $request_id,
                        "affected_rows" => $affected,
                        "execute_result" => $result,
                        "error_info" => $error_info
                    ]
                ]);
            }
        } catch(PDOException $e) {
            $pdo->rollBack();
            file_put_contents('accept_debug.log', "PDO Exception: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode([
                "success" => false, 
                "message" => "Database error: " . $e->getMessage(),
                "debug" => [
                    "error_code" => $e->getCode(),
                    "error_message" => $e->getMessage()
                ]
            ]);
        }
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Request ID and Farmer ID are required and cannot be empty",
            "debug" => [
                "received_request_id" => $request_id,
                "received_farmer_id" => $farmer_id,
                "post_data" => $_POST
            ]
        ]);
    }
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Invalid request method. Expected POST, received: " . $_SERVER['REQUEST_METHOD']
    ]);
}
?>