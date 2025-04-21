<?php
// Start session and check authentication
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    // Fetch pitches from database where type = 'pitch'
    $stmt = $pdo->query("SELECT * FROM courts WHERE type = 'pitch'");
    $pitches = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error loading pitches: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Sports Pitches - Sports Court Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #002855;
            --secondary-color: #FFC72C;
            --accent-color: #e2e8f0;
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
            background: #f8fafc;
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .pitch-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            flex: 1;
        }

        .page-title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 3px;
            background: var(--secondary-color);
        }

        .pitch-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .pitch-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .pitch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .pitch-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 4px solid var(--secondary-color);
        }

        .pitch-details {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .pitch-name {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .pitch-address {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .book-button {
            background: var(--sporty-green);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            width: fit-content;
        }

        .book-button:hover {
            background: #219653;
            transform: translateY(-2px);
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            margin-top: auto;
            text-align: center;
        }

        @media (max-width: 768px) {
            .pitch-grid {
                grid-template-columns: 1fr;
            }
            
            .pitch-image {
                height: 200px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <h1 class="portal-title">Sports Court Booking</h1>
        </div>
        <nav class="nav-links">
            <a href="home.php" class="nav-link">Home</a>
            <a href="booking.php" class="nav-link">My Bookings</a>
            <a href="calendar.php" class="nav-link">Calendar</a>
            <a href="profile.php" class="nav-link">Profile</a>
        </nav>
    </header>

    <main class="pitch-container">
        <h1 class="page-title">Book Sports Pitches</h1>
        
        <div class="pitch-grid">
            <?php foreach ($pitches as $pitch): ?>
            <div class="pitch-card">
                <img src="<?= htmlspecialchars($pitch['image_url']) ?>" 
                     alt="<?= htmlspecialchars($pitch['name']) ?>" 
                     class="pitch-image">
                <div class="pitch-details">
                    <h2 class="pitch-name"><?= htmlspecialchars($pitch['name']) ?></h2>
                    <p class="pitch-address"><?= htmlspecialchars($pitch['location']) ?></p>
                    <a href="slot.php?court_id=<?= $pitch['id'] ?>" class="book-button">Book Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>© 2023 Sports Court Booking System</p>
            <p>Contact: support@sportsbooking.com | Help Desk: (022) 1234-5678</p>
        </div>
    </footer>

    <script>
        // Active link highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const currentPage = window.location.pathname.split('/').pop();
            const currentPageBase = currentPage.split('.')[0];

            navLinks.forEach(link => {
                const linkPage = link.getAttribute('href').split('.')[0];
                if (linkPage === currentPageBase) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>