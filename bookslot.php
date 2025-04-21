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
    <title>BOKERS - Book Slots</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #002855; 
            --secondary-color: #e74c3c;
            --accent-color: #3498db;
            --sporty-green: #27ae60;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #2d3748;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .header {
            background: var(--primary-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-color);
        }

        /* Main Content */
        .main-container {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .booking-section {
            text-align: center;
            margin: 2rem 0;
        }

        .section-heading {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .facility-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem auto;
            max-width: 800px;
        }

        .facility-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .facility-card:hover {
            transform: translateY(-5px);
        }

        .facility-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 1rem;
        }

        .facility-name {
            color: var(--primary-color);
            font-size: 1.4rem;
            margin: 0;
        }

        /* Footer Styles */
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

        .copyright {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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

            .facility-cards {
                scroll-snap-type: x mandatory;
            }

            .facility-card {
                min-width: 85vw;
                scroll-snap-align: center;
            }

            .footer-content {
                text-align: center;
            }

            .footer-links {
                margin-bottom: 2rem;
            }
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
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
</style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <h1 class="portal-title">BOKERS</h1>
        </div>
        <nav class="nav-links">
            <a href="home.php" class="nav-link">Home</a>
            <a href="booking.php" class="nav-link">My Bookings</a>
            <a href="events.php" class="nav-link">Events</a>
            <a href="profile.php" class="nav-link active">Profile</a>
        </nav>
    </header>

    <main class="main-container">
        <section class="booking-section">
            <h2 class="section-heading">Book Facilities</h2>
            <p class="section-subtitle">Select your preferred facility type</p>
            
            <div class="facility-cards">
                <div class="facility-card pulse">
                    <img src="https://static.vecteezy.com/system/resources/previews/038/258/524/non_2x/cricket-player-logo-design-illustration-vector.jpg" 
                         alt="Sports Pitches" 
                         class="facility-image">
                    <h3 class="facility-name">Sports Pitches</h3>
                    <div class="booking-cta">
                        <button class="btn-primary" onclick="window.location.href='pitches.php'">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                    </div>
                </div>

                <div class="facility-card pulse">
                    <img src="https://t3.ftcdn.net/jpg/00/54/89/08/360_F_54890802_j6XIBBYEpMf9D5mTZsSrqmXmJCtT3X0d.jpg" 
                         alt="Sports Courts" 
                         class="facility-image">
                    <h3 class="facility-name">Sports Courts</h3>
                    <div class="booking-cta">
                        <button class="btn-primary" onclick="window.location.href='bookcourts.php'">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <!-- Use the same footer structure from home.php -->
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
        
        <div class="copyright">
            <p>© 2023 BOKERS Mumbai. All rights reserved.</p>
            <p>Designed with <i class="fas fa-heart" style="color: #e74c3c;"></i> in India</p>
        </div>
    </footer>

    <script>
        // Use the same JavaScript from home.php
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation active state
            const currentPage = window.location.pathname.split('/').pop();
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });

            // Pulse animation for cards
            const facilityCards = document.querySelectorAll('.facility-card');
            facilityCards.forEach(card => {
                setInterval(() => {
                    card.classList.toggle('pulse');
                }, 4000);
            });
        });
    </script>
</body>
</html>
