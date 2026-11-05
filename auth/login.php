<?php
error_reporting(E_ALL);
_set('display_errors', 1);
session_start();
include '../includes/db.php';
$error = "";

// AUTOMATIC TABLE FIX: Ensure the 'users' table exists on the Render Cloud Database
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($table_check->num_rows == 0) {
    // If it doesn't exist, create it automatically and insert a default admin account
    $create_table = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL,
        fullname VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($create_table)) {
        $default_pass = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->query("INSERT IGNORE INTO users (username, password, role, fullname) VALUES ('admin@campus.com', '$default_pass', 'Admin', 'System Administrator')");
    }
}

// Generate Math Captcha numbers on first load or if not set in session
if (!isset($_SESSION['math_captcha'])) {
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['math_captcha_num1'] = $num1;
    $_SESSION['math_captcha_num2'] = $num2;
    $_SESSION['math_captcha'] = $num1 + $num2;
} else {
    $num1 = $_SESSION['math_captcha_num1'];
    $num2 = $_SESSION['math_captcha_num2'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $selected_role = trim($_POST['role']);
    $user_answer = trim($_POST['captcha_answer'] ?? '');

    // 1. Verify Math Captcha
    if ($user_answer != $_SESSION['math_captcha']) {
        $error = "Incorrect security verification answer. Please try again.";
    } else {
        // Clear used captcha
        unset($_SESSION['math_captcha']);

        // 2. Look up user in the database by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // 3. Verify password (supports plain text or hashed passwords)
            $db_password = $user['password'];
            if ($password === $db_password || password_verify($password, $db_password)) {
                
                // 4. Verify matching roles
                $db_role = strtolower(trim($user['role'] ?? ''));
                $selected_role_lower = strtolower(trim($selected_role));

                if ($db_role == $selected_role_lower) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['fullname'] = $user['fullname'] ?? $user['username'];

                    // 5. Redirect user securely based on their role using absolute paths
                    if ($db_role == 'admin') { 
                        header("Location: /my_campus/admin/index.php"); 
                        exit(); 
                    } elseif ($db_role == 'lecturer') {
                        header("Location: /my_campus/lecturer/index.php");
                        exit();
                    } else {
                        header("Location: /my_campus/student/index.php");
                        exit();
                    }
                } else {
                    $error = "Role mismatch! Database has: '" . $user['role'] . "' but you selected: '" . $selected_role . "'";
                }

            } else {
                $error = "Invalid password! Please check your credentials.";
            }
        } else {
            $error = "User not found! Username does not exist in the system.";
        }
        $stmt->close();
    }

    // Regenerate captcha in case of an error
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    $_SESSION['math_captcha_num1'] = $num1;
    $_SESSION['math_captcha_num2'] = $num2;
    $_SESSION['math_captcha'] = $num1 + $num2;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Campus</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(rgba(13, 27, 42, 0.7), rgba(13, 27, 42, 0.7)), url('../assets/images/banner1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
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
            z-index: 100;
        }
        .home-tab:hover {
            background: #00b4d8;
            color: white;
        }
        .login-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            z-index: 10;
            box-sizing: border-box;
            margin: 20px;
        }
        h2 {
            color: #0d1b2a;
            margin-top: 0;
            text-align: center;
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s;
            background-color: #f8fafc;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #00b4d8;
            outline: none;
            background-color: #fff;
        }
        .login-btn {
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
        .login-btn:hover {
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
    </style>
</head>
<body>

    <a href="../index.php" class="home-tab">Home</a>

    <div class="login-container">
        <h2>My Campus Login</h2>

        <?php if (!empty($error)) { echo "<div class='error'>$error</div>"; } ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Select Role</label>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Lecturer">Lecturer</option>
                    <option value="Student">Student</option>
                </select>
            </div>

            <div class="form-group">
                <label>Security Question: What is <?php echo $num1; ?> + <?php echo $num2; ?>?</label>
                <input type="text" name="captcha_answer" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>

</body>
</html>