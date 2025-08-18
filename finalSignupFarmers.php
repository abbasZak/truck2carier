<?php 
    session_start();
    include("connect.php");
    $farmersid = $_SESSION['farmer_id'];
    

    if ( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $farmLocation = $_POST['farm_location'];
        $produce_type = $_POST['produce_type'];
        $farm_size = $_POST['farm_size'];
        $experience = $_POST['experience'];
        $equipment_needed = $_POST['equipment_needed'];
        $additional_info = $_POST['additional_info'];

        $error = "";

        if ( !isset($farmersid) ) {
            header("location: index.php");
        } else {
            try {
                $sql = "INSERT INTO farmers (`user_id`, `name`, `farm_location`, `produce_type`, `years_of_experiece`, `phone`, `farm_size`, `equipment_needed`, `additional_information`) VALUES (?,?,?,?,?,?,?,?,?)";
                $stmt = $pdo->prepare($sql);
                $execute = $stmt->execute([$farmersid, $name, $farmLocation, $produce_type, $experience, $phone, $farm_size, $equipment_needed, $additional_info]);
                if ( $execute ) {
                    header("Location: farmerspage.php");
                } 

                
                
            
            } catch(PDOException $e) {
                $error = "Error:" .  $e->getMessage();
                header("Location: additionalFarmersPage.php");
            }

            
            
        }
    }
?>