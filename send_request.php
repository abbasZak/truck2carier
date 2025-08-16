<?php
header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

session_start();
include("connect.php");

$data = json_decode(file_get_contents("php://input"), true);

$produce     = $data['produce'] ?? '';
$quantity    = $data['quantity'] ?? '';
$unit        = $data['unit'] ?? '';
$pickup      = $data['pickup'] ?? '';
$destination = $data['destination'] ?? '';
$urgency     = $data['urgency'] ?? '';
$additional  = $data['additional'] ?? '';
$status      = "Pending";

$farmersid = $_SESSION['farmer_id'] ?? null;

if (empty($produce) || empty($quantity) || empty($pickup) || empty($destination) || empty($urgency)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields"]);
    exit;
}

if (!$farmersid) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

try {
    $sql = "INSERT INTO transport_requests 
        (farmer_id, produce_type, quantity, pickup_location, destination, urgency_level, additional_information, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$farmersid, $produce, $quantity, $pickup, $destination, $urgency, $additional, $status]);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Request has been set"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed"]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage() // TEMP for debugging
    ]);
}

?>