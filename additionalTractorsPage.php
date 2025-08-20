<?php 
    session_start();

    $errors = $_SESSION['trucks_error'] ?? "";
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Tractor - FarmConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles/additionalTractorsPage.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-tractor"></i>
                Truck2carrier
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
    <main class="main-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-icon">
                <i class="fas fa-plus-circle"></i>
            </div>
            <h1 class="hero-title">Register Your Tractor</h1>
            <p class="hero-subtitle">
                Join thousands of farmers connecting with those in need. 
                Add your tractor details to start earning and helping your community.
            </p>
        </section>

        <!-- Success Message -->
        <div id="successMessage" class="success-message">
            <i class="fas fa-check-circle"></i>
            <div class="success-content">
                <h3>Tractor Registered Successfully!</h3>
                <p>Your tractor has been added to the platform and is ready to receive requests.</p>
            </div>
        </div>

        <!-- Error Message -->
        <!-- <div id="errorMessage" class="error-message">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Please fill in all required fields correctly.</span>
        </div> -->

        <!-- Registration Form -->
        <form id="tractorForm" class="registration-form" method="post" action="addTractors.php">
            <?php if (!empty($errors)): ?>
            <div class="error-message">
                <span class="error-icon">&#9888;</span>
                <div class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
            <div class="form-header">
                <h2>Tractor Information</h2>
                <p>Please provide accurate details about your tractor</p>
            </div>

            <!-- Basic Information -->
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="tractorType">
                        <i class="fas fa-tractor"></i>
                        Tractor Type *
                    </label>
                    <select id="tractorType" name="truck_type" class="form-select" required>
                        <option value="">Select Tractor Type</option>
                        <option value="compact">Compact Tractor (20-50 HP)</option>
                        <option value="utility">Utility Tractor (50-100 HP)</option>
                        <option value="row-crop">Row Crop Tractor (100-200 HP)</option>
                        <option value="high-power">High Power Tractor (200+ HP)</option>
                        <option value="specialty">Specialty Tractor</option>
                        <option value="vintage">Vintage/Classic Tractor</option>
                    </select>
                    <i class="fas fa-cog input-icon"></i>
                </div>

                <div class="form-group">
                    <label class="form-label" for="plateNumber">
                        <i class="fas fa-id-card"></i>
                        License Plate Number *
                    </label>
                    <input type="text" id="plateNumber" name="plate_number" class="form-input" 
                           placeholder="e.g., ABC-123-XYZ" required>
                    <i class="fas fa-hashtag input-icon"></i>
                </div>

                <div class="form-group">
                    <label class="form-label" for="manufacturer">
                        <i class="fas fa-industry"></i>
                        Manufacturer
                    </label>
                    <input type="text" id="manufacturer" name="manufacturer" class="form-input" 
                           placeholder="e.g., John Deere, Massey Ferguson">
                    <i class="fas fa-building input-icon"></i>
                </div>

                <div class="form-group">
                    <label class="form-label" for="model">
                        <i class="fas fa-tag"></i>
                        Model
                    </label>
                    <input type="text" id="model" name="model" class="form-input" 
                           placeholder="e.g., 5075E, MF 275">
                    <i class="fas fa-tags input-icon"></i>
                </div>

                <div class="form-group">
                    <label class="form-label" for="year">
                        <i class="fas fa-calendar-alt"></i>
                        Year of Manufacture
                    </label>
                    <input type="number" id="year" name="year" class="form-input" 
                           placeholder="e.g., 2020" min="1950" max="2024">
                    <i class="fas fa-calendar input-icon"></i>
                </div>

                <div class="form-group">
                    <label class="form-label" for="hourlyRate">
                        <i class="fas fa-dollar-sign"></i>
                        Hourly Rate (₦)
                    </label>
                    <input type="number" id="hourlyRate" name="hourly_rate" class="form-input" 
                           placeholder="e.g., 5000" min="0" step="100">
                    <i class="fas fa-money-bill input-icon"></i>
                </div>
            </div>

            <!-- Capacity Section -->
            <div class="capacity-section">
                <h3>
                    <i class="fas fa-weight-hanging"></i>
                    Tractor Capacity *
                </h3>
                <div class="capacity-grid">
                    <div class="capacity-item" onclick="selectCapacity('light')">
                        <input type="radio" name="capacity" value="light" class="capacity-radio" required>
                        <div class="capacity-icon">
                            <i class="fas fa-feather-alt"></i>
                        </div>
                        <h4>Light Duty</h4>
                        <p>Up to 2 acres<br>Ideal for small farms</p>
                    </div>

                    <div class="capacity-item" onclick="selectCapacity('medium')">
                        <input type="radio" name="capacity" value="medium" class="capacity-radio" required>
                        <div class="capacity-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h4>Medium Duty</h4>
                        <p>2-10 acres<br>Perfect for mid-size operations</p>
                    </div>

                    <div class="capacity-item" onclick="selectCapacity('heavy')">
                        <input type="radio" name="capacity" value="heavy" class="capacity-radio" required>
                        <div class="capacity-icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <h4>Heavy Duty</h4>
                        <p>10+ acres<br>Commercial operations</p>
                    </div>

                    <div class="capacity-item" onclick="selectCapacity('specialized')">
                        <input type="radio" name="capacity" value="specialized" class="capacity-radio" required>
                        <div class="capacity-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4>Specialized</h4>
                        <p>Custom equipment<br>Specific tasks only</p>
                    </div>
                </div>
            </div>

            

            <!-- Status Section -->
            <div class="status-section">
                <h3>
                    <i class="fas fa-traffic-light"></i>
                    Current Availability Status *
                </h3>
                <div class="status-options">
                    <div class="status-option available" onclick="selectStatus('available')">
                        <input type="radio" name="status" value="available" class="capacity-radio" required>
                        <div class="status-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4>Available</h4>
                        <p>Ready to accept new jobs</p>
                    </div>

                    <div class="status-option busy" onclick="selectStatus('busy')">
                        <input type="radio" name="status" value="busy" class="capacity-radio" required>
                        <div class="status-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Busy</h4>
                        <p>Currently working on projects</p>
                    </div>

                    <div class="status-option maintenance" onclick="selectStatus('maintenance')">
                        <input type="radio" name="status" value="maintenance" class="capacity-radio" required>
                        <div class="status-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h4>Maintenance</h4>
                        <p>Undergoing repairs or maintenance</p>
                    </div>
                </div>
            </div>

            

            <!-- Submit Button -->
            <div class="submit-section">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    Register Tractor
                </button>
            </div>
        </form>
    </main>

    <script src="js/additionalTractorsPage.js"></script>
    
</body>
</html>