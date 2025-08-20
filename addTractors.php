<?php 
session_start();
include("connect.php");

// Initialize variables and error array
$Tractor_type = $plate_number = $Manufacturer = $model = $year_of_manufacture = $hourly_rate = $capacity = $status = "";
$errors = [];

// Check if user is logged in
if (!isset($_SESSION['tractor_id'])) {
    $errors[] = "You must be logged in to register a tractor";
    header("Location: index.php");
    
}

$tractor_id = $_SESSION['tractor_id'];
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Validate and sanitize inputs
    $Tractor_type = trim($_POST['truck_type']);
    $plate_number = trim($_POST['plate_number']);
    $Manufacturer = trim($_POST['manufacturer']); // Fixed the extra quote
    $model = trim($_POST['model']);
    $year_of_manufacture = trim($_POST['year']);
    $hourly_rate = trim($_POST['hourly_rate']);
    $capacity = trim($_POST['capacity']);
    $status = trim($_POST['status']);
    
    // Validation
    if (empty($Tractor_type)) {
        $errors[] = "Tractor type is required";
    }
    
    if (empty($plate_number)) {
        $errors[] = "License plate number is required";
    } elseif (!preg_match("/^[A-Z]{3}[0-9]{3,4}$/", $plate_number)) {
        $errors[] = "Invalid license plate format. Expected format: ABC123 or ABC1234";
    }
    
    if (empty($Manufacturer)) {
        $errors[] = "Manufacturer is required";
    }
    
    if (empty($hourly_rate) || !is_numeric($hourly_rate) || $hourly_rate <= 0) {
        $errors[] = "Valid hourly rate is required";
    }
    
    if (empty($capacity)) {
        $errors[] = "Capacity selection is required";
    }
    
    if (empty($status)) {
        $errors[] = "Status selection is required";
    }
    
    // Check if plate number already exists in database
    try {
        $checkPlate = $pdo->prepare("SELECT COUNT(*) FROM trucks WHERE plate_number = ?");
        $checkPlate->execute([$plate_number]);
        if ($checkPlate->fetchColumn() > 0) {
            $errors[] = "This license plate number is already registered";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO trucks (owner_id, truck_type, capacity, plate_number, Manufacturer, Model, years_of_manufacture, hourly_rate, status, created_at) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $tractor_id, 
                $Tractor_type, 
                $capacity, 
                $plate_number, 
                $Manufacturer, 
                $model, 
                $year_of_manufacture, 
                $hourly_rate, 
                $status
            ]);
            
            if ($result) {
                
                header("Location: tractorsdashboard.php");
                exit();
            } else {
                $errors[] = "Failed to register tractor. Please try again.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['trucks_error'] = $errors;
        header("Location: additionalTractorsPage.php");
    }
}
?>