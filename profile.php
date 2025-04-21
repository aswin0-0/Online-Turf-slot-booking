<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sports_portal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Booking Portal - Profile</title>
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

        .profile-section {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 3rem;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 5px solid var(--accent-color);
        }

        .profile-name {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .profile-details {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .details-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 1rem;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
        }

        .detail-value {
            color: var(--text-color);
        }

        @media (max-width: 768px) {
            .profile-section {
                grid-template-columns: 1fr;
            }
            
            .detail-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <h1 class="portal-title">Slot Booking Portal</h1>
        </div>
        <nav class="nav-links">
            <a href="home.php" class="nav-link">Home</a>
            <a href="profile.php" class="nav-link active">Profile</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </header>

    <main class="main-container">
        <h1 class="page-title">My Profile</h1>
        
        <div class="profile-section">
            <div class="profile-card">
                <img src="https://pbs.twimg.com/media/GgYw7OyXQAA5rOx?format=jpg&name=4096x4096" alt="Profile Picture" class="profile-pic">
                <h2 class="profile-name"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-label">Member Since</div>
                        <div class="stat-value"><?php echo date('Y', strtotime($user['created_at'])); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="profile-details">
                <div class="details-section">
                    <h3 class="section-title">Personal Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Full Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['phone']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Aadhaar:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['aadhaar']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Gender:</span>
                        <span class="detail-value"><?php echo ucfirst($user['gender']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>