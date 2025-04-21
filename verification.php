<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['booking_id'])) {
    header("Location: home.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT b.*, c.name AS court_name, c.location, c.image_url 
                         FROM bookings b
                         JOIN courts c ON b.court_id = c.id
                         WHERE b.id = ? AND b.user_id = ?");
    $stmt->execute([$_GET['booking_id'], $_SESSION['user_id']]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) {
        throw new Exception('Booking not found');
    }
    
    // Calculate times
    $start_time = new DateTime($booking['start_time']);
    $end_time = clone $start_time;
    $end_time->add(new DateInterval("PT".$booking['duration']."H"));
    
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Sports Court Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #002855;
            --secondary-color: #FFC72C;
            --accent-color: #e2e8f0;
            --text-color: #2d3748;
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

        .confirmation-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .confirmation-icon {
            font-size: 4rem;
            color: #27ae60;
            margin-bottom: 1rem;
        }

        .booking-details {
            text-align: left;
            margin: 2rem 0;
            padding: 1.5rem;
            background: var(--accent-color);
            border-radius: 8px;
        }

        .detail-row {
            margin: 1rem 0;
            padding: 0.5rem 0;
            border-bottom: 1px solid #ddd;
        }

        .detail-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .home-btn {
            background: var(--secondary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            margin-top: auto;
            text-align: center;
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

    <div class="confirmation-container">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Booking Confirmed!</h1>
        <p>Your booking details have been sent to your registered email</p>

        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Court Name:</span>
                <?= htmlspecialchars($booking['court_name']) ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <?= date('F j, Y', strtotime($booking['booking_date'])) ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <?= $start_time->format('h:i A') ?> - <?= $end_time->format('h:i A') ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Location:</span>
                <?= htmlspecialchars($booking['location']) ?>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                ₹<?= number_format($booking['total_amount'], 2) ?>
            </div>
        </div>

        <a href="home.php" class="home-btn">Return to Home</a>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <p>© 2023 Sports Court Booking</p>
            <p>Contact: support@sportsbooking.com | Help Desk: (022) 1234-5678</p>
        </div>
    </footer>
</body>
</html>