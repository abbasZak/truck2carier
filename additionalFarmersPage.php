<?php 
    session_start();
    
    if ( !isset($_SESSION['farmer_id']) ) {
        header("location: index.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Farmer Profile - TractorConnect</title>
    <link rel="stylesheet" href="styles/additionalFarmersPage.css">
</head>
<body>
    <div class="container">
        <div class="floating-elements"></div>
        
        <div class="header">
            <div class="logo">🚜</div>
            <h1>Complete Your Farmer Profile</h1>
            <p class="subtitle">Help us connect you with the right tractors and equipment for your farming needs</p>
        </div>

        <form id="farmerForm" method="post" action="finalSignupFarmers.php">
            <div class="form-group has-icon">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                <span class="field-icon">👤</span>
            </div>

            <div class="form-row">
                <div class="form-group has-icon">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="+234 800 123 4567" required>
                    <span class="field-icon">📞</span>
                </div>
                
                <div class="form-group has-icon">
                    <label for="farm_location">Farm Location</label>
                    <input type="text" id="farm_location" name="farm_location" placeholder="City, State" required>
                    <span class="field-icon">📍</span>
                </div>
            </div>

            <div class="form-group has-icon">
                <label for="produce_type">Primary Produce Type</label>
                <select id="produce_type" name="produce_type" required>
                    <option value="">Select your primary produce</option>
                    <option value="grains">Grains (Rice, Wheat, Corn, etc.)</option>
                    <option value="vegetables">Vegetables</option>
                    <option value="fruits">Fruits</option>
                    <option value="legumes">Legumes (Beans, Peas, etc.)</option>
                    <option value="tubers">Tubers (Yam, Cassava, Potato, etc.)</option>
                    <option value="cash_crops">Cash Crops (Cocoa, Cotton, etc.)</option>
                    <option value="livestock">Livestock</option>
                    <option value="mixed_farming">Mixed Farming</option>
                    <option value="other">Other</option>
                </select>
                <span class="field-icon">🌾</span>
            </div>

            <div class="form-row">
                <div class="form-group has-icon">
                    <label for="farm_size">Farm Size (Hectares)</label>
                    <input type="number" id="farm_size" name="farm_size" placeholder="e.g., 5.5" step="0.1" min="0">
                    <span class="field-icon">📏</span>
                </div>
                
                <div class="form-group has-icon">
                    <label for="experience">Years of Experience</label>
                    <input type="number" id="experience" name="experience" placeholder="e.g., 10" min="0">
                    <span class="field-icon">⏳</span>
                </div>
            </div>

            <div class="form-group">
                <label for="equipment_needed">Equipment/Services Needed</label>
                <textarea id="equipment_needed" name="equipment_needed" placeholder="Describe the tractors, implements, or services you typically need (e.g., land preparation, planting, harvesting, transportation)"></textarea>
            </div>

            <div class="form-group">
                <label for="additional_info">Additional Information</label>
                <textarea id="additional_info" name="additional_info" placeholder="Any other information you'd like to share about your farming operation, preferred working hours, special requirements, etc."></textarea>
            </div>

            <button type="submit" class="submit-btn">
                <span class="icon">🚀</span>
                Complete Registration
            </button>
        </form>

        <div id="successMessage" class="success-message">
            <h3>🎉 Profile Complete!</h3>
            <p>Your farmer profile has been successfully created. You'll now be able to connect with tractor operators in your area!</p>
        </div>
    </div>

    
</body>
</html>