<?php
session_start();
include("connect.php");

$sql = "SELECT  
            tr.id AS request_id,
            tr.produce_type,
            tr.quantity,
            tr.pickup_location,
            tr.destination,
            tr.urgency_level,
            tr.additional_information,
            tr.status,
            tr.created_at,
            u.id AS farmer_id,     
            u.name AS farmer_name,
            u.phone AS phone_number
        FROM transport_requests tr
        JOIN users u ON tr.farmer_id = u.id
        ORDER BY tr.created_at DESC";

$stmt = $pdo->query($sql);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tracktorId = isset($_SESSION['tractor_id']) ? $_SESSION['tractor_id'] : null;

if ($tracktorId) {
    $sql2 = "SELECT * FROM users WHERE id=? LIMIT 1";
    $stmt = $pdo->prepare($sql2);
    $stmt->execute([$tracktorId]);
    $User = $stmt->fetch(PDO::FETCH_ASSOC);
}
$status_pending = "pending";

$sql3 = "SELECT COUNT(*) AS total_pending 
         FROM transport_requests 
         WHERE status = ?";

$stmt3 = $pdo->prepare($sql3);
$stmt3->execute([$status_pending]); // ✅ run query with value
$row_pending = $stmt3->fetch(PDO::FETCH_ASSOC);


$status_matched = "matched";

$sql4 = "SELECT COUNT(*) AS total_matched 
         FROM transport_requests 
         WHERE status = ?";

$stmt4 = $pdo->prepare($sql4);
$stmt4->execute([$status_matched]); // ✅ run query with value
$row_matched = $stmt4->fetch(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tractors Dashboard - Truck2Carrier</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/tractordashboard.css">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Success Message -->
    <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle"></i>
        <span id="successText">Action completed successfully!</span>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-truck"></i>
                Tractors Dashboard
            </div>
            <?php 
                if (isset($tracktorId) && $tracktorId) {
                    ?> 
                        <a href="#" id="profile-link">
                            <div class="user-info">
                            <div class="user-avatar">
                                M
                                <div class="notification-dot"></div>
                            </div>
                            <span><?= $User['name']; ?></span>
                            <a href="logout2.php" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>

                        </div>
                        </a>
                                
                    <?php
                } else {
                    ?> 
                        <div class="user-info">
                            <a href="Registration.php" class="create-account-btn">
                                <i class="fas fa-user-plus"></i>
                                Create Account
                            </a>
                            <a href="login.php" class="login-btn">
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </a>
                        </div>
                    <?php
                }
            ?>
        </div>
    </header>

    <?php if (isset($tracktorId) && $tracktorId): ?>
        <!-- Main Container - Only show when logged in -->
        <div class="container">
            <!-- Stats Sidebar -->
            <div class="stats-sidebar">
                <!-- Profile Card -->
                <div class="profile-card">
                    <div class="profile-avatar">M</div>
                    <div class="profile-name"><?= $User['name'] ?? "User"; ?></div>
                    <div class="profile-role">Truck Owner & Operator</div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Pending Requests</h3>
                        </div>
                    </div>
                    <div class="stat-value"><?= $row_pending['total_pending'] ?></div>
                </div>

                <div class="stats-card">
                    <div class="stat-header">
                        <div class="stat-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Active Trips</h3>
                        </div>
                    </div>
                    <div class="stat-value"><?= $row_matched['total_matched']; ?></div>
                </div>

                
            </div>

            <!-- Requests List -->
            <div class="requests-container">
                <div class="requests-header">
                    <h2>Available Transport Requests</h2>
                    <div class="filter-tabs">
                        <button class="filter-tab active" onclick="filterRequests('all')">All</button>
                        <button class="filter-tab" onclick="filterRequests('high')">Urgent</button>
                        <button class="filter-tab" onclick="filterRequests('nearby')">Nearby</button>
                        <button class="filter-tab" onclick="filterRequests('high-value')">High Value</button>
                    </div>
                </div>

                <div class="search-filter">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search by produce type, location, or farmer name...">
                    </div>
                    <select class="location-filter">
                        <option value="">All Locations</option>
                        <option value="Damaturu">Damaturu</option>
                        <option value="Potiskum">Potiskum</option>
                        <option value="Gashua">Gashua</option>
                        <option value="Nguru">Nguru</option>
                        <option value="Geidam">Geidam</option>
                    </select>
                </div>

                <div id="requestsList">
                    <?php foreach ($requests as $request): ?>
                    <div class="request-card" data-status="<?= htmlspecialchars($request['urgency_level']) ?>" data-location="<?= htmlspecialchars($request['pickup_location']) ?>">
                        <div class="request-header">
                            <div>
                                <div class="request-title"><?= htmlspecialchars($request['produce_type']) ?> Transport Request</div>
                                <div class="farmer-info">
                                    <div class="farmer-avatar"><?= substr($request['farmer_name'], 0, 1) ?></div>
                                    <span><?= htmlspecialchars($request['farmer_name']) ?></span>
                                </div>
                                <div class="request-id">REQ-<?= str_pad($request['request_id'], 3, '0', STR_PAD_LEFT) ?></div>
                            </div>
                            <div class="urgency-badge urgency-<?= strtolower($request['urgency_level']) ?>">
                                <?= ucfirst($request['urgency_level']) ?> Priority
                            </div>
                        </div>

                        <div class="request-details">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="fas fa-seedling"></i></div>
                                <div class="detail-content">
                                    <h4>Produce</h4>
                                    <p><?= htmlspecialchars($request['produce_type']) ?> - <?= htmlspecialchars($request['quantity']) ?></p>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="detail-content">
                                    <h4>Pickup</h4>
                                    <p><?= htmlspecialchars($request['pickup_location']) ?></p>
                                </div>
                            </div>

                            <?php if($request['status'] === "matched"): ?>
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-phone"></i></div>
                                    <div class="detail-content">
                                        <h4>Contact</h4>
                                        <p><?= htmlspecialchars($request['phone_number']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="detail-item">
                                <div class="detail-icon"><i class="fas fa-flag-checkered"></i></div>
                                <div class="detail-content">
                                    <h4>Destination</h4>
                                    <p><?= htmlspecialchars($request['destination']) ?></p>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon"><i class="fas fa-clock"></i></div>
                                <div class="detail-content">
                                    <h4>Posted</h4>
                                    <p><?= date("F j, Y, g:i a", strtotime($request['created_at'])) ?></p>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon"><i class="fas fa-info-circle"></i></div>
                                <div class="detail-content">
                                    <h4>Notes</h4>
                                    <p><?= htmlspecialchars($request['additional_information']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="request-actions">
                            <?php if ($request['status'] == 'pending'): ?>
                                <button class="action-btn btn-details" onclick="showModal('Request Details')">
                                    <i class="fas fa-info-circle"></i> Details
                                </button>
                                <button class="action-btn btn-accept" onclick="acceptRequest(
                                    <?= $request['request_id']; ?>, 
                                    '<?= htmlspecialchars($request['produce_type'], ENT_QUOTES); ?>', 
                                    '<?= htmlspecialchars($request['quantity'], ENT_QUOTES); ?>', 
                                    '<?= htmlspecialchars($request['pickup_location'], ENT_QUOTES); ?>', 
                                    '<?= htmlspecialchars($request['destination'], ENT_QUOTES); ?>', 
                                    '<?= htmlspecialchars($request['urgency_level'], ENT_QUOTES); ?>', 
                                    '<?= htmlspecialchars($request['farmer_name'], ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($request['farmer_id'], ENT_QUOTES); ?>'
                                )">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            <?php elseif ($request['status'] == 'matched'): ?>
                                <div class="status-badge matched">
                                    <i class="fas fa-link"></i> Matched with You
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Not Logged In Content -->
        <div class="not-logged-in-container">
            <div class="auth-card">
                <div class="auth-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h2 class="auth-title">Welcome to Truck2Carrier</h2>
                <p class="auth-subtitle">Connect farmers with reliable transport solutions</p>
                
                <div class="auth-features">
                    <div class="feature-item">
                        <i class="fas fa-handshake"></i>
                        <span>Connect with farmers needing transport</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Earn money with your truck</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-route"></i>
                        <span>Manage your trips efficiently</span>
                    </div>
                </div>

                <div class="auth-message">
                    <h3>Join Our Transport Network</h3>
                    <p>Create an account or log in to start viewing and accepting transport requests from farmers in your area.</p>
                </div>

                <div class="auth-actions">
                    <a href="Registration.php" class="auth-btn primary">
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </a>
                    <a href="Registration.php" class="auth-btn secondary">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal -->
    <div class="modal" id="detailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Request Details</h3>
                <button type="button" class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="form-group">
                <label for="requestDetails">Details</label>
                <textarea id="requestDetails" class="form-textarea" readonly>Here will be the detailed information about the selected request.</textarea>
            </div>
        </div>
    </div>

    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-modal">
            <div class="confirm-header">
                <div class="confirm-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="confirm-title">Accept Request?</div>
                <div class="confirm-subtitle">Review the details before confirming</div>
            </div>
            
            <div class="confirm-body">
                <div class="request-preview" id="requestPreview">
                    <!-- Request details will be populated here -->
                </div>
                
                <div class="confirm-message">
                    Are you sure you want to accept this transport request? Once confirmed, you'll be committed to completing this job.
                </div>
                
                <div class="confirm-actions">
                    <button class="confirm-btn btn-cancel" onclick="hideConfirmAccept()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="confirm-btn btn-confirm" id="confirmAcceptBtn" onclick="processAcceptRequest()">
                        <i class="fas fa-check"></i> Accept Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/tractorsdashboard.js"></script>
    
    <script src="accept_request.js"></script>
</body>
</html>