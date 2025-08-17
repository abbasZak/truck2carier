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

include("connect.php");

// Debugging: Log the received POST data
file_put_contents('debug.txt', "Script accessed at ".date('Y-m-d H:i:s')."\n", FILE_APPEND);
file_put_contents('accept_debug.log', "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Get and validate request ID
    $request_id = $_POST['requestId'] ?? null;
    
    // Remove any whitespace and ensure it's not empty
    if ($request_id !== null) {
        $request_id = trim($request_id);
    }
    
    $new_status = "matched";
    
    // Debugging: Verify the values
    file_put_contents('accept_debug.log', "\nCleaned Request ID: '$request_id', Status: '$new_status'\n", FILE_APPEND);
    file_put_contents('accept_debug.log', "Request ID type: " . gettype($request_id) . "\n", FILE_APPEND);
    file_put_contents('accept_debug.log', "Request ID length: " . strlen($request_id) . "\n", FILE_APPEND);

    if ($request_id && $request_id !== '') {
        try {
            // First, let's check what records exist
            $all_sql = "SELECT id, status FROM transport_requests LIMIT 10";
            $all_stmt = $pdo->prepare($all_sql);
            $all_stmt->execute();
            $all_records = $all_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            file_put_contents('accept_debug.log', "All records sample: " . print_r($all_records, true) . "\n", FILE_APPEND);
            
            // Check if the record exists with string comparison
            $check_sql = "SELECT id, status FROM transport_requests WHERE id = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$request_id]);
            $existing_record = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            file_put_contents('accept_debug.log', "String search result: " . print_r($existing_record, true) . "\n", FILE_APPEND);
            
            // Also try with integer conversion
            $check_int_sql = "SELECT id, status FROM transport_requests WHERE id = ?";
            $check_int_stmt = $pdo->prepare($check_int_sql);
            $check_int_stmt->execute([intval($request_id)]);
            $existing_record_int = $check_int_stmt->fetch(PDO::FETCH_ASSOC);
            
            file_put_contents('accept_debug.log', "Integer search result: " . print_r($existing_record_int, true) . "\n", FILE_APPEND);
            
            // Use whichever method found the record
            $record_found = $existing_record ?: $existing_record_int;
            $use_int_id = !$existing_record && $existing_record_int;
            
            if (!$record_found) {
                echo json_encode([
                    "success" => false, 
                    "message" => "Request not found with ID: $request_id",
                    "debug" => [
                        "request_id" => $request_id,
                        "string_search" => $existing_record,
                        "int_search" => $existing_record_int,
                        "all_ids_sample" => array_column($all_records, 'id')
                    ]
                ]);
                exit;
            }
            
            // Check if already accepted
            if ($record_found['status'] === 'accepted') {
                echo json_encode([
                    "success" => false, 
                    "message" => "Request has already been accepted",
                    "debug" => [
                        "request_id" => $request_id,
                        "current_status" => $record_found['status']
                    ]
                ]);
                exit;
            }
            
            // Now update the record using the correct data type
            $update_id = $use_int_id ? intval($request_id) : $request_id;
            $sql = "UPDATE transport_requests SET status = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            // Debugging: Log the actual query being executed
            file_put_contents('accept_debug.log', "Query: $sql\nParams: ['$new_status', '$update_id'] (using " . ($use_int_id ? 'integer' : 'string') . " ID)\n", FILE_APPEND);
            
            $result = $stmt->execute([$new_status, $update_id]);
            
            // Debugging: Check affected rows
            $affected = $stmt->rowCount();
            file_put_contents('accept_debug.log', "Execute result: " . ($result ? 'true' : 'false') . "\n", FILE_APPEND);
            file_put_contents('accept_debug.log', "Affected rows: $affected\n", FILE_APPEND);
            
            if ($result && $affected > 0) {
                // Verify the update by selecting the record again
                $verify_stmt = $pdo->prepare("SELECT status FROM transport_requests WHERE id = ?");
                $verify_stmt->execute([$update_id]);
                $updated_record = $verify_stmt->fetch(PDO::FETCH_ASSOC);
                
                file_put_contents('accept_debug.log', "Verification - Updated record: " . print_r($updated_record, true) . "\n", FILE_APPEND);
                
                echo json_encode([
                    "success" => true, 
                    "message" => "Transport request accepted successfully!",
                    "debug" => [
                        "request_id" => $request_id,
                        "update_id_used" => $update_id,
                        "status_set" => $new_status,
                        "affected_rows" => $affected,
                        "verified_status" => $updated_record['status'] ?? 'verification_failed'
                    ]
                ]);
            } else {
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
            file_put_contents('accept_debug.log', "PDO Exception: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode([
                "success" => false, 
                "message" => "Database error: " . $e->getMessage(),
                "debug" => [
                    "error_code" => $e->getCode(),
                    "trace" => $e->getTraceAsString()
                ]
            ]);
        }
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Request ID is required and cannot be empty",
            "debug" => [
                "received_request_id" => $request_id,
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