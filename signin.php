<?php 
session_start();
include("connect.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $phoneNumber = htmlspecialchars(trim($_POST['tel']), ENT_QUOTES, 'UTF-8');
    $passwordEl  = htmlspecialchars(trim($_POST['password']), ENT_QUOTES, 'UTF-8');
    
    function isPhoneNumberValid($phone) {
        return preg_match('/^(?:\+234|234|0)(7\d|8\d|9\d)\d{8}$/', $phone);
    }

    function accountExists($pdo, $phone) {
        $sql = "SELECT COUNT(*) FROM users WHERE phone = :phone";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetchColumn() > 0;
    }

    // Validate phone format
    if (!isPhoneNumberValid($phoneNumber)) {
        $errors[] = "Phone number is not valid";
    }

    // Check if account exists
    if (empty($errors) && !accountExists($pdo, $phoneNumber)) {
        $errors[] = "Account does not exist. Please sign up.";
    }

    // Process login if no errors so far
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = :phone LIMIT 1");
        $stmt->execute([":phone" => $phoneNumber]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($passwordEl, $user['password'])) {
                if ($user['role'] === "farmer") {
                    $_SESSION['farmer_id'] = $user['id'];
                    header("Location: farmerspage.php");
                } else {
                    $_SESSION['tractor_id'] = $user['id'];
                    header("Location: tractorsdashboard.php");
                }
                exit();
            } else {
                $errors[] = "Password is incorrect";
            }
        } else {
            $errors[] = "Account does not exist. Please sign up.";
        }
    }

    // Store errors and redirect back
    if (!empty($errors)) {
        $_SESSION['login_error'] = $errors;
        header("Location: Registration.php");
        exit();
    }
}
?>
