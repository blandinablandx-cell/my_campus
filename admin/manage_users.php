<?php
session_start();
include '../includes/db.php';

// Ensure only admin can access this page
if (!isset($_SESSION['role'] ) || strtolower(trim($_SESSION['role'])) != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle User Deletion
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $del_id");
    header("Location: manage_users.php?success=User deleted successfully!");
    exit();
}

// Fetch all users including reg_no and expire_date
$sql = "SELECT id, fullname, reg_no, username, email, password, role, course, status, expire_date FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Portal</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; }
        .header { background-color: #0d1b2a; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1400px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.05); }
        .top-action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h2 { color: #0d1b2a; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 13px; }
        th { background-color: #1b263b; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .back-btn { display: inline-block; margin-top: 20px; color: #00b4d8; text-decoration: none; font-weight: 600; }
        .confirm-btn { background-color: #2b9348; color: white; padding: 5px 8px; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; margin-right: 4px; margin-bottom: 4px; }
        .pwd-btn { background-color: #ffb703; color: #000; padding: 5px 8px; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; margin-right: 4px; margin-bottom: 4px; }
        .exp-btn { background-color: #7209b7; color: #fff; padding: 5px 8px; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; margin-right: 4px; margin-bottom: 4px; }
        .exp-btn:hover { background-color: #560bad; }
        .delete-btn { background-color: #d90429; color: white; padding: 5px 8px; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; margin-bottom: 4px; }
        .add-user-btn { background-color: #00b4d8; color: white; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .status-confirmed { color: #2b9348; font-weight: bold; }
        .status-pending { color: #d90429; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .pwd-box { background: #e2e8f0; padding: 3px 6px; border-radius: 4px; font-family: monospace; font-size: 11px; }
        .reg-box { font-weight: bold; color: #1d3557; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Manage System Users</h2>
        <a href="index.php" style="color: white; text-decoration: none;">← Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="top-action-bar">
            <div>
                <h2>Registered Users List</h2>
                <p style="margin: 0; color: #64748b;">Manage students, RegNos, passwords in plain text, expiry dates, and statuses.</p>
            </div>
            <a href="add_user.php" class="add-user-btn">+ Add New User</a>
        </div>

        <?php 
        if (isset($_GET['success'])) {
            echo "<div class='alert-success'>" . htmlspecialchars($_GET['success']) . "</div>";
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Reg No</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status = $row['status'] ?? 'Pending';
                        
                        // Hakikisha nenosiri linaonekana kama plain text moja kwa moja kutoka database
                        $raw_password = $row['password'];

                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . htmlspecialchars($row['fullname']) . "</td>
                                <td><span class='reg-box'>" . (!empty($row['reg_no']) ? htmlspecialchars($row['reg_no']) : 'N/A') . "</span></td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td><span class='pwd-box'>" . htmlspecialchars($raw_password) . "</span></td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . (!empty($row['course']) ? htmlspecialchars($row['course']) : 'N/A') . "</td>
                                <td><strong>" . ucfirst($row['role']) . "</strong></td>
                                <td>";
                                
                        if ($row['role'] == 'student') {
                            if ($status == 'Confirmed') {
                                echo "<span class='status-confirmed'>Confirmed</span>";
                            } else {
                                echo "<span class='status-pending'>Pending</span>";
                            }
                        } else {
                            echo "<span style='color: #0d1b2a;'>Active</span>";
                        }

                        echo "</td>
                                <td>" . (!empty($row['expire_date']) ? $row['expire_date'] : 'N/A') . "</td>
                                <td>";
                        
                        if ($row['role'] == 'student' && $status != 'Confirmed') {
                            echo "<a href='confirm_student.php?id=" . $row['id'] . "' class='confirm-btn'>Confirm</a>";
                        }

                        echo "<a href='edit_password.php?id=" . $row['id'] . "' class='pwd-btn'>Change Pwd</a>";
                        echo "<a href='edit_expiry.php?id=" . $row['id'] . "' class='exp-btn'>Edit Exp</a>";
                        echo "<a href='manage_users.php?delete_id=" . $row['id'] . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";

                        echo "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' style='text-align:center;'>No users found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        <a href="index.php" class="back-btn">← Back to Dashboard</a>
    </div>

</body>
</html>