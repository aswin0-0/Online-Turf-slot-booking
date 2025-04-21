<?php
session_start();
// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOKERS - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #002855;
            --secondary-color: #e74c3c;
            --accent-color: #3498db;
            --text-color: #2d3748;
            --sporty-green: #27ae60;
            --sporty-orange: #f39c12;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background: var(--primary-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .portal-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--sporty-orange);
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--sporty-orange);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .court-slider {
            position: relative;
            max-width: 1200px;
            margin: 2rem auto;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .slides-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            position: relative;
        }

        .slide img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 12px;
        }

        .slide-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 1rem;
        }

        .slider-btn {
            background: rgba(255,255,255,0.8);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-btn:hover {
            background: white;
        }

        .slider-dots {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .main-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in;
            flex: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-section {
            text-align: center;
            margin: 3rem 0;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--sporty-green), var(--accent-color));
        }

        .welcome-heading {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .welcome-section p {
            font-size: 1.1rem;
            color: #555;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 2.5rem 0;
        }

        .action-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-top: 4px solid var(--sporty-green);
            position: relative;
            overflow: hidden;
        }

        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .action-card h3 a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .action-card:hover h3 a {
            color: var(--accent-color);
        }

        .card-icon {
            font-size: 2.5rem;
            color: var(--sporty-green);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .action-card:hover .card-icon {
            transform: scale(1.1);
            color: var(--accent-color);
        }

        .action-card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .action-card p {
            color: #666;
            font-size: 1rem;
        }

        .booking-section {
            margin-top: 3rem;
        }

        .booking-section h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }

        .booking-section h2::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            bottom: -8px;
            left: 0;
            background: linear-gradient(90deg, var(--sporty-green), var(--accent-color));
            border-radius: 2px;
        }

        .about-section {
            margin: 5rem 0;
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--sporty-green), var(--accent-color));
        }

        .about-section h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .about-section h2::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(90deg, var(--sporty-green), var(--accent-color));
            border-radius: 2px;
        }

        .about-content {
            display: flex;
            align-items: center;
            gap: 3rem;
            margin-top: 3rem;
        }

        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            height: 400px;
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        .about-text {
            flex: 1;
        }

        .about-text h3 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }

        .about-text p {
            margin-bottom: 1.5rem;
            color: #555;
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
            border-left: 3px solid var(--sporty-green);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            background: white;
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--sporty-green);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            color: var(--accent-color);
            transform: scale(1.1);
        }

        .feature-item h4 {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .feature-item p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0;
        }

        .reviews-section {
            margin: 5rem 0;
            background: linear-gradient(to bottom, #f8f9fa, white);
            padding: 3rem 2rem;
            border-radius: 12px;
        }

        .reviews-section h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 3rem;
            text-align: center;
            position: relative;
        }

        .reviews-section h2::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 4px;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(90deg, var(--sporty-green), var(--accent-color));
            border-radius: 2px;
        }

        .reviews-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .review-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .review-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .reviewer-info {
            flex: 1;
        }

        .reviewer-info h4 {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 0.3rem;
        }

        .review-stars {
            color: var(--sporty-orange);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .review-text {
            color: #555;
            line-height: 1.7;
            font-style: italic;
            position: relative;
            padding-left: 1.5rem;
            font-size: 1rem;
        }

        .review-text::before {
            content: '"';
            position: absolute;
            left: 0;
            top: -10px;
            font-size: 3rem;
            color: rgba(39, 174, 96, 0.1);
            font-family: serif;
        }

        .review-date {
            display: block;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #888;
            text-align: right;
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 3rem 2rem;
            margin-top: auto;
            border-top: 3px solid var(--sporty-orange);
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            text-align: left;
        }

        .footer-section {
            margin-bottom: 1.5rem;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--sporty-orange);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--sporty-orange);
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .social-icons a {
            font-size: 1.5rem;
            color: white;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: var(--sporty-orange);
        }

        .copyright {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
            }
           
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .welcome-section {
                padding: 2rem 1.5rem;
            }

            .welcome-heading {
                font-size: 2rem;
            }

            .slide img {
                height: 300px;
            }
           
            .slide-caption {
                padding: 1rem;
            }

            .about-content {
                flex-direction: column;
            }

            .about-image {
                margin-bottom: 2rem;
                height: 300px;
            }

            .about-features {
                grid-template-columns: 1fr;
            }

            .footer-content {
                text-align: center;
            }
            
            .footer-links {
                margin-bottom: 2rem;
            }
            
            .social-icons {
                justify-content: center;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .shake {
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .slides-container {
        display: flex;
        width: 100%; /* Add this */
        transition: transform 0.5s ease-in-out;
        }

        .slide {
        min-width: 100%;
        position: relative;
        flex-shrink: 0; /* Add this to prevent squishing */
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <h1 class="portal-title">BOKERS</h1>
        </div>
        <nav class="nav-links">
            <!-- Updated navigation links -->
            <a href="home.php" class="nav-link active">Home</a>
            <a href="booking.php" class="nav-link">My Bookings</a>
            <a href="events.php" class="nav-link">Events</a>
            <a href="profile.php" class="nav-link">Profile</a>
        </nav>
    </header>

    <main class="main-container">
        <section class="welcome-section">
            <h2 class="welcome-heading">Welcome to BOKERS Mumbai!</h2>
            <p>Book your sports court slots at Mumbai's premier sports facility</p>
           
            <div class="quick-actions">
                <div class="action-card pulse">
                    <i class="fas fa-calendar-plus card-icon"></i>
                    <h3><a href="bookslot.php">Book Newslots</a></h3>
                    <p>Schedule your sports activities</p>
                </div>
                <div class="action-card">
                    <i class="fas fa-clipboard-list card-icon"></i>
                    <h3><a href="booking.php">My Bookings</a></h3>
                    <p>View and manage existing bookings</p>
                </div>
                <div class="action-card">
                    <i class="fas fa-calendar-alt card-icon"></i>
                    <h3><a href="events.php">Upcoming Events</a></h3>
                    <p>Join tournaments and special games</p>
                </div>
            </div>
        </section>

        <section class="booking-section">
            <h2>Our Facilities</h2>
            <div class="court-slider">
                <div class="slides-container">
                    <div class="slide">
                        <img src="https://whosonthemove.com/wp-content/uploads/2019/07/Byerly-Basketball-Court.jpg" alt="Basketball Court">
                        <div class="slide-caption">
                            <h3>Professional Basketball Court</h3>
                            <p>FIBA approved flooring and equipment</p>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://th.bing.com/th/id/R.9d5017f8a3e888dfdbd71795513a31f2?rik=Dj5qzSteu8YjRA&riu=http%3a%2f%2fgetwallpapers.com%2fwallpaper%2ffull%2fa%2fd%2f2%2f511062.jpg&ehk=WwOYZvMg%2beXiQdVflfuTForKvzYM7B81PltkFONelKQ%3d&risl=&pid=ImgRaw&r=0" alt="Tennis Court">
                        <div class="slide-caption">
                            <h3>Olympic-standard Tennis Courts</h3>
                            <p>Clay and hard court options available</p>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://wallpapercave.com/wp/wp2302368.jpg" alt="Badminton Court">
                        <div class="slide-caption">
                            <h3>International Badminton Courts</h3>
                            <p>BWF-approved shuttle courts</p>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://images.rawpixel.com/image_800/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI0LTA2L3Jhd3BpeGVsX29mZmljZV8zMl9waG90b19vZl9hX3NvY2Nlcl9zdGFkaXVtX29uX3doaXRlX2JhY2tncm91bl9hZWEzNWVlYi1hY2E3LTRkMGMtYjczMS0yMGYyOTJjMDA1M2RfMS5qcGc.jpg" alt="Soccer Field">
                        <div class="slide-caption">
                            <h3>Professional Soccer Field</h3>
                            <p>FIFA approved turf and equipment</p>
                        </div>
                    </div>
                    <div class="slide">
                        <img src="https://www.sportsimports.com/wp-content/uploads/cropped-mens_volleyball_championship_net_system-1-e1679796999506-2048x1207.png" alt="Volleyball Court">
                        <div class="slide-caption">
                            <h3>Championship Volleyball Courts</h3>
                            <p>Professional net systems and flooring</p>
                        </div>
                    </div>
                </div>

                <div class="slider-nav">
                    <button class="slider-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                    <button class="slider-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                </div>

                <div class="slider-dots"></div>
            </div>
        </section>

        <section class="about-section">
            <h2>About BOKERS </h2>
            <div class="about-content">
                <div class="about-image">
                    <img src="https://aspiringgentleman.com/wp-content/uploads/2019/10/Cricket-Sport.jpg" alt="Mumbai Sports Complex">
                </div>
                <div class="about-text">
                    <h3>Mumbai's Premier Sports Facility</h3>
                    <p>Welcome to BOKERS Mumbai, the city's most trusted sports facility located in the heart of the city. Our mission is to provide world-class sports facilities to Mumbai's sports enthusiasts with seamless booking experiences.</p>
                    <p>Established in 2015, we've grown to become Mumbai's favorite sports destination with 12 different sports facilities under one roof, serving thousands of sports lovers every month.</p>
                   
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-rupee-sign feature-icon"></i>
                            <h4>Affordable</h4>
                            <p>Competitive pricing for Mumbai</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-bolt feature-icon"></i>
                            <h4>Instant</h4>
                            <p>Real-time booking confirmation</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-map-marker-alt feature-icon"></i>
                            <h4>Central Location</h4>
                            <p>Easy access from all parts of Mumbai</p>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt feature-icon"></i>
                            <h4>Secure</h4>
                            <p>100% safe and verified bookings</p>
                        </div>
                    </div>
                    
                   
                </div>
            </div>
        </section>

        <section class="reviews-section">
            <h2>What Our Customers Say</h2>
            <div class="reviews-container">
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <h4>Rajesh Verma</h4>
                            <div class="review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="review-text">BOKERS Mumbai has the best badminton courts in the city! The booking system is so convenient and the courts are always well-maintained. My weekly games have never been better.</p>
                    <span class="review-date">March 15, 2023</span>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <h4>Priya Desai</h4>
                            <div class="review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="review-text">As a tennis player in Mumbai, I've tried many facilities but BOKERS stands out. The courts are excellent and the online booking makes it so easy to reserve my preferred time slots.</p>
                    <span class="review-date">February 28, 2023</span>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <h4>Vikram Patel</h4>
                            <div class="review-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="review-text">The basketball facility at BOKERS Mumbai is top-notch. I've been coming here for years and the quality has only improved. Easy online booking and great staff make it my go-to place.</p>
                    <span class="review-date">January 10, 2023</span>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="booking.php">Bookings</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>BOKERS Mumbai</p>
                <p>Sports Complex, Marine Drive</p>
                <p>Mumbai 400001</p>
                <p>Email: mumbai@bokers.in</p>
                <p>Phone: +91 98765 43210</p>
            </div>
            
            <div class="footer-section">
                <h4>Legal</h4>
                <ul class="footer-links">
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms of Service</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                </ul>
            </div>
        </div>
        
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
        
        <div class="copyright">
            <p>© 2023 BOKERS Mumbai. All rights reserved.</p>
            <p>Designed with <i class="fas fa-heart" style="color: #e74c3c;"></i> in India</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slider Functionality
        const sliderContainer = document.querySelector('.slides-container');
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const dotsContainer = document.querySelector('.slider-dots');
        let currentSlide = 0;
        const totalSlides = slides.length;

        // Create slider dots
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.classList.add('slider-dot');
            if(index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        // Slide navigation functions
        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            updateSlider();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function updateSlider() {
            sliderContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            document.querySelectorAll('.slider-dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Event listeners for controls
        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);

        // Auto-advance slider
        let autoSlide = setInterval(nextSlide, 5000);

        // Pause on hover
        sliderContainer.parentElement.addEventListener('mouseenter', () => {
            clearInterval(autoSlide);
        });

        sliderContainer.parentElement.addEventListener('mouseleave', () => {
            autoSlide = setInterval(nextSlide, 5000);
        });

        // Navigation active state management
        const navLinks = document.querySelectorAll('.nav-link');
        const currentPage = window.location.pathname.split('/').pop();

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
            
            link.addEventListener('click', function(e) {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Additional animations
        const featuredCard = document.querySelector('.pulse');
        if(featuredCard) {
            setInterval(() => {
                featuredCard.classList.toggle('pulse');
            }, 4000);
        }
    });
</script>
</body>
</html>

