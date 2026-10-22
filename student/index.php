<?php
session_start();
include '../includes/db.php';

// Ensure only student can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

// Chota taarifa sahihi za mwanafunzi aliyelogin kutoka kwenye database yako
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT fullname, reg_no, email, course, status, expire_date FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$fullname = $student['fullname'] ?? $username;
$regno = !empty($student['reg_no']) ? $student['reg_no'] : 'Not Assigned';
$course = $student['course'] ?? 'Not Specified';
$status = $student['status'] ?? 'Active';
$email = $student['email'] ?? 'N/A';
$expire_date = !empty($student['expire_date']) ? $student['expire_date'] : 'Not specified';

// Pata ukurasa unaotakiwa kuonekana kupitia URL (Default ni dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - My Campus</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        /* Top Navbar */
        .navbar {
            background-color: #ffffff;
            color: #0d1b2a;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar .brand {
            font-weight: bold;
            font-size: 18px;
            color: #0d1b2a;
        }
        .user-profile-top {
            font-weight: 600;
            font-size: 14px;
            color: #1b263b;
        }
        
        /* Main Layout */
        .main-layout {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: #1b263b;
            color: #cbd5e1;
            padding: 15px 0;
            overflow-y: auto;
            max-height: calc(100vh - 60px);
            position: sticky;
            top: 60px;
            flex-shrink: 0;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 11px 20px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #0d1b2a;
            color: #ffffff;
            border-left-color: #00b4d8;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 25px;
            background-color: #f8fafc;
        }

        /* Welcome Banner Card */
        .welcome-card {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 20px;
        }
        .welcome-card h2 {
            color: #0d1b2a;
            margin-top: 0;
            font-size: 22px;
        }
        .welcome-card p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 0;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.04);
            text-align: center;
        }
        .stat-card h4 {
            margin: 0 0 10px 0;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
        }
        .stat-card .value {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Info Section & Tables */
        .grid-two {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        @media (max-width: 900px) {
            .grid-two { grid-template-columns: 1fr; }
        }
        .card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 20px;
        }
        .card h3 {
            margin-top: 0;
            font-size: 15px;
            color: #0d1b2a;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }
        .info-table, .standard-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .info-table td, .standard-table td, .standard-table th {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #475569;
            width: 38%;
        }
        .standard-table th {
            background-color: #1b263b;
            color: white;
            text-align: left;
        }
        .notice-box {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 12px 15px;
            font-size: 13px;
            color: #166534;
            border-radius: 4px;
        }
        .logout-link {
            color: #d90429 !important;
        }
        .logout-link:hover {
            background-color: #fee2e2 !important;
            border-left-color: #d90429 !important;
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <div class="navbar">
        <div class="brand">
            <span>My Campus Portal</span>
        </div>
        <div class="user-profile-top">
            <span>Signed in as: <strong><?php echo htmlspecialchars($fullname); ?></strong></span>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Sidebar Navigation using GET parameters -->
        <div class="sidebar">
            <a href="index.php?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">📊 Dashboard</a>
            <a href="index.php?page=profile" class="<?php echo ($page == 'profile') ? 'active' : ''; ?>">👤 My Profile</a>
            <a href="index.php?page=fees" class="<?php echo ($page == 'fees') ? 'active' : ''; ?>">💰 My Fee Status</a>
            <a href="index.php?page=marks" class="<?php echo ($page == 'marks') ? 'active' : ''; ?>">🏆 My Marks</a>
            <a href="index.php?page=performance" class="<?php echo ($page == 'performance') ? 'active' : ''; ?>">📈 My Performance</a>
            <a href="index.php?page=iptr" class="<?php echo ($page == 'iptr') ? 'active' : ''; ?>">📋 My IPTR</a>
            <a href="index.php?page=assignments" class="<?php echo ($page == 'assignments') ? 'active' : ''; ?>">📝 My Assignments</a>
            <a href="index.php?page=downloads" class="<?php echo ($page == 'downloads') ? 'active' : ''; ?>">📥 My Downloads</a>
            <a href="index.php?page=tickets" class="<?php echo ($page == 'tickets') ? 'active' : ''; ?>">🎫 Hall Tickets</a>
            <a href="index.php?page=permissions" class="<?php echo ($page == 'permissions') ? 'active' : ''; ?>">🚪 Permissions</a>
            <a href="../auth/logout.php" class="logout-link">🚪 Logout</a>
        </div>

        <!-- Dynamic Content Area -->
        <div class="content-area">
            <?php 
            switch ($page) {
                case 'profile':
                    ?>
                    <div class="card" style="max-width: 800px;">
                        <h3>My Student Profile</h3>
                        <table class="info-table">
                            <tr><td>Full Name</td><td><?php echo htmlspecialchars($fullname); ?></td></tr>
                            <tr><td>Registration Number</td><td><strong><?php echo htmlspecialchars($regno); ?></strong></td></tr>
                            <tr><td>Username</td><td><?php echo htmlspecialchars($username); ?></td></tr>
                            <tr><td>Email Address</td><td><?php echo htmlspecialchars($email); ?></td></tr>
                            <tr><td>Course / Program</td><td><?php echo htmlspecialchars($course); ?></td></tr>
                            <tr><td>Account Status</td><td><span style="color: #16a34a; font-weight: bold;"><?php echo htmlspecialchars($status); ?></span></td></tr>
                            <tr><td>Studies Expiry Date</td><td><?php echo htmlspecialchars($expire_date); ?></td></tr>
                        </table>
                    </div>
                    <?php
                    break;

                case 'fees':
                    ?>
                    <div class="card">
                        <h3>My Fee Payment Status</h3>
                        <table class="standard-table">
                            <tr><th>Description</th><th>Details</th></tr>
                            <tr><td>Student Name</td><td><?php echo htmlspecialchars($fullname); ?></td></tr>
                            <tr><td>Registration No</td><td><?php echo htmlspecialchars($regno); ?></td></tr>
                            <tr><td>Total Fee Due</td><td><strong style="color: #d90429;">0.00 TZS</strong></td></tr>
                            <tr><td>Payment Status</td><td><span style="color: #16a34a; font-weight: bold;">Up to Date</span></td></tr>
                        </table>
                    </div>
                    <?php
                    break;

                case 'marks':
                    ?>
                    <div class="card">
                        <h3>My Examination Marks</h3>
                        <p style="color: #64748b; font-size: 13px;">No examination results have been published for your account yet.</p>
                    </div>
                    <?php
                    break;

                case 'performance':
                    ?>
                    <div class="card">
                        <h3>My Academic Performance</h3>
                        <p style="color: #64748b; font-size: 13px;">Your GPA evaluations and term performances will appear here.</p>
                    </div>
                    <?php
                    break;

                case 'iptr':
                    ?>
                    <div class="card">
                        <h3>My IPTR (Individual Progress Tracking Report)</h3>
                        <p style="color: #64748b; font-size: 13px;">No progress tracking logs recorded at the moment.</p>
                    </div>
                    <?php
                    break;

                case 'assignments':
                    ?>
                    <div class="card">
                        <h3>My Assignments</h3>
                        <p style="color: #64748b; font-size: 13px;">You have 0 active assignments to submit.</p>
                    </div>
                    <?php
                    break;

                case 'downloads':
                    ?>
                    <div class="card">
                        <h3>My Downloads & Resources</h3>
                        <p style="color: #64748b; font-size: 13px;">No study materials or downloadable forms available currently.</p>
                    </div>
                    <?php
                    break;

                case 'tickets':
                    ?>
                    <div class="card">
                        <h3>Examination Hall Tickets</h3>
                        <p style="color: #64748b; font-size: 13px;">Hall tickets will be accessible prior to examination periods.</p>
                    </div>
                    <?php
                    break;

                case 'permissions':
                    ?>
                    <div class="card">
                        <h3>Leave / Permission Requests</h3>
                        <p style="color: #64748b; font-size: 13px;">You have not submitted any permission requests.</p>
                    </div>
                    <?php
                    break;

                default:
                    // Dashboard Default View
                    ?>
                    <div class="welcome-card">
                        <h2>Welcome back, <?php echo htmlspecialchars($fullname); ?>!</h2>
                        <p>Manage your academic profile, view examination results, monitor your course fees, and track your assignments securely.</p>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <h4>Program / Course</h4>
                            <div class="value" style="color: #00b4d8; font-size: 14px;"><?php echo htmlspecialchars($course); ?></div>
                        </div>
                        <div class="stat-card">
                            <h4>Registration No</h4>
                            <div class="value"><?php echo htmlspecialchars($regno); ?></div>
                        </div>
                        <div class="stat-card">
                            <h4>Account Status</h4>
                            <div class="value" style="color: #16a34a;"><?php echo htmlspecialchars($status); ?></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3>📢 NOTICE BOARD</h3>
                        <div class="notice-box">
                            <strong>System Notice:</strong> Welcome to My Campus portal. Please ensure you update your profile details and check your fee balances regularly.
                        </div>
                    </div>

                    <div class="grid-two">
                        <div class="card">
                            <h3>Student Information</h3>
                            <table class="info-table">
                                <tr><td>FULL NAME</td><td><?php echo htmlspecialchars($fullname); ?></td></tr>
                                <tr><td>REGISTRATION NO</td><td><?php echo htmlspecialchars($regno); ?></td></tr>
                                <tr><td>USERNAME</td><td><?php echo htmlspecialchars($username); ?></td></tr>
                                <tr><td>COURSE</td><td><?php echo htmlspecialchars($course); ?></td></tr>
                                <tr><td>STATUS</td><td><span style="color: #16a34a; font-weight: bold;"><?php echo htmlspecialchars($status); ?></span></td></tr>
                            </table>
                        </div>

                        <div class="card">
                            <h3>Quick Actions</h3>
                            <p style="font-size: 13px; color: #64748b; line-height: 1.5;">
                                You can use the sidebar links to quickly check your marks, review performance metrics, and submit or view your assignments.
                            </p>
                            <a href="index.php?page=marks" style="display: inline-block; background: #00b4d8; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; margin-top: 10px;">View My Marks →</a>
                        </div>
                    </div>
                    <?php
                    break;
            }
            ?>
        </div>
    </div>

</body>
</html>