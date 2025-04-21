<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT b.id, 
               c.name AS facility, 
               DATE_FORMAT(b.booking_date, '%Y-%m-%d') AS booking_date,
               DATE_FORMAT(b.start_time, '%H:%i') AS start_time,
               b.duration,
               b.status,
               DATE_FORMAT(ADDTIME(b.start_time, SEC_TO_TIME(b.duration*3600)), '%H:%i') AS end_time
        FROM bookings b
        JOIN courts c ON b.court_id = c.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC, b.start_time DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching bookings: " . $e->getMessage());
}

// Handle session messages
$error_message = $_SESSION['error'] ?? null;
$success_message = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Sports Court Booking</title>
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
        }

        .header {
            background: var(--primary-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .portal-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--secondary-color);
        }

        .main-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }

        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .bookings-table th, 
        .bookings-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .bookings-table th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        .bookings-table tr:hover {
            background: #f8fafc;
        }

        .status-pill {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .confirmed {
            background: #e6f4ea;
            color: #137333;
        }

        .pending {
            background: #fef7e0;
            color: #f9ab00;
        }

        .cancelled {
            background: #fce8e6;
            color: #c5221f;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .cancel-btn {
            background: #fce8e6;
            color: #c5221f;
        }

        .cancel-btn:hover {
            background: #f5c0b8;
        }

        .no-bookings {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #e6f4ea;
            color: #137333;
            border-color: #137333;
        }

        .alert-error {
            background: #fce8e6;
            color: #c5221f;
            border-color: #c5221f;
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
            
            .bookings-table {
                display: block;
                overflow-x: auto;
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
            <a href="booking.php" class="nav-link active">My Bookings</a>
            <a href="events.php" class="nav-link">Events</a>
            <a href="profile.php" class="nav-link">Profile</a>
        </nav>
    </header>

    <main class="main-container">
        <h1 class="page-title">My Bookings</h1>
        
        <?php if($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        
        <?php if($error_message): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <?php if(count($bookings) > 0): ?>
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Facility</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $booking): ?>
                    <tr>
                        <td>#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></td>
                        <td><?= htmlspecialchars($booking['facility']) ?></td>
                        <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                        <td>
                            <?= htmlspecialchars($booking['start_time']) ?> - 
                            <?= htmlspecialchars($booking['end_time']) ?>
                        </td>
                        <td>
                            <span class="status-pill <?= strtolower(htmlspecialchars($booking['status'])) ?>">
                                <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if($booking['status'] !== 'cancelled'): ?>
                                <button class="action-btn cancel-btn" 
                                        data-booking="<?= $booking['id'] ?>">
                                    Cancel
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-bookings">
                <h3>No Bookings Found</h3>
                <p>You haven't made any bookings yet. Start by booking a facility from our home page.</p>
                <a href="home.php" class="action-btn">Browse Facilities</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>© 2023 Sports Court Booking System</p>
            <p>Contact: support@sportsbooking.com | Help Desk: (022) 1234-5678</p>
        </div>
    </footer>

    <script>
        // Cancel button handling
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.dataset.booking;
                if(confirm('Are you sure you want to cancel this booking?\nThis action cannot be undone.')) {
                    window.location.href = `cancel_booking.php?id=${bookingId}`;
                }
            });
        });

        // Set active navigation link
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.href.endsWith(currentPage)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>