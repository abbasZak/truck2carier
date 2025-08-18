<?php
session_start();
include("connect.php");

$errors = [];

function accountExists($pdo, $userId) {
    $sql = "SELECT COUNT(*) FROM farmers WHERE user_id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Validate inputs
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $farmLocation = filter_input(INPUT_POST, 'farm_location', FILTER_SANITIZE_STRING);
    $produce_type = filter_input(INPUT_POST, 'produce_type', FILTER_SANITIZE_STRING);
    $farm_size = filter_input(INPUT_POST, 'farm_size', FILTER_SANITIZE_STRING);
    $experience = filter_input(INPUT_POST, 'experience', FILTER_SANITIZE_STRING);
    $equipment_needed = filter_input(INPUT_POST, 'equipment_needed', FILTER_SANITIZE_STRING);
    $additional_info = filter_input(INPUT_POST, 'additional_info', FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($name)) $errors[] = "Name is required";
    if (empty($phone)) $errors[] = "Phone is required";
    // Add more validations as needed

    if (accountExists($pdo, $_SESSION['farmer_id'])) {
        $errors[] = "Account already exists";
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO farmers (`user_id`, `name`, `farm_location`, `produce_type`, `years_of_experiece`, `phone`, `farm_size`, `equipment_needed`, `additional_information`) 
                    VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $execute = $stmt->execute([
                $_SESSION['farmer_id'], 
                $name, 
                $farmLocation, 
                $produce_type, 
                $experience, 
                $phone, 
                $farm_size, 
                $equipment_needed, 
                $additional_info
            ]);
            
            if ($execute) {
                header("Location: farmerspage.php");
                exit();
            }
        } catch(PDOException $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
    
    // Store errors in session and redirect back
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST; // Optional: to repopulate form
    header("Location: additionalFarmersPage.php");
    exit();
}
?>