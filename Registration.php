<?php
    session_start();
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']); // Clear errors after showing

    $signinerror = $_SESSION['login_error'] ?? [];
    unset($_SESSION['login_error']); // Clear login error after showing

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck2Carrier - Join Our Community</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/registration.css">
</head>
<body>
    <!-- Floating Background Icons -->
    <div class="floating-icons">
        <i class="fas fa-truck floating-icon"></i>
        <i class="fas fa-seedling floating-icon"></i>
        <i class="fas fa-map-marker-alt floating-icon"></i>
        <i class="fas fa-handshake floating-icon"></i>
        <i class="fas fa-leaf floating-icon"></i>
        <i class="fas fa-route floating-icon"></i>
    </div>

    <!-- Back to Home -->
    <a href="index.php" class="back-home">
        <i class="fas fa-arrow-left"></i>
        Back to Home
    </a>

    <!-- Main Auth Container -->
    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-truck"></i>
                    Truck2Carrier
                </div>
                <p>Connecting Yobe's Agricultural Community</p>
            </div>
        </div>

        <?php 
            foreach ($errors as $err) {
                echo $err;
            }
            
        
        ?>
        <!-- Form Container -->
        <div class="form-container">
            <!-- Error Message -->
            <?php if (!empty($signinerror) && is_array($signinerror)): ?>
                <div class="error-message">
                    <span class="error-icon">&#9888;</span>
                    <div class="error-list">
                        <?php foreach ($signinerror as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>            

            <!-- Form Tabs -->
            <div class="form-tabs">
                <button class="tab-button active" onclick="switchTab('login')">Login</button>
                <button class="tab-button" onclick="switchTab('signup')">Sign Up</button>
                <div class="tab-slider" id="tabSlider"></div>
            </div>

            <!-- Login Form -->
            <form class="form active" id="loginForm" action="signin.php" method="post">
                
            
            
                <div class="form-group">
                    <label for="loginPhone">Phone Number</label>
                    <div style="position: relative;">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="tel" id="loginPhone" class="form-input" placeholder="08012345678" name="tel" required>
                        <i class="fas fa-check validation-icon" id="loginPhoneValid"></i>
                        <i class="fas fa-times validation-icon" id="loginPhoneInvalid"></i>
                    </div>
                    
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="loginPassword" class="form-input" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye-slash" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;" onclick="togglePassword('loginPassword', this)"></i>
                    </div>
                    
                </div>

                <button type="submit" class="submit-btn">
                    <span class="btn-text">Sign In</span>
                    <div class="loading">
                        <div class="loading-spinner"></div>
                        Signing in...
                    </div>
                </button>

                <div class="auth-links">
                    <a href="#forgot">Forgot your password?</a>
                </div>
            </form>

            <!-- Signup Form -->
            <!-- Signup Form -->
<form class="form" id="signupForm" method="post" action="signup.php">
    <!-- Error Message -->
                <?php if (!empty($errors) && is_array($errors)): ?>
                    <div class="error-message">
                        <span class="error-icon">&#9888;</span>
                        <div class="error-list">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
    

    <div class="form-group">
        <label for="signupName">Full Name</label>
        <div style="position: relative;">
            <i class="fas fa-user input-icon"></i>
            <input type="text" id="signupName" name="signupName" class="form-input" placeholder="Enter your full name" required>
            <i class="fas fa-check validation-icon" id="signupNameValid"></i>
            <i class="fas fa-times validation-icon" id="signupNameInvalid"></i>
        </div>
        
    </div>

    <div class="form-group">
        <?php if (!empty($errors)): ?>
            <div style="color:red;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

        <label for="signupPhone">Phone Number</label>
        <div style="position: relative;">
            <i class="fas fa-phone input-icon"></i>
            <input type="tel" id="signupPhone" name="signupPhone" class="form-input" placeholder="08012345678" required>
            <i class="fas fa-check validation-icon" id="signupPhoneValid"></i>
            <i class="fas fa-times validation-icon" id="signupPhoneInvalid"></i>
        </div>
        
    </div>

    <div class="form-group">
        <label for="signupPassword">Password</label>
        <div style="position: relative;">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" id="signupPassword" name="signupPassword" class="form-input" placeholder="Create a strong password" required>
            <i class="fas fa-eye-slash" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;" onclick="togglePassword('signupPassword', this)"></i>
        </div>
        <div class="password-strength">
            <div class="password-strength-bar" id="strengthBar"></div>
        </div>
        
    </div>

    <div class="form-group">
        <label>Choose your role:</label>
        <div class="role-selector">
            <div class="role-option">
                <input type="radio" id="farmer" name="role" value="farmer" required>
                <label for="farmer" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="role-title">Farmer</div>
                    <div class="role-desc">I need transport for my crops</div>
                </label>
            </div>
            <div class="role-option">
                <input type="radio" id="truck_owner" name="role" value="truck_owner" required>
                <label for="truck_owner" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="role-title">Truck Owner</div>
                    <div class="role-desc">I provide transport services</div>
                </label>
            </div>
        </div>
        
    </div>

    <div class="form-group">
        <label for="location">Location in Yobe State</label>
        <div style="position: relative;">
            <i class="fas fa-map-marker-alt input-icon"></i>
            <select id="location" name="location" class="form-input" required>
                <option value="">Select your location</option>
                <option value="Damaturu">Damaturu (Capital)</option>
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
                <option value="Other">Other</option>
            </select>
        </div>
        
    </div>

    <button type="submit" class="submit-btn">
        <span class="btn-text">Create Account</span>
        <div class="loading">
            <div class="loading-spinner"></div>
            Creating account...
        </div>
    </button>

    <div class="auth-links">
        <small>By signing up, you agree to our <a href="#terms">Terms of Service</a> and <a href="#privacy">Privacy Policy</a></small>
    </div>
</form>
        </div>
    </div>

    <script src="js/registration.js"></script>
</body>
</html>