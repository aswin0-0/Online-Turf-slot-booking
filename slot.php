<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['court_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM courts WHERE id = ?");
        $stmt->execute([$_GET['court_id']]);
        $court = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$court) {
            header("Location: bookcourts.php");
            exit();
        }
    } catch(PDOException $e) {
        die("Error fetching court details: " . $e->getMessage());
    }
} else {
    header("Location: bookcourts.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Slot - <?= htmlspecialchars($court['name']) ?> | Sports Court Booking</title>
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

        .booking-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .form-title {
            color: var(--primary-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .booking-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        select, input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid var(--accent-color);
            border-radius: 6px;
            font-size: 1rem;
        }

        .total-section {
            grid-column: 1 / -1;
            padding: 1.5rem;
            background: var(--accent-color);
            border-radius: 8px;
            margin-top: 1rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .grand-total {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .night-charges {
            color: #c53030;
            font-weight: 600;
            display: none;
        }

        .book-now-btn {
            background: var(--secondary-color);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            grid-column: 1 / -1;
            margin-top: 1.5rem;
        }

        .book-now-btn:hover {
            background: #ffb400;
            transform: translateY(-2px);
        }

        .footer {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .booking-form {
                grid-template-columns: 1fr;
            }
            
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
        }
        /* Custom Date Picker Styling */
        .date-picker-container {
            position: relative;
        }

        #bookingDate {
            padding-right: 2.5rem;
            cursor: pointer;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        #bookingDate:hover {
            background-color: #ffffff;
            border-color: var(--secondary-color);
        }

        #bookingDate:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 40, 85, 0.1);
        }

        /* Calendar Icon */
        .date-picker-container::after {
            content: '\f073';
            font-family: 'Font Awesome 5 Free';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            pointer-events: none;
            font-weight: 900;
        }

        /* Date Picker Customization */
        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            position: absolute;
            right: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        input[type="date"]::-webkit-datetime-edit {
            color: var(--text-color);
            padding: 0.2rem;
        }

        input[type="date"]::-webkit-datetime-edit-fields-wrapper {
            background: transparent;
        }

        input[type="date"]::-webkit-datetime-edit-text {
            color: var(--primary-color);
            padding: 0 0.2rem;
        }

        input[type="date"]::-webkit-inner-spin-button {
            display: none;
        }

        input[type="date"]::-webkit-clear-button {
            margin-right: 10px;
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

    <div class="booking-container">
        <h2 class="form-title">Book Slot - <?= htmlspecialchars($court['name']) ?></h2>
        <form class="booking-form" id="bookingForm">
            <input type="hidden" id="court_id" value="<?= $court['id'] ?>">

            <div class="form-group date-picker-container">
                <label for="bookingDate">Select Date</label>
                <input type="date" 
                    id="bookingDate" 
                    required 
                    min="<?= date('Y-m-d') ?>"
                    placeholder="Choose your booking date"
                    aria-label="Select booking date">
            </div>

            <div class="form-group">
                <label for="bookingTime">Select Time</label>
                <select id="bookingTime" required>
                    <option value="">Choose time slot</option>
                    <?php for($hour = 6; $hour <= 22; $hour++): ?>
                    <option value="<?= str_pad($hour, 2, '0', STR_PAD_LEFT) ?>:00">
                        <?= str_pad($hour, 2, '0', STR_PAD_LEFT) ?>:00
                    </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="duration">Duration (hours)</label>
                <select id="duration" required>
                    <option value="1">1 hour</option>
                    <option value="2">2 hours</option>
                    <option value="3">3 hours</option>
                </select>
            </div>

            <div class="total-section">
                <div class="total-row">
                    <span>Court Fee:</span>
                    <span id="courtFee">₹0.00</span>
                </div>
                <div class="total-row night-charges">
                    <span>Night Charges (<span id="nightHours">0</span>hrs × ₹<?= number_format($court['night_charge'], 2) ?>):</span>
                    <span id="nightCharges">₹0.00</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total Amount:</span>
                    <span id="totalAmount">₹0.00</span>
                </div>
            </div>

            <button type="button" class="book-now-btn" onclick="confirmBooking()">Confirm Booking</button>
        </form>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <p>© 2023 Sports Court Booking</p>
            <p>Contact: support@sportsbooking.com | Help Desk: (022) 1234-5678</p>
            <div class="social-links" style="margin-top: 1rem;">
                <a href="#" style="color: white; margin: 0 0.5rem;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color: white; margin: 0 0.5rem;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: white; margin: 0 0.5rem;"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>
        const baseRate = <?= $court['base_rate'] ?>;
        const nightRate = <?= $court['night_charge'] ?>;

        function calculateTotal() {
            const duration = parseInt(document.getElementById('duration').value) || 0;
            const timeValue = document.getElementById('bookingTime').value;
            const [selectedHour] = timeValue.split(':');
            const startHour = parseInt(selectedHour, 10);
            const endHour = startHour + duration;
            const nightHours = Math.max(0, Math.min(endHour, 24) - Math.max(startHour, 19));
            
            const courtFee = duration * baseRate;
            const nightCharge = nightHours * nightRate;
            const total = courtFee + nightCharge;

            // Format numbers with 2 decimal places
            document.getElementById('courtFee').textContent = `₹${courtFee.toFixed(2)}`;
            document.getElementById('nightHours').textContent = nightHours;
            document.getElementById('nightCharges').textContent = `₹${nightCharge.toFixed(2)}`;
            document.getElementById('totalAmount').textContent = `₹${total.toFixed(2)}`;
            document.querySelector('.night-charges').style.display = nightHours > 0 ? 'flex' : 'none';
        }

        async function confirmBooking() {
            const courtId = document.getElementById('court_id').value;
            const bookingDate = document.getElementById('bookingDate').value;
            const time = document.getElementById('bookingTime').value;
            const duration = parseInt(document.getElementById('duration').value);
            const total = parseFloat(document.getElementById('totalAmount').textContent.replace(/[^0-9.]/g, ''));

            if (!bookingDate || !time || !duration || isNaN(total)) {
                alert('Please fill all required fields correctly');
                return;
            }

            try {
                const response = await fetch('process_booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        court_id: courtId,
                        booking_date: bookingDate,
                        start_time: time,
                        duration: duration,
                        total_amount: total
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    window.location.href = `verification.php?booking_id=${result.booking_id}`;
                } else {
                    alert(result.message || 'Booking failed. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Booking failed due to technical error. Please try again.');
            }
        }

        // Initialize event listeners
        document.getElementById('duration').addEventListener('change', calculateTotal);
        document.getElementById('bookingTime').addEventListener('change', calculateTotal);
        document.getElementById('bookingDate').addEventListener('change', calculateTotal);
        calculateTotal();
    </script>
</body>
</html>