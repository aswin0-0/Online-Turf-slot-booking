<?php 
session_start();
$pageTitle = "Events - BOKERS Sports Portal";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOKERS - Events</title>
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

        .main-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-in;
            flex: 1;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 40, 85, 0.8), rgba(0, 40, 85, 0.8)),
                        url('https://images.unsplash.com/photo-1552674605-db6ffd4facb5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 6rem 2rem;
            text-align: center;
            border-radius: 12px;
            margin: 2rem 0;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin: 2.5rem 0;
        }

        @media (max-width: 768px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
            .header {
                flex-direction: column;
                gap: 1.5rem;
            }
        }

        .action-card {
            background: white;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .action-card:hover .event-image {
            transform: scale(1.05);
        }

        .card-content {
            padding: 1.5rem;
            text-align: center;
        }

        .sports-icon {
            font-size: 2.5rem;
            color: var(--sporty-green);
            margin: -2rem 0 1rem 0;
            position: relative;
            z-index: 1;
        }

        .event-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 1rem 0;
            color: var(--primary-color);
        }

        .event-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            margin-top: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
        }

        .featured-event {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                        url('https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 4rem 2rem;
            border-radius: 12px;
            margin: 3rem 0;
            text-align: center;
        }

        .footer {
            background: #002855;
            color: white;
            padding: 3rem 2rem;
            margin-top: auto;
            border-top: 3px solid #3498db;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section {
            margin-bottom: 1.5rem;
        }

        .footer-section h4 {
            color: #3498db;
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #3498db;
        }

        .copyright {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            <a href="Booking.php" class="nav-link">My Bookings</a>
            <a href="events.php" class="nav-link active">Events</a>
            <a href="Profile.php" class="nav-link">Profile</a>
        </nav>
    </header>

    <main class="main-container">
        <section class="hero-section">
            <h1 class="portal-title" style="font-size: 3rem; margin-bottom: 1rem;">Mumbai Sports Events 2024</h1>
            <p style="font-size: 1.2rem;">Compete in Premier Tournaments & Championships</p>
        </section>

        <div class="quick-actions">
            <!-- Badminton Event -->
            <div class="action-card">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHK2DIDVKKDEesGsQn2h5a-SFnFA1N5TJMLw&s" 
                     alt="Badminton" 
                     class="event-image">
                <div class="card-content">
                    <i class="fas fa-shuttlecock sports-icon"></i>
                    <h3>Mumbai Badminton Premier League</h3>
                    <div class="event-details">
                        <div class="event-date">
                            <i class="fas fa-calendar-day"></i>
                            10-15 May 2024
                        </div>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            Court 5-8
                        </div>
                    </div>
                    <p>Open doubles tournament with ₹50,000 prize pool</p>
                    <button class="btn-primary" disabled>Registration Not Started Yet</button>
                </div>
            </div>

            <!-- Basketball Event -->
            <div class="action-card">
                <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1300&q=80" 
                     alt="Basketball" 
                     class="event-image">
                <div class="card-content">
                    <i class="fas fa-basketball-ball sports-icon"></i>
                    <h3>3x3 Basketball Challenge</h3>
                    <div class="event-details">
                        <div class="event-date">
                            <i class="fas fa-calendar-day"></i>
                            17-19 May 2024
                        </div>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            Main Arena
                        </div>
                    </div>
                    <p>Fast-paced street basketball tournament</p>
                    <button class="btn-primary" disabled>Registration Not Started Yet</button>
                </div>
            </div>

            <!-- Table Tennis Event -->
            <div class="action-card">
                <img src="https://media.istockphoto.com/id/1425158165/photo/table-tennis-ping-pong-paddles-and-white-ball-on-blue-board.jpg?s=612x612&w=0&k=20&c=KSdi4bEGoxdhaGMnl6CZaqTLbKbobArgrrpLem3oN98=" 
                     alt="Table Tennis" 
                     class="event-image">
                <div class="card-content">
                    <i class="fas fa-table-tennis sports-icon"></i>
                    <h3>Corporate Table Tennis Cup</h3>
                    <div class="event-details">
                        <div class="event-date">
                            <i class="fas fa-calendar-day"></i>
                            25 May 2024
                        </div>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            TT Zone
                        </div>
                    </div>
                    <p>Inter-company team championship</p>
                    <button class="btn-primary" disabled>Registration Not Started Yet</button>                
                </div>
            </div>
        </div>

        <section class="featured-event">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Mumbai Sports Fest 2024</h2>
            <p style="font-size: 1.2rem; margin-bottom: 2rem;">25-30 May | Marine Drive Sports Complex</p>
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; min-width: 200px;">
                    <i class="fas fa-trophy" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p>₹5,00,000<br>Total Prizes</p>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; min-width: 200px;">
                    <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p>1000+<br>Participants</p>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; min-width: 200px;">
                    <i class="fas fa-medal" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p>15+<br>Sports Categories</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>BOKERS Mumbai</p>
                <p>Sports Complex, Marine Drive</p>
                <p>Mumbai 400001</p>
                <p>Email: contact@bokers.in</p>
                <p>Phone: +91 98765 43210</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="terms.php">Terms of Service</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-icons" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="#" style="color: white;"><i class="fab fa-facebook fa-2x"></i></a>
                    <a href="#" style="color: white;"><i class="fab fa-twitter fa-2x"></i></a>
                    <a href="#" style="color: white;"><i class="fab fa-instagram fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>© 2024 BOKERS. All rights reserved.</p>
            <p style="margin-top: 0.5rem;">Designed with <i class="fas fa-heart" style="color: #e74c3c;"></i> in Mumbai</p>
        </div>
    </footer>

    <script>
        // Navigation active state
        document.querySelectorAll('.nav-link').forEach(link => {
            if(link.href === window.location.href) {
                link.classList.add('active');
            }
        });

        // Image hover effect
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('mouseover', () => {
                card.querySelector('.event-image').style.transform = 'scale(1.05)';
            });
            card.addEventListener('mouseout', () => {
                card.querySelector('.event-image').style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
