<?php 
    session_start();
    include("connect.php");
    $farmersid = isset($_SESSION['farmer_id']) ? $_SESSION['farmer_id'] : null;

    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$farmersid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql2 = "SELECT * FROM transport_requests WHERE farmer_id=?";
    $stmt = $pdo->prepare($sql2);
    $stmt->execute([$farmersid]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);


    
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmers Dashboard - Truck2Carrier</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/farmerspage.css">
    <style>
        
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-seedling"></i>
                Farmers Dashboard
            </div>
            <div class="user-info">
                

                <?php 
                    if(!isset($farmersid)) {

                        ?> 
                        <a class="create-account-btn" style="text-decoration: none;" href="Registration.php">Create account</a>
                        <?php
                    } else {
                        ?>
                            <a href="farmersProfilePage.php" class="user-link">
    <div class="user-avatar">
        A
        <div class="notification-dot"></div>
    </div>
    <span><?= $user['name']; ?></span>    
</a>

                            
                        
                            

                            <form action="logout.php" method="post">
                                <button type="submit" class="logout-btn" name="Logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>

                                    
                        <?php
                    }
                ?>
                
            </div>
        </div>
    </header>

    <div id="formMessage" class="message-box hidden">
    <i id="messageIcon" class="fas"></i>
    <span id="messageText"></span>
</div>


    <!-- Main Container -->
    <div class="container">
        <!-- Request Form -->
        <div class="request-form-container">
            <div class="form-header">
                <p>Find trucks to transport your produce across Yobe State</p>
                <h2>Post Transport Request</h2>
            </div>

            <!-- Success Message -->
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i>
                <span>Transport request posted successfully!</span>
            </div>

            <form id="requestForm">
                <!-- Message Box -->
            <div id="formMessage" class="message-box hidden">
                <i id="messageIcon" class="fas"></i>
                <span id="messageText"></span>
            </div>


                <div class="form-group">
                    <label for="produceType">Produce Type</label>
                    <div style="position: relative;">
                        <i class="fas fa-leaf input-icon"></i>
                        <select id="produceType" class="form-select" name="produce" required>
                            <option value="">Select your produce</option>
                            <option value="Rice">Rice</option>
                            <option value="Millet">Millet</option>
                            <option value="Sorghum">Sorghum</option>
                            <option value="Maize">Maize (Corn)</option>
                            <option value="Groundnuts">Groundnuts</option>
                            <option value="Beans">Beans</option>
                            <option value="Sesame">Sesame</option>
                            <option value="Cotton">Cotton</option>
                            <option value="Onions">Onions</option>
                            <option value="Tomatoes">Tomatoes</option>
                            <option value="Peppers">Peppers</option>
                            <option value="Livestock">Livestock</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <div class="quantity-input-group">
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn" onclick="adjustQuantity(-1)">−</button>
                            <div class="quantity-display" id="quantityDisplay">1</div>
                            <button type="button" class="quantity-btn" onclick="adjustQuantity(1)">+</button>
                        </div>
                    </div>
                    <div class="unit-selector">
                        <button type="button" class="unit-btn active" onclick="selectUnit('tons')">Tons</button>
                        <button type="button" class="unit-btn" onclick="selectUnit('bags')">Bags</button>
                        <button type="button" class="unit-btn" onclick="selectUnit('kg')">Kg</button>
                        <button type="button" class="unit-btn" onclick="selectUnit('loads')">Truck Loads</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pickupLocation">Pickup Location</label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <select id="pickupLocation" class="form-select" required>
                            <option value="">Select pickup location</option>
                            <option value="Damaturu">Damaturu</option>
                            <option value="Gashua">Gashua</option>
                            <option value="Nguru">Nguru</option>
                            <option value="Potiskum">Potiskum</option>
                            <option value="Geidam">Geidam</option>
                            <option value="Gujba">Gujba</option>
                            <option value="Gulani">Gulani</option>
                            <option value="Fika">Fika</option>
                            <option value="Nangere">Nangere</option>
                            <option value="Bade">Bade</option>
                            <option value="Jakusko">Jakusko</option>
                            <option value="Karasuwa">Karasuwa</option>
                            <option value="Machina">Machina</option>
                            <option value="Yusufari">Yusufari</option>
                            <option value="Other">Other Location</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="destination">Destination</label>
                    <div style="position: relative;">
                        <i class="fas fa-flag-checkered input-icon"></i>
                        <input type="text" id="destination" class="form-input" placeholder="Where should it be delivered?" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="urgency">Urgency Level</label>
                    <div style="position: relative;">
                        <i class="fas fa-clock input-icon"></i>
                        <select id="urgency" class="form-select" required>
                            <option value="">Select urgency</option>
                            <option value="Low">Low - Within a week</option>
                            <option value="Medium">Medium - Within 3 days</option>
                            <option value="High">High - Within 24 hours</option>
                            <option value="Emergency">Emergency - ASAP</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="additionalInfo">Additional Information</label>
                    <div style="position: relative;">
                        <i class="fas fa-info-circle input-icon" style="top: 20px;"></i>
                        <textarea id="additionalInfo" class="form-textarea" placeholder="Special handling requirements, preferred pickup time, contact details, etc."></textarea>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    Post Request
                </button>
            </form>
        </div>

        <!-- Requests List -->
        <div class="requests-container">
            <div class="requests-header">
                <h2>My Transport Requests</h2>
                <div class="filter-tabs">
                    <button class="filter-tab active" onclick="filterRequests('all')">All</button>
                    <button class="filter-tab" onclick="filterRequests('pending')">Pending</button>
                    <button class="filter-tab" onclick="filterRequests('matched')">In Transit</button>
                    <button class="filter-tab" onclick="filterRequests('completed')">Completed</button>
                </div>
            </div>

            <div id="requestsList">
                <!-- Sample Request Cards -->
                <?php if (isset($farmersid)): ?>
    <!-- User is logged in - show their requests -->
    <?php foreach($requests as $request): ?>
        <div class="request-card" data-status="<?= $request['status']; ?>">
            <div class="request-header">
                <div>
                    <div class="request-title"><?= $request['produce_type']; ?> Transport</div>
                    <div class="request-id">REQ-<?= $request['id']; ?></div>
                </div>
                <div class="status-badge status-pending"><?= $request['status']; ?></div>
            </div>
            
            <div class="request-details">
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Produce</h4>
                        <p><?= $request['additional_information']; ?></p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Pickup</h4>
                        <p><?= $request['pickup_location']; ?></p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Destination</h4>
                        <p><?= $request['destination']; ?></p>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-content">
                        <h4>Posted</h4>
                        <p>2 hours ago</p>
                    </div>
                </div>
            </div>
            
            <div class="request-actions">
                <?php if($request['status'] === 'matched'): ?>
                <button class="action-btn btn-delivered" id="deliveredBtn" data-requestId="<?= $request['id'] ?>" >
                    <i class="fas fa-check-circle"></i> 
                    Delivered
                </button>
                <?php endif; ?>

                <button class="action-btn btn-edit">
                    
                    
                <i class='fas fa-edit'></i> Edit
                </button>
                <button class="action-btn btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </button>

                

                
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Static example cards (you can remove these if you only want dynamic content) -->
    

    

<?php else: ?>
    <!-- User is not logged in - show signin required message -->
    <div class="signin-required">
        <div class="signin-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h2 class="signin-title">Sign In Required</h2>
        <p class="signin-description">
            Please sign in to your account to view your transport requests and track your shipments. 
            If you don't have an account yet, create one to get started with our services.
        </p>
        
        <div class="signin-actions">
            <a href="Registration.php" class="signin-btn btn-signin">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </a>
            <a href="Registration.php" class="signin-btn btn-signup">
                <i class="fas fa-user-plus"></i>
                Create Account
            </a>
        </div>

        <div class="features-list">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="feature-text">
                    <div class="feature-title">Track Your Requests</div>
                    <div class="feature-desc">Monitor your transport requests in real-time</div>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="feature-text">
                    <div class="feature-title">Request History</div>
                    <div class="feature-desc">Access all your past and current shipments</div>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="feature-text">
                    <div class="feature-title">Get Notifications</div>
                    <div class="feature-desc">Receive updates on your transport status</div>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-text">
                    <div class="feature-title">Secure Platform</div>
                    <div class="feature-desc">Your data and transactions are protected</div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
 
            </div>
        </div>
    </div>
    <script src="js/farmerspage.js"></script>
    <script src="send_request.js"></script>
    
    <script src="statusComplete.js"></script>
</body>
</html>