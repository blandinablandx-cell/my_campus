<?php
session_start();
include '../includes/db.php';

// Ensure only student can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT fullname, reg_no, email, course, status, expire_date FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Weka ujumbe wa tahadhari kama hazijajazwa
$reg_no = !empty($student['reg_no']) ? $student['reg_no'] : '<span style="color: #d90429; font-style: italic;">Not registered yet (Contact Admin)</span>';
$expire_date = !empty($student['expire_date']) ? $student['expire_date'] : '<span style="color: #64748b; font-style: italic;">Not specified</span>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Portal</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; color: #333; }
        .navbar { background-color: #ffffff; padding: 12px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .main-layout { display: flex; min-height: calc(100vh - 57px); }
        .sidebar { width: 260px; background-color: #1b263b; color: #cbd5e1; padding: 15px 0; flex-shrink: 0; }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: #cbd5e1; text-decoration: none; padding: 11px 20px; font-size: 13px; font-weight: 500; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background-color: #0d1b2a; color: #ffffff; border-left-color: #00b4d8; }
        .content { flex: 1; padding: 30px; background-color: #f8fafc; overflow-y: auto; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0px 2px 8px rgba(0,0,0,0.04); max-width: 700px; }
        h2 { color: #0d1b2a; margin-top: 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
        .profile-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        .profile-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; }
        .profile-table td:first-child { font-weight: 600; color: #475569; width: 35%; }
        .back-btn { display: inline-block; margin-top: 20px; background: #0d1b2a; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="navbar">
        <strong>My Campus Portal</strong>
        <span>Signed in as: <strong><?php echo htmlspecialchars($student['fullname'] ?? $username); ?></strong></span>
    </div>

    <div class="main-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="index.php">📊 Dashboard</a>
            <a href="my_profile.php" class="active">👤 My Profile</a>
            <a href="my_fee_status.php">💰 My Fee Status</a>
            <a href="my_marks.php">🏆 My Marks</a>
            <a href="my_performance.php">📈 My Performance</a>
            <a href="my_iptr.php">📋 My IPTR</a>
            <a href="my_assignments.php">📝 My Assignments</a>
            <a href="downloads.php">📥 My Downloads</a>
            <a href="hall_tickets.php">🎫 Hall Tickets</a>
            <a href="permissions.php">🚪 Permissions</a>
            <a href="../auth/logout.php" style="color: #d90429;">🚪 Logout</a>
        </div>

        <!-- Content Area -->
        <div class="content">
            <div class="card">
                <h2>My Student Profile</h2>
                <table class="profile-table">
                    <tr>
                        <td>Full Name</td>
                        <td><?php echo htmlspecialchars($student['fullname'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Registration Number</td>
                        <td><strong><?php echo $reg_no; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?php echo htmlspecialchars($username); ?></td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Course / Program</td>
                        <td><?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td>Account Status</td>
                        <td><span style="color: #16a34a; font-weight: bold;"><?php echo htmlspecialchars($student['status'] ?? 'Active'); ?></span></td>
                    </tr>
                    <tr>
                        <td>Studies Expiry Date</td>
                        <td><?php echo $expire_date; ?></td>
                    </tr>
                </table>
                <a href="index.php" class="back-btn">← Back to Dashboard</a>
            </div>
        </div>
    </div>

</body>
</html>