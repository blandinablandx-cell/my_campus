<?php
session_start();
include '../includes/db.php';

// Hakikisha ni admin pekee anayeweza kuingia
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Hesabu takwimu za watumiaji ndani ya database
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_students = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
$total_lecturers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='lecturer'")->fetch_assoc()['count'];
$total_pending = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='student' AND (status = 'Pending' OR status IS NULL)")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - My Campus</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }
        .header {
            background-color: #0d1b2a;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #00b4d8;
        }
        .logout-btn {
            background-color: #d90429;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .welcome-card h2 {
            color: #0d1b2a;
            margin-top: 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
            border-left: 5px solid #00b4d8;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #64748b;
            font-size: 14px;
        }
        .stat-card p {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
            color: #0d1b2a;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .menu-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s, box-shadow 0.3s;
            display: block;
        }
        .menu-card:hover {
            transform: translateY(-4px);
            box-shadow: 0px 6px 15px rgba(0,0,0,0.08);
        }
        .menu-card h3 {
            color: #0d1b2a;
            margin-top: 0;
        }
        .menu-card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>My Campus Admin Portal</h1>
        <div>
            <span style="margin-right: 20px; font-size: 14px;">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        
        <div class="welcome-card">
            <h2>Administrator Dashboard</h2>
            <p>Welcome to the management control center. From here you can manage students, lecturers, course admissions, and system configurations.</p>
        </div>

        <!-- Quick Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card" style="border-left-color: #2b9348;">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>
            <div class="stat-card" style="border-left-color: #ffb703;">
                <h3>Total Lecturers</h3>
                <p><?php echo $total_lecturers; ?></p>
            </div>
            <div class="stat-card" style="border-left-color: #d90429;">
                <h3>Pending Admissions</h3>
                <p><?php echo $total_pending; ?></p>
            </div>
        </div>

        <!-- Navigation Action Cards -->
        <div class="menu-grid">
            <a href="manage_users.php" class="menu-card">
                <h3>Manage Users</h3>
                <p>View, confirm admissions, set expiry dates, or delete students and lecturers.</p>
            </a>
            <a href="add_user.php" class="menu-card">
                <h3>Add New User</h3>
                <p>Register new administrators, lecturers, or students into the system manually.</p>
            </a>
            <a href="../index.php" class="menu-card">
                <h3>View Main Website</h3>
                <p>Go back to the homepage of My Campus university portal.</p>
            </a>
        </div>

    </div>

</body>
</html>