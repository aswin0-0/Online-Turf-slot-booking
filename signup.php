<?php
// Start PHP processing only when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    

    // Validate required fields
    $required = ['fullName', 'age', 'phone', 'aadhaar', 'email', 'password', 'confirmPassword', 'gender'];
    $errors = [];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "$field is required";
        }
    }

    // Validate file upload
    if (empty($_FILES['aadhaar_image']['name'])) {
        $errors[] = "Aadhaar image is required";
    } else {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['aadhaar_image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF files are allowed";
        }
        
        if ($_FILES['aadhaar_image']['size'] > 2 * 1024 * 1024) { // 2MB limit
            $errors[] = "File size must be less than 2MB";
        }
    }

    if (!empty($errors)) {
        die(implode("<br>", $errors));
    }

    // Process file upload
    $aadhaar_image = file_get_contents($_FILES['aadhaar_image']['tmp_name']);

    // Sanitize inputs
    $full_name = htmlspecialchars($_POST['fullName']);
    $age = intval($_POST['age']);
    $phone = htmlspecialchars($_POST['phone']);
    $aadhaar = htmlspecialchars($_POST['aadhaar']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    $gender = htmlspecialchars($_POST['gender']);

    // Validate password match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Email already exists!");
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, age, phone, aadhaar, email, password, gender, aadhaar_image) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssssb", $full_name, $age, $phone, $aadhaar, $email, $hashed_password, $gender, $aadhaar_image);

    // Need to send blob data separately
    $stmt->send_long_data(7, $aadhaar_image);

    if ($stmt->execute()) {
        header("Location: login.php?signup=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Booking Portal - Sign Up</title>
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

        .signup-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 450px;
            margin: 1.5rem 0;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo {
            width: 100px;
            margin-bottom: 0.8rem;
        }

        h1 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 0.8rem;
        }

        .input-field {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #3498db;
        }

        .gender-group {
            display: flex;
            gap: 1rem;
            margin: 0.8rem 0;
        }

        .gender-option {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
        }

        .gender-option input[type="radio"] {
            accent-color: #3498db;
        }

        .signup-btn {
            width: 100%;
            padding: 10px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 0.8rem;
        }

        .signup-btn:hover {
            background: #219653;
        }

        .login-link {
            text-align: center;
            margin-top: 1.2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .signup-container {
                padding: 1.2rem;
                max-width: 380px;
            }
            
            .input-field {
                padding: 8px 10px;
                font-size: 0.9rem;
            }
            
            h1 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo-section">
            <img src="https://i.pinimg.com/736x/e3/ad/91/e3ad91f2d6a47a91418098d735aa7030.jpg" 
                 alt="College Logo" 
                 class="logo">
            <h1>Create New Account</h1>
        </div>

        <form id="signupForm" method="POST" enctype="multipart/form-data">
            <!-- Existing form fields remain the same -->
            <div class="form-group">
                <input type="text" 
                       id="fullName" 
                       name="fullName"
                       class="input-field" 
                       placeholder="Full Name" 
                       required>
            </div>

            <div class="form-group">
                <input type="number" 
                       id="age" 
                       name="age"
                       class="input-field" 
                       placeholder="Age" 
                       min="12" 
                       max="100" 
                       required>
            </div>

            <div class="form-group">
                <input type="tel" 
                       id="phone"
                       name="phone" 
                       class="input-field" 
                       placeholder="Phone Number" 
                       pattern="[0-9]{10}" 
                       required>
            </div>

            <div class="form-group">
                <input type="text" 
                       id="aadhaar"
                       name="aadhaar" 
                       class="input-field" 
                       placeholder="Aadhaar Number" 
                       pattern="[0-9]{12}" 
                       required>
            </div>

            <!-- New Aadhaar Image Upload Field -->
            <div class="form-group">
                <label for="aadhaar_image">Upload Aadhaar Card (Image)</label>
                <input type="file" 
                       id="aadhaar_image"
                       name="aadhaar_image" 
                       class="input-field"
                       accept="image/*"
                       required>
            </div>

            <div class="form-group">
                <input type="email" 
                       id="email" 
                       name="email"
                       class="input-field" 
                       placeholder="Email Address" 
                       required>
            </div>

            <div class="form-group">
                <input type="password" 
                       id="password"
                       name="password" 
                       class="input-field" 
                       placeholder="Create Password" 
                       required>
            </div>

            <div class="form-group">
                <input type="password" 
                       id="confirmPassword" 
                       name="confirmPassword"
                       class="input-field" 
                       placeholder="Confirm Password" 
                       required>
            </div>

            <div class="gender-group">
                <label class="gender-option">
                    <input type="radio" name="gender" value="male" required> Male
                </label>
                <label class="gender-option">
                    <input type="radio" name="gender" value="female"> Female
                </label>
                <label class="gender-option">
                    <input type="radio" name="gender" value="other"> Other
                </label>
            </div>

            <button type="submit" class="signup-btn">Create Account</button>

            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>