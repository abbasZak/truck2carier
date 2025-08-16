<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truck2Carrier - Connecting Drivers & Farmers in Yobe State</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .logo i {
            margin-right: 0.5rem;
            color: #FFC107;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .cta-button {
            background: linear-gradient(45deg, #FF6B35, #F7931E);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255,107,53,0.3);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.9), rgba(76, 175, 80, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23e8f5e8" width="1200" height="600"/><path fill="%234CAF50" opacity="0.1" d="M0,300 Q300,100 600,300 T1200,300 V600 H0 Z"/><circle fill="%23FFC107" opacity="0.6" cx="1000" cy="100" r="80"/><rect fill="%23FF6B35" opacity="0.3" x="100" y="400" width="60" height="100" rx="5"/><rect fill="%23FF6B35" opacity="0.3" x="200" y="450" width="40" height="50" rx="3"/></svg>');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.1), rgba(76, 175, 80, 0.1));
            animation: shimmer 3s ease-in-out infinite alternate;
        }

        @keyframes shimmer {
            0% { opacity: 0.1; }
            100% { opacity: 0.3; }
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            animation: fadeInUp 1s ease 0.3s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.6s both;
        }

        .btn-primary {
            background: linear-gradient(45deg, #FF6B35, #F7931E);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255,107,53,0.4);
        }

        .btn-secondary:hover {
            background: white;
            color: #2E7D32;
            transform: translateY(-5px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features {
            padding: 5rem 0;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2E7D32;
            font-weight: 700;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #FF6B35, #F7931E);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            position: relative;
        }

        .feature-icon::after {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            opacity: 0.2;
            z-index: -1;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #2E7D32;
            font-weight: 600;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 5rem 0;
            background: white;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 2;
        }

        .step h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #2E7D32;
        }

        .step p {
            color: #666;
        }

        /* CTA Section */
        .cta-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, #2E7D32, #4CAF50);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #4CAF50;
        }

        .footer-section p, .footer-section a {
            color: #ccc;
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
        }

        .footer-section a:hover {
            color: #4CAF50;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #333;
            color: #999;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .section-title {
                font-size: 2rem;
            }

            .feature-card {
                padding: 2rem;
            }
        }

        /* Floating Elements */
        .floating-truck {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 1000;
            animation: bounce 2s infinite;
            box-shadow: 0 5px 20px rgba(255,107,53,0.3);
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-truck"></i>
                Truck2Carrier
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#features" class="nav-link">Features</a></li>
                    <li><a href="#how-it-works" class="nav-link">How It Works</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                </ul>
            </nav>
            <a href="Registration.php" class="cta-button">Join Now</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Connecting Trucks to Crops</h1>
            <p>Bridge the gap between truck drivers and farmers in Yobe State. Efficient transportation solutions for agricultural success.</p>
            <div class="hero-buttons">
                <a href="tractorsdashboard.php" class="btn-primary">
                    <i class="fas fa-truck"></i>
                    I'm a Driver
                </a>
                <a href="farmerspage.php" class="btn-secondary">
                    <i class="fas fa-seedling"></i>
                    I'm a Farmer
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Why Choose Truck2Carrier?</h2>
            <p class="section-subtitle">Empowering agriculture in Yobe State through smart logistics</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Direct Connection</h3>
                    <p>Connect directly with verified truck drivers and farmers. No middlemen, no extra costs - just efficient transportation solutions.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Local Focus</h3>
                    <p>Specialized for Yobe State's agricultural landscape. We understand local routes, seasons, and farming communities.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Secure & Reliable</h3>
                    <p>All users are verified for safety and reliability. Track your cargo in real-time with our secure platform.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Time-Sensitive</h3>
                    <p>Perfect for agricultural produce that needs quick transportation. Fresh crops, timely delivery, better profits.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Easy to Use</h3>
                    <p>Simple mobile-friendly interface. Book trucks, track shipments, and manage payments all in one place.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Fair Pricing</h3>
                    <p>Transparent pricing with no hidden fees. Competitive rates that benefit both drivers and farmers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Simple steps to connect and transport</p>
            
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Register</h3>
                    <p>Sign up as a truck driver or farmer. Complete your profile with necessary details and verification.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Post or Browse</h3>
                    <p>Farmers post transport needs. Drivers browse available jobs or post their truck availability.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Connect</h3>
                    <p>Match based on location, cargo type, and schedule. Communicate directly through our platform.</p>
                </div>
                
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Transport</h3>
                    <p>Complete the transport with real-time tracking. Secure payment upon successful delivery.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Transform Agriculture in Yobe?</h2>
                <p>Join thousands of farmers and drivers already using Truck2Carrier</p>
                <a href="Registration.php" class="btn-primary">
                    <i class="fas fa-rocket"></i>
                    Get Started Today
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Truck2Carrier</h3>
                    <p>Connecting agricultural communities in Yobe State with efficient transportation solutions.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="#about">About Us</a>
                    <a href="#services">Services</a>
                    <a href="#pricing">Pricing</a>
                    <a href="#support">Support</a>
                </div>
                
                <div class="footer-section">
                    <h3>For Farmers</h3>
                    <a href="#farmer-signup">Join as Farmer</a>
                    <a href="#post-load">Post Your Cargo</a>
                    <a href="#track">Track Shipment</a>
                    <a href="#farmer-guide">Farmer Guide</a>
                </div>
                
                <div class="footer-section">
                    <h3>For Drivers</h3>
                    <a href="#driver-signup">Join as Driver</a>
                    <a href="#find-loads">Find Loads</a>
                    <a href="#earnings">Earnings</a>
                    <a href="#driver-guide">Driver Guide</a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Truck2Carrier. All rights reserved. Proudly serving Yobe State's agricultural community.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Button -->
    <div class="floating-truck" title="Quick Connect">
        <i class="fas fa-comments"></i>
    </div>

    <script>
        // Add smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to header
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'linear-gradient(135deg, rgba(46, 125, 50, 0.95), rgba(76, 175, 80, 0.95))';
            } else {
                header.style.background = 'linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%)';
            }
        });

        // Add animation on scroll for feature cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .step').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Floating button interaction
        document.querySelector('.floating-truck').addEventListener('click', () => {
            alert('Connect with us on WhatsApp: +234 706 897 7156');
        });
    </script>
</body>
</html>