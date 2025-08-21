<?php 
    session_start();
    include("connect.php");

    $truckDriversId = isset($_SESSION['tractor_id']) ? $_SESSION['tractor_id'] : null;
    

    if ( !isset($truckDriversId)) {
        header("location: index.php");
    }

    $sql = "SELECT * FROM trucks WHERE owner_id=? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$truckDriversId]);
    $truck_driver = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql2 = "SELECT *  FROM users WHERE id = ? LIMIT 1";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$truckDriversId]);  // bind your variable here
    $user = $stmt2->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile - TractorConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles/tractorProfilePage.css">
    
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-tractor"></i>
                TractorConnect
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
               
                <div class="profile-info">
                    <h1 id="farmerName"><?= htmlspecialchars($user['name']) ?></h1>
                    <p class="profile-subtitle" id="farmerLocation"><?= htmlspecialchars($user['location']) ?></p>
                    <div class="profile-badges">
                        <div class="badge badge-primary">
                            <i class="fas fa-seedling"></i>
                            <span id="produceType"><?= htmlspecialchars($truck_driver['truck_type']) ?> Farmer</span>
                        </div>
                        <div class="badge badge-secondary">
                            <i class="fas fa-calendar-alt"></i>
                            <span id="experienceYears"><?= htmlspecialchars($truck_driver['years_of_manufacture']) ?> years of Manufacture</span>
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
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-value" id="farmSizeStat">₦<?= htmlspecialchars($truck_driver['hourly_rate']) ?> per hour</div>
                
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="stat-value">
                    <i class="fas fa-check-circle" style="font-size: 1rem; color: #4CAF50;"></i>
                </div>
                <div class="stat-label">Contact Verified</div>
                <div class="stat-value"><?= htmlspecialchars($user['phone']) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tractor"></i>
                </div>
                <div class="stat-value"><?= htmlspecialchars($truck_driver['capacity']) ?></div>
                <div class="stat-label">Capacity</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">8</div>
                <div class="stat-label">Completed Jobs</div>
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

    <script>
        // Show/hide delete modal
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Simulate profile deletion
        function confirmDelete() {
            showLoading();
            hideDeleteModal();
            
            // Simulate API call
            setTimeout(() => {
                hideLoading();
                showMessage('Profile deleted successfully', 'success');
                
                // Redirect after success
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);
            }, 1500);
        }

        // Show/hide loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Show message
        function showMessage(text, type) {
            const messageBox = document.getElementById('messageBox');
            const messageIcon = document.getElementById('messageIcon');
            const messageText = document.getElementById('messageText');
            
            messageBox.classList.remove('hidden', 'success', 'error');
            messageBox.classList.add(type);
            
            if (type === 'success') {
                messageIcon.classList.add('fa-check-circle');
            } else {
                messageIcon.classList.add('fa-exclamation-circle');
            }
            
            messageText.textContent = text;
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                messageBox.classList.add('hidden');
            }, 3000);
        }

        // Edit profile function
        function editProfile() {
            showMessage('Edit feature coming soon!', 'success');
        }

        // Close modal if clicked outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                hideDeleteModal();
            }
        });
    </script>
</body>
</html>