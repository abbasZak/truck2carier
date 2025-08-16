<?php 
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $fullName    = htmlspecialchars(trim($_POST['signupName']), ENT_QUOTES, 'UTF-8');
    $phoneNumber = htmlspecialchars(trim($_POST['signupPhone']), ENT_QUOTES, 'UTF-8');
    $raw_password = htmlspecialchars(trim($_POST['signupPassword']), ENT_QUOTES, 'UTF-8');
    $role        = trim($_POST['role']);
    $location    = htmlspecialchars(trim($_POST['location']), ENT_QUOTES, 'UTF-8');
    $password_hash = password_hash($raw_password, PASSWORD_DEFAULT);
    $errors = [];

    function isPasswordStrong($password) {
        return preg_match('/^(?=.*[A-Z])(?=.*[\d\W]).+$/', $password);
    }

    function isPhoneNumberValid($phone) {
    // Nigerian phone number pattern
        return preg_match('/^(?:\+234|234|0)(7\d|8\d|9\d)\d{8}$/', $phone);
    }


    function accountExists($pdo, $phone) {
        $sql = "SELECT COUNT(*) FROM users WHERE phone = :phone";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetchColumn() > 0;
    }

    function getAccountValue($pdo, $columnToCheck, $valueToCheck, $columnToReturn) {
        $sql = "SELECT $columnToReturn FROM users WHERE $columnToCheck = :value LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':value' => $valueToCheck]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result[$columnToReturn] : false; 
    }

    if (!isPasswordStrong($raw_password)) {
        $errors[] = "Password should contain a capital letter, number or symbol.";
    }

    if (strlen($raw_password) < 6) {
        $errors[] = "Password should be more than 6 characters.";
    }

    if (!isPhoneNumberValid($phoneNumber)) {
        $errors[] = "Phone number should be a valid Nigerian number.";
    }

    if (accountExists($pdo, $phoneNumber)) {
        $errors[] = "Account already exists.";
    }

    if (!in_array($role, ['farmer', 'tractor'])) {
        $errors[] = "Choose farmer or tractor owner.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: Registration.php");
        exit();
    } else {
        $sql = "INSERT INTO users (name, phone, password, role, location) 
                VALUES(:name, :phone, :password, :role, :location)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ":name" => $fullName,
            ":phone" => $phoneNumber,
            ":password" => $password_hash,
            ":role" => $role,
            ":location" => $location
        ]);

        if ($result) {
            if ($role == "farmer") {
                $_SESSION['farmer_id'] = getAccountValue($pdo, 'phone', $phoneNumber, 'id');
                header("Location: farmerspage.php");
            } else {
                $_SESSION['tractor_id'] = getAccountValue($pdo, 'phone', $phoneNumber, 'id');
                header("Location: tractorsdashboard.php");
            }
        }
    }
}
?>
