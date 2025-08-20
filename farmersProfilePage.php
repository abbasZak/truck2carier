<?php 
    session_start();
    include("connect.php");

    $farmersid = $_SESSION['farmer_id'];
    

    if ( !isset($farmersid)) {
        header("location: index.php");
    }

    $sql = "SELECT * FROM farmers WHERE user_id=? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$farmersid]);
    $farmer = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql2 = "SELECT location FROM users WHERE id = ? LIMIT 1";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$farmersid]);  // bind your variable here
    $user = $stmt2->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile - TractorConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles/farmersProfilePage.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-tractor"></i>
                Truck2carier
            </div>
            <div class="nav-actions">
                <a href="farmerspage.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-main">
                <div class="profile-avatar" id="profileAvatar">
                    JD
                </div>
                <div class="profile-info">
                    <h1 id="farmerName"><?= $farmer['name'] ?></h1>
                    <p class="profile-subtitle" id="farmerLocation"><?= $user['location'] ?></p>
                    <div class="profile-badges">
                        <div class="badge badge-primary">
                            <i class="fas fa-seedling"></i>
                            <span id="produceType"><?= $farmer['produce_type'] ?> Farmer</span>
                        </div>
                        <div class="badge badge-secondary">
                            <i class="fas fa-calendar-alt"></i>
                            <span id="experienceYears"><?= $farmer['years_of_experiece']; ?> years of fexperiece</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <button class="action-btn btn-edit" onclick="editProfile()">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </button>
                <button class="action-btn btn-delete" onclick="showDeleteModal()">
                    <i class="fas fa-trash-alt"></i>
                    Delete Profile
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-value" id="farmSizeStat"><?= $farmer['farm_size'] ?></div>
                <div class="stat-label">Hectares</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-value">
                    <i class="fas fa-check-circle" style="font-size: 1rem; color: #4CAF50;"></i>
                </div>
                <div class="stat-label">Contact Verified</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tractor"></i>
                </div>
                <div class="stat-value"><?= $farmer['equipment_needed'] ?></div>
                <div class="stat-label">Equipment Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">8</div>
                <div class="stat-label">Completed Jobs</div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="profile-details">
            <!-- Contact Information -->
            <div class="detail-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <h3 class="card-title">Contact Information</h3>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value large" id="phoneNumber"><?= $farmer['phone']; ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Farm Location</div>
                    <div class="detail-value" id="farmLocation"><?= $farmer['farm_location']; ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Member Since</div>
                    <div class="detail-value" id="memberSince"><?= $farmer['created_at']; ?></div>
                </div>
            </div>

            <!-- Farm Information -->
            <div class="detail-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="card-title">Farm Details</h3>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Primary Produce</div>
                    <div class="detail-value" id="primaryProduce">Grains (Rice, Wheat, Corn)</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Farm Size</div>
                    <div class="detail-value" id="farmSize">25.5 Hectares</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Years of Experience</div>
                    <div class="detail-value" id="yearsExperience">10 Years</div>
                </div>
            </div>

            

            <!-- Additional Information -->
            <div class="detail-card" style="grid-column: span 2;">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="card-title">Additional Information</h3>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Notes</div>
                    <div class="detail-value" id="additionalInfo">
                        <?= $farmer['additional_information']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="modal-title">Delete Profile?</h3>
            <p class="modal-text">
                Are you sure you want to delete this farmer profile? This action cannot be undone and all associated data will be permanently removed.
            </p>
            <div class="modal-actions">
                <button class="modal-btn btn-confirm" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt"></i>
                    Delete Profile
                </button>
                <button class="modal-btn btn-cancel" onclick="hideDeleteModal()">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Message Box -->
    <div id="messageBox" class="message-box hidden">
        <i id="messageIcon" class="fas"></i>
        <span id="messageText"></span>
    </div>

</body>
</html>