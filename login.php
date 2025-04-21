<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sports_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token!");
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['logged_in'] = true;

            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $login_error = "Invalid email or password";
        }
    } else {
        $login_error = "Invalid email or password";
    }

    $stmt->close();
}

// Generate CSRF token
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Booking Portal - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                        url('https://img.freepik.com/free-photo/sports-tools_53876-138077.jpg') center/cover fixed;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 450px;
            transform: translateY(-20px);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 120px;
            margin-bottom: 1rem;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .tagline {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .input-field {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #3498db;
        }

        .options {
            display: flex;
            justify-content: space-between;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #7f8c8d;
        }

        .forgot-password {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #2980b9;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: #2980b9;
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #7f8c8d;
        }

        .signup-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
            }
        }

        .error-message {
        color: #ff0000; /* Bright red color */
        text-align: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        font-weight: bold; /* Added bold text */
        padding: 10px;
        background: #ffeeee; /* Light red background */
        border-radius: 5px;
        border: 1px solid #ffcccc;
        }
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <img src="https://i.pinimg.com/736x/e3/ad/91/e3ad91f2d6a47a91418098d735aa7030.jpg" alt="College Logo" class="logo">
            <h1>Slot Booking Portal</h1>
            <p class="tagline"><b>Bokers</b></p>
        </div>

        <?php if (!empty($login_error)): ?>
            <div class="error-message"><?php echo $login_error; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="form-group">
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="login-btn">Sign In</button>

            <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a></p>
        </form>
    </div>
</body>
</html>