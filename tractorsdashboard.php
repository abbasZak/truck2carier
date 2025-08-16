<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tractors Dashboard - Truck2Carrier</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1976D2 0%, #2196F3 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo i {
            margin-right: 0.5rem;
            color: #FFC107;
            animation: drive 3s infinite;
        }

        @keyframes drive {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #FFC107, #FF8F00);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            position: relative;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
        }

        /* Stats Sidebar */
        .stats-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            animation: slideInLeft 0.8s ease;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2196F3, #1976D2);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .stat-info h3 {
            color: #333;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.2rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #2196F3;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
            position: sticky;
            top: 120px;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2196F3, #1976D2);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }

        .profile-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .profile-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #f0f0f0;
        }

        .profile-stat {
            text-align: center;
        }

        .profile-stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2196F3;
        }

        .profile-stat-label {
            font-size: 0.8rem;
            color: #666;
        }

        /* Requests List */
        .requests-container {
            animation: slideInRight 0.8s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .requests-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .requests-header h2 {
            color: #2196F3;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .filter-tabs {
            display: flex;
            background: white;
            border-radius: 25px;
            padding: 0.25rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border: none;
            background: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.85rem;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .search-input:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
        }

        .search-wrapper {
            position: relative;
            flex: 1;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .location-filter {
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .location-filter:focus {
            outline: none;
            border-color: #2196F3;
        }

        .request-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            border-left: 5px solid #2196F3;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .request-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2196F3;
            margin-bottom: 0.5rem;
        }

        .request-id {
            font-size: 0.8rem;
            color: #888;
            background: #f0f0f0;
            padding: 0.2rem 0.8rem;
            border-radius: 15px;
        }

        .farmer-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .farmer-avatar {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .urgency-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .urgency-low {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
        }

        .urgency-medium {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .urgency-high {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .urgency-emergency {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
            animation: pulse 2s infinite;
        }

        .request-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .detail-item:hover {
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .detail-content h4 {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-content p {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .price-estimate {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
            padding: 1rem;
            border-radius: 15px;
            text-align: center;
            margin: 1rem 0;
        }

        .price-estimate h4 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .price-estimate .amount {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .request-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-accept {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
        }

        .btn-negotiate {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .btn-details {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .btn-contact {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e0e0e0;
            border-radius: 50%;
            border-top-color: #2196F3;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2196F3;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: #f0f0f0;
            color: #333;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
        }

        /* Notification Dot */
        .notification-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 15px;
            height: 15px;
            background: #f44336;
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Success Message */
        .success-message {
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            color: white;
            padding: 1rem 2rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.5s ease;
            position: fixed;
            top: 100px;
            right: 2rem;
            z-index: 9999;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-sidebar {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }

            .profile-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .nav-container {
                padding: 0 1rem;
            }

            .user-info span {
                display: none;
            }

            .request-details {
                grid-template-columns: 1fr;
            }

            .request-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }

            .filter-tabs {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .search-filter {
                flex-direction: column;
            }

            .requests-header {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-sidebar {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
            <div class="user-info">
                <div class="user-avatar">
                    M
                    <div class="notification-dot"></div>
                </div>
                <span>Musa Ibrahim</span>
                <a href="#" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Stats Sidebar -->
        <div class="stats-sidebar">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-avatar">M</div>
                <div class="profile-name">Musa Ibrahim</div>
                <div class="profile-role">Truck Owner & Operator</div>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <div class="profile-stat-value">4.8</div>
                        <div class="profile-stat-label">Rating</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-value">127</div>
                        <div class="profile-stat-label">Trips</div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-value">2</div>
                        <div class="profile-stat-label">Years</div>
                    </div>
                </div>
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
                <div class="stat-value">8</div>
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
                <div class="stat-value">2</div>
            </div>

            <div class="stats-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>This Month</h3>
                    </div>
                </div>
                <div class="stat-value">₦125k</div>
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
                <!-- Sample Request Cards -->
                <div class="request-card" data-status="high" data-location="Damaturu">
                    <div class="request-header">
                        <div>
                            <div class="request-title">Rice Transport Request</div>
                            <div class="farmer-info">
                                <div class="farmer-avatar">A</div>
                                <span>Ahmad Musa</span>
                            </div>
                            <div class="request-id">REQ-001</div>
                        </div>
                        <div class="urgency-badge urgency-high">High Priority</div>
                    </div>

                    <div class="request-details">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Produce</h4>
                                <p>Rice - 5 Tons</p>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Pickup</h4>
                                <p>Damaturu (15km away)</p>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Destination</h4>
                                <p>Kano Market</p>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-road"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Distance</h4>
                                <p>~450km</p>
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

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Notes</h4>
                                <p>Fragile load, handle with care</p>
                            </div>
                        </div>
                    </div>
                    <div class="price-estimate">
                        <h4>Estimated Earnings</h4>
                        <div class="amount">₦75,000 - ₦90,000</div>
                    </div>
                    <div class="request-actions">
                        <button class="action-btn btn-details" onclick="showModal('Request Details')">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                        <button class="action-btn btn-negotiate" onclick="showSuccessMessage('Negotiation started')">
                            <i class="fas fa-handshake"></i> Negotiate
                        </button>
                        <button class="action-btn btn-accept" onclick="showSuccessMessage('Request accepted')">
                            <i class="fas fa-check"></i> Accept
                        </button>
                    </div>
                </div>
                <!-- Additional Request Cards -->
                <div class="request-card" data-status="medium" data-location="Potiskum">
                    <div class="request-header">
                        <div>
                            <div class="request-title">Maize Transport Request</div>
                            <div class="farmer-info">
                                <div class="farmer-avatar">B</div>
                                <span>Bello Ali</span>
                            </div>
                            <div class="request-id">REQ-002</div>
                        </div>
                        <div class="urgency-badge urgency-medium">Medium Priority</div>
                    </div>
                    <div class="request-details">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Produce</h4>
                                <p>Maize - 10 Tons</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Pickup</h4>
                                <p>Potiskum (30km away)</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Destination</h4>
                                <p>Maiduguri</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-road"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Distance</h4>
                                <p>~380km</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Posted</h4>
                                <p>5 hours ago</p>
                            </div>
                        </div>
                    </div>
                    <div class="price-estimate">
                        <h4>Estimated Earnings</h4>
                        <div class="amount">₦65,000 - ₦80,000</div>
                    </div>
                    <div class="request-actions">
                        <button class="action-btn btn-details" onclick="showModal('Request Details')">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                        <button class="action-btn btn-negotiate" onclick="showSuccessMessage('Negotiation started')">
                            <i class="fas fa-handshake"></i> Negotiate
                        </button>
                        <button class="action-btn btn-accept" onclick="showSuccessMessage('Request accepted')">
                            <i class="fas fa-check"></i> Accept
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        // Filter Requests
        function filterRequests(status) {
            const filterTabs = document.querySelectorAll('.filter-tab');
            filterTabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            const requestCards = document.querySelectorAll('.request-card');
            requestCards.forEach(card => {
                if (status === 'all') {
                    card.style.display = 'block';
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    const cardLocation = card.getAttribute('data-location');
                    const locationFilter = document.querySelector('.location-filter').value;

                    let shouldShow = cardStatus === status;

                    if (status === 'nearby') {
                        shouldShow = cardLocation === locationFilter || locationFilter === '';
                    }

                    card.style.display = shouldShow ? 'block' : 'none';
                }
            });
        }

        // Show Modal
        function showModal(title) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('detailsModal').style.display = 'flex';
        }

        // Close Modal
        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        // Show Success Message
        function showSuccessMessage(message) {
            document.getElementById('successText').textContent = message;
            const successMessage = document.getElementById('successMessage');
            successMessage.style.display = 'flex';

            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners or other initialization code here
        });
    </script>
</body>
</html>