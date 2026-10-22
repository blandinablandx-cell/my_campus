<?php
session_start();
include '../includes/db.php';

// Hakikisha ni admin pekee anayeweza kuingia
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$error = "";
$success = "";

// Angalia kama ID ya mtumiaji imetumwa kupitia URL
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET['id']);

// Chukua taarifa za mtumiaji husika
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
if ($result->num_rows == 0) {
    header("Location: manage_users.php");
    exit();
}
$user = $result->fetch_assoc();

// Kama fomu imetumwa (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);

    if (!empty($new_password)) {
        // Sasisha nenosiri kwenye database (Unaweza kuweka au kutosimba kulingana na mfumo wako wa kawaida)
        $sql = "UPDATE users SET password = '$new_password' WHERE id = $user_id";

        if ($conn->query($sql) === TRUE) {
            $success = "Password changed successfully!";
            // Sasisha taarifa za mtumiaji ili kuonyesha nenosiri jipya kwenye fomu
            $user['password'] = $new_password;
        } else {
            $error = "Error updating password: " . $conn->error;
        }
    } else {
        $error = "Please enter a new password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Admin Portal</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; }
        .form-container { max-width: 450px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.05); margin: auto; }
        h2 { color: #0d1b2a; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #1b263b; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .btn { background: #00b4d8; color: white; border: none; padding: 11px; width: 100%; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #03045e; }
        .back-link { display: inline-block; margin-top: 15px; color: #0d1b2a; text-decoration: none; font-size: 13px; font-weight: 600; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Change Password</h2>
        <p style="font-size: 13px; color: #64748b;">Changing password for: <strong><?php echo htmlspecialchars($user['username']); ?></strong> (<?php echo htmlspecialchars($user['fullname']); ?>)</p>
        
        <?php if($error) echo "<div class='alert-error'>$error</div>"; ?>
        <?php if($success) echo "<div class='alert-success'>$success</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>New Password</label>
                <input type="text" name="new_password" required value="<?php echo htmlspecialchars($user['password']); ?>">
            </div>
            <button type="submit" class="btn">Update Password</button>
        </form>
        <a href="manage_users.php" class="back-link">← Back to Manage Users</a>
    </div>
</body>
</html>