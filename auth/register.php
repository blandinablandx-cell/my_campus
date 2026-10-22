<?php
session_start();
include '../includes/db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $age = intval($_POST['age']);
    $nationality = trim($_POST['nationality']);
    $education_level = trim($_POST['education_level']);
    $course = trim($_POST['course']);
    
    // Hifadhi nenosiri kama Plain Text ili liweze kusomeka vizuri na Admin
    $password = trim($_POST['password']);
    
    $role = trim($_POST['role']);
    $user_answer = trim($_POST['captcha_answer'] ?? '');

    // 1. Verify Math Captcha
    if (!isset($_SESSION['math_captcha']) || $user_answer != $_SESSION['math_captcha']) {
        $error = "Incorrect security verification answer. Please try again.";
    } else {
        // 2. Check if username or email already exists
        $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $error = "Username or Email already exists. Please choose another one.";
        } else {
            // 3. Set initial status ('Pending' kwa wanafunzi ili kuleta Notification kwa Admin, 'Confirmed' kwa wengine)
            $status = ($role == 'student') ? 'Pending' : 'Confirmed';

            // 4. Insert user into database (Bila RegNo mwanzo ili Admin aweze kum-assign kupitia add_user.php)
            $sql = "INSERT INTO users (fullname, username, email, age, nationality, education_level, course, password, role, status) 
                    VALUES ('$fullname', '$username', '$email', $age, '$nationality', '$education_level', '$course', '$password', '$role', '$status')";

            if ($conn->query($sql) === TRUE) {
                $success = "Registration successful! Your account is pending review by the administrator. You can <a href='login.php'>Login here</a> once approved.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

// Generate new random numbers for Captcha
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$_SESSION['math_captcha'] = $num1 + $num2;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Campus</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(rgba(13, 27, 42, 0.6), rgba(13, 27, 42, 0.6)), url('../assets/images/banner1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 40px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }
        .home-tab {
            position: absolute;
            top: 25px;
            left: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #0d1b2a;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .home-tab:hover {
            background: #00b4d8;
            color: white;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0,0,0,0.2);
            width: 450px;
            backdrop-filter: blur(5px);
            margin-top: 20px;
        }
        h2 {
            color: #0d1b2a;
            margin-top: 0;
            text-align: center;
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #f8fafc;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #00b4d8;
            outline: none;
            background-color: #fff;
        }
        .register-btn {
            width: 100%;
            background-color: #0d1b2a;
            color: white;
            border: none;
            padding: 12px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .register-btn:hover {
            background-color: #00b4d8;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 13px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 13px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #475569;
        }
        .login-link a {
            color: #00b4d8;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <a href="../index.php" class="home-tab">← Back to Home</a>

    <div class="register-container">
        <h2>Admissions & Register</h2>
        
        <?php 
        if($error != "") { echo "<div class='error'>$error</div>"; }
        if($success != "") { echo "<div class='success'>$success</div>"; }
        ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Choose a username">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your email address">
            </div>

            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" required placeholder="Enter your age">
            </div>

            <div class="form-group">
                <label>Nationality</label>
                <input type="text" name="nationality" required placeholder="Enter your nationality">
            </div>

            <div class="form-group">
                <label>Education Level</label>
                <select name="education_level" required>
                    <option value="">Select Education Level</option>
                    <option value="Form Six">Form Six / Advanced Certificate</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Bachelor">Bachelor Degree</option>
                </select>
            </div>

            <div class="form-group">
                <label>Course / Program</label>
                <select name="course" required>
                    <option value="">Select Course</option>
                    <option value="CS">Computer Science (CS)</option>
                    <option value="IT">Information Technology (IT)</option>
                    <option value="NET">Computer Networks & Security (NET)</option>
                    <option value="DE">Digital Electricity & Smart Systems (DE)</option>
                    <option value="ECE">Electrical & Computer Engineering (ECE)</option>
                    <option value="EN">Civil & Mechanical Engineering (EN)</option>
                    <option value="BA">Business Administration (BA)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Password (Plain Text)</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="student">Student</option>
                    <option value="lecturer">Lecturer</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="form-group">
                <label>Security Verification: <strong><?php echo "$num1 + $num2"; ?> = ?</strong></label>
                <input type="number" name="captcha_answer" required placeholder="Enter answer here">
            </div>

            <button type="submit" class="register-btn">Register Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

</body>
</html>