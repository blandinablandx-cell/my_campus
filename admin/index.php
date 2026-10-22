<?php
session_start();
include '../includes/db.php';

// Hakikisha ni Admin pekee anayeweza kuingia
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$admin_success = "";
$admin_error = "";
$cur_success = "";
$cur_error = "";

// 1. Assign Lecturer Duties
if (isset($_POST['assign_lecturer'])) {
    $lecturer_id = $_POST['lecturer_id'];
    $course_name = trim($_POST['course_name']);
    $module_name = trim($_POST['module_name']);
    $target_group = trim($_POST['target_group']);

    if (!empty($lecturer_id) && !empty($course_name) && !empty($module_name) && !empty($target_group)) {
        $stmt = $conn->prepare("INSERT INTO lecturer_assignments (lecturer_id, course_name, module_name, target_group) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $lecturer_id, $course_name, $module_name, $target_group);
        
        if ($stmt->execute()) {
            $admin_success = "Module and duties assigned successfully to the lecturer!";
        } else {
            $admin_error = "Error assigning duties: " . $conn->error;
        }
    } else {
        $admin_error = "Please fill in all fields!";
    }
}

// 2. Add Curriculum Subject
if (isset($_POST['add_curriculum'])) {
    $academic_year = trim($_POST['academic_year']);
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $semester = trim($_POST['semester']);
    $program_name = trim($_POST['program_name']);

    if (!empty($academic_year) && !empty($course_code) && !empty($course_name) && !empty($semester) && !empty($program_name)) {
        $stmt_c = $conn->prepare("INSERT INTO curriculums (academic_year, course_code, course_name, semester, program_name) VALUES (?, ?, ?, ?, ?)");
        $stmt_c->bind_param("sssss", $academic_year, $course_code, $course_name, $semester, $program_name);
        
        if ($stmt_c->execute()) {
            $cur_success = "Curriculum course added successfully!";
        } else {
            $cur_error = "Error adding curriculum: " . $conn->error;
        }
    } else {
        $cur_error = "Please fill in all curriculum fields!";
    }
}

// 3. Update Student Fee Breakdown & Hall Ticket Permit
if (isset($_POST['update_permit'])) {
    $student_id = intval($_POST['student_id']);
    $paid_amount = floatval($_POST['paid_amount']);
    $total_fee = floatval($_POST['total_fee']);
    $ticket_access = intval($_POST['hall_ticket_access']);

    if ($paid_amount >= $total_fee) {
        $fee_status = 'Paid';
    } else {
        $fee_status = 'Pending Payment';
    }

    if ($paid_amount < $total_fee && $ticket_access == 1) {
        $admin_error = "Condition Error: Student cannot be granted Hall Ticket access unless their paid amount covers the total fee!";
    } else {
        $stmt_p = $conn->prepare("UPDATE users SET paid_amount = ?, total_fee = ?, fee_status = ?, hall_ticket_access = ? WHERE id = ? AND role = 'student'");
        $stmt_p->bind_param("ddsii", $paid_amount, $total_fee, $fee_status, $ticket_access, $student_id);
        if ($stmt_p->execute()) {
            $admin_success = "Student financial breakdown and Hall Ticket permit updated successfully!";
        } else {
            $admin_error = "Error updating permit: " . $conn->error;
        }
    }
}

// Deletions
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM lecturer_assignments WHERE id = $del_id");
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete_cur_id'])) {
    $del_cur = intval($_GET['delete_cur_id']);
    $conn->query("DELETE FROM curriculums WHERE id = $del_cur");
    header("Location: index.php");
    exit();
}

// Fetch Lists
$lecturers_list = $conn->query("SELECT id, fullname FROM users WHERE role = 'lecturer'");
$assignments_list = $conn->query("SELECT la.*, u.fullname FROM lecturer_assignments la JOIN users u ON la.lecturer_id = u.id ORDER BY la.id DESC");
$curriculums_list = $conn->query("SELECT * FROM curriculums ORDER BY FIELD(academic_year, 'Year I', 'Year II', 'Year III', 'Year IV', 'Year V'), semester ASC");
$programs_list = $conn->query("SELECT * FROM programs");
$subjects_list = $conn->query("SELECT DISTINCT course_name, course_code FROM curriculums");
$students_list = $conn->query("SELECT id, fullname, reg_no, fee_status, paid_amount, total_fee, hall_ticket_access FROM users WHERE role = 'student'");
$pending_fees_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'student' AND (fee_status = 'Pending Payment' OR fee_status = 'Pending')")->fetch_assoc()['total'];

// Hesabu wanafunzi wapya waliojisajili na wanasubiri kuongezwa RegNo
$pending_registrations_count = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'student' AND (reg_no IS NULL OR reg_no = '')")->fetch_assoc()['total'];

// Fetch Activity Logs classified by role
$student_logs = $conn->query("SELECT * FROM activity_logs WHERE role = 'student' ORDER BY id DESC");
$lecturer_logs = $conn->query("SELECT * FROM activity_logs WHERE role = 'lecturer' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Campus - Admin Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; color: #333; }
        .navbar { background-color: #0d1b2a; color: #ffffff; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 22px; color: #00b4d8; }
        .nav-links { display: flex; gap: 15px; align-items: center; }
        .nav-links a { color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; padding: 6px 12px; border-radius: 4px; transition: background 0.3s; }
        .nav-links a:hover, .nav-links a.active-link { background-color: #00b4d8; }
        .banner-container { position: relative; max-width: 100%; height: 350px; overflow: hidden; background-color: #0d1b2a; }
        .slide { position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: white; padding: 0 20px; box-sizing: border-box; background-size: cover; background-position: center; }
        .slide.active { opacity: 1; }
        .slide-content { position: relative; z-index: 2; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); }
        .slide h2 { font-size: 36px; margin-bottom: 10px; }
        .slide p { font-size: 16px; margin-bottom: 20px; color: #f8f9fa; }
        .btn { background-color: #00b4d8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; border: none; cursor: pointer; }
        .btn:hover { background-color: #03045e; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; display: flex; gap: 20px; flex-wrap: wrap; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.05); flex: 1; min-width: 350px; margin-bottom: 20px; }
        .card h3 { color: #0d1b2a; margin-top: 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px; font-size: 18px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 13px; color: #0d1b2a; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        .alert-success { background-color: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; font-weight: 500; }
        .alert-error { background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; font-weight: 500; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table th { background-color: #f8fafc; color: #0d1b2a; font-weight: 600; }
        .action-delete { color: #e63946; text-decoration: none; font-weight: 600; }
        .action-delete:hover { text-decoration: underline; }
        footer { background-color: #0d1b2a; color: white; text-align: center; padding: 20px; margin-top: 40px; font-size: 13px; }
    </style>
</head>
<body>

    <!-- Navigation Bar with Tabs -->
    <div class="navbar">
        <h1>My Campus - Admin</h1>
        <div class="nav-links">
            <a href="index.php" class="active-link">Dashboard</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="add_user.php" style="background-color: #3b82f6;">Add User 
                <?php if($pending_registrations_count > 0) echo "<span style='background: #ef4444; color: white; padding: 2px 6px; border-radius: 50%; font-size: 11px;'>$pending_registrations_count</span>"; ?>
            </a>
            <a href="#permit-section" style="background-color: #2a9d8f;">Permit 
                <?php if($pending_fees_count > 0) echo "<span style='background: #e63946; color: white; padding: 2px 6px; border-radius: 50%; font-size: 11px;'>$pending_fees_count</span>"; ?>
            </a>
            <a href="#logs-section" style="background-color: #f77f00;">Activity Logs</a>
            <a href="../auth/logout.php" style="background: #d90429;">Logout</a>
        </div>
    </div>

    <!-- Banner Slider -->
    <div class="banner-container">
        <div class="slide active" style="background-image: url('../assets/images/banner1.jpg');">
            <div class="slide-content">
                <h2>Admin Management Portal</h2>
                <p>Oversee user accounts, curriculums, and institutional records efficiently.</p>
                <a href="manage_users.php" class="btn">Manage Users</a>
            </div>
        </div>
        <div class="slide" style="background-image: url('../assets/images/banner2.jpg');">
            <div class="slide-content">
                <h2>System Audit & Activity Logs</h2>
                <p>Monitor student and lecturer actions in real-time across the entire campus system.</p>
                <a href="#logs-section" class="btn">View Logs</a>
            </div>
        </div>
    </div>

    <!-- Notification Alert kwa New Student Registrations -->
    <?php if ($pending_registrations_count > 0): ?>
    <div style="max-width: 1160px; margin: 25px auto 0 auto; padding: 0 20px;">
        <div style="background-color: #dbeafe; color: #1e40af; padding: 15px 20px; border-radius: 8px; border: 1px solid #bfdbfe; display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
            <div>
                <strong>🔔 New Student Alert:</strong> Kuna <strong><?php echo $pending_registrations_count; ?></strong> mwanafunzi mpya aliyejisajili na anasubiri kuongezewa namba ya usajili (RegNo).
            </div>
            <a href="add_user.php" class="btn" style="padding: 6px 14px; font-size: 12px; background: #2563eb; text-decoration: none; color: white;">Nenda Kamwongeze</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Notification Alert kwa Fees & Permit -->
    <?php if ($pending_fees_count > 0): ?>
    <div style="max-width: 1160px; margin: 20px auto 0 auto; padding: 0 20px;">
        <div style="background-color: #fff3cd; color: #856404; padding: 15px 20px; border-radius: 8px; border: 1px solid #ffeeba; display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
            <div>
                <strong>🔔 Fee Verification Alert:</strong> There are <strong><?php echo $pending_fees_count; ?></strong> student(s) waiting for fee verification and Hall Ticket confirmation.
            </div>
            <a href="#permit-section" class="btn" style="padding: 6px 14px; font-size: 12px; background: #856404; text-decoration: none;">Review Permit</a>
        </div>
    </div>
    <?php endif; ?>

    <?php if($admin_success) echo "<div style='max-width:1160px; margin: 20px auto 0 auto; padding: 0 20px;'><div class='alert-success'>$admin_success</div></div>"; ?>
    <?php if($admin_error) echo "<div style='max-width:1160px; margin: 20px auto 0 auto; padding: 0 20px;'><div class='alert-error'>$admin_error</div></div>"; ?>

    <!-- Main Features Container -->
    <div class="container">
        <!-- 1. Assign Lecturer Form -->
        <div class="card">
            <h3>🎓 Assign Course & Module to Lecturer</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Select Lecturer *</label>
                    <select name="lecturer_id" required>
                        <option value="">-- Choose Lecturer --</option>
                        <?php 
                        if ($lecturers_list && $lecturers_list->num_rows > 0) {
                            $lecturers_list->data_seek(0);
                            while($lec = $lecturers_list->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $lec['id']; ?>"><?php echo htmlspecialchars($lec['fullname']); ?></option>
                        <?php endwhile; } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Select Program Name *</label>
                    <select name="course_name" required>
                        <option value="">-- Choose Program --</option>
                        <?php 
                        if ($programs_list && $programs_list->num_rows > 0) {
                            $programs_list->data_seek(0);
                            while($prog = $programs_list->fetch_assoc()): 
                        ?>
                            <option value="<?php echo htmlspecialchars($prog['program_name']); ?>"><?php echo htmlspecialchars($prog['program_name'] . " (" . $prog['program_code'] . ")"); ?></option>
                        <?php endwhile; } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Select Module / Subject Name *</label>
                    <select name="module_name" required>
                        <option value="">-- Choose Module --</option>
                        <?php 
                        if ($subjects_list && $subjects_list->num_rows > 0) {
                            $subjects_list->data_seek(0);
                            while($sub = $subjects_list->fetch_assoc()): 
                        ?>
                            <option value="<?php echo htmlspecialchars($sub['course_name']); ?>"><?php echo htmlspecialchars($sub['course_code'] . ' - ' . $sub['course_name']); ?></option>
                        <?php endwhile; } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Target Group / Students *</label>
                    <select name="target_group" required>
                        <option value="">-- Choose Target Group --</option>
                        <option value="Year I Students">Year I Students</option>
                        <option value="Year II Students">Year II Students</option>
                        <option value="Year III Students">Year III Students</option>
                        <option value="Year IV Students">Year IV Students</option>
                    </select>
                </div>

                <button type="submit" name="assign_lecturer" class="btn">Assign Duties</button>
            </form>
        </div>

        <!-- 2. Add Curriculum Subject Form -->
        <div class="card" id="curriculum-section">
            <h3>📚 Add Curriculum Subject (Year I - Final Year)</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Select Program Name *</label>
                    <select name="program_name" required>
                        <option value="">-- Choose Program & Abbreviation --</option>
                        <?php 
                        if ($programs_list && $programs_list->num_rows > 0) {
                            $programs_list->data_seek(0);
                            while($prog = $programs_list->fetch_assoc()): 
                        ?>
                            <option value="<?php echo htmlspecialchars($prog['program_name']); ?>"><?php echo htmlspecialchars($prog['program_name'] . " (" . $prog['program_code'] . ")"); ?></option>
                        <?php endwhile; } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Academic Year *</label>
                    <select name="academic_year" required>
                        <option value="">-- Select Academic Year --</option>
                        <option value="Year I">Year I</option>
                        <option value="Year II">Year II</option>
                        <option value="Year III">Year III</option>
                        <option value="Year IV">Year IV</option>
                        <option value="Year V">Year V</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semester *</label>
                    <select name="semester" required>
                        <option value="">-- Select Semester --</option>
                        <option value="Semester I">Semester I</option>
                        <option value="Semester II">Semester II</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Course Code (Abbreviation) *</label>
                    <input type="text" name="course_code" required placeholder="e.g. ISNE 111">
                </div>
                <div class="form-group">
                    <label>Course / Subject Name *</label>
                    <input type="text" name="course_name" required placeholder="e.g. Introduction to Programming">
                </div>
                <button type="submit" name="add_curriculum" class="btn" style="background-color: #2a9d8f;">Save Curriculum</button>
            </form>
        </div>
    </div>

    <!-- PERMIT TAB SECTION -->
    <div class="container" style="flex-direction: column;" id="permit-section">
        <div class="card" style="width: 100%; box-sizing: border-box;">
            <h3>🛡️ Permit & Fee Breakdown Management</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Reg Number</th>
                        <th>Total Fee (TZS)</th>
                        <th>Paid Amount (TZS)</th>
                        <th>Balance / Remainder</th>
                        <th>Hall Ticket Permit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $p_counter = 1;
                    if ($students_list && $students_list->num_rows > 0) {
                        while($std = $students_list->fetch_assoc()) {
                            $total = floatval($std['total_fee']);
                            $paid = floatval($std['paid_amount']);
                            $balance = $total - $paid;
                    ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="student_id" value="<?php echo $std['id']; ?>">
                                <td><?php echo $p_counter++; ?></td>
                                <td><strong><?php echo htmlspecialchars($std['fullname']); ?></strong></td>
                                <td><?php echo htmlspecialchars($std['reg_no']); ?></td>
                                <td><input type="number" step="100" name="total_fee" value="<?php echo $total; ?>" style="width: 110px; padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px;"></td>
                                <td><input type="number" step="100" name="paid_amount" value="<?php echo $paid; ?>" style="width: 110px; padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px;"></td>
                                <td><strong style="color: <?php echo ($balance > 0) ? '#e63946' : '#2a9d8f'; ?>;"><?php echo number_format($balance, 2); ?> TZS</strong></td>
                                <td>
                                    <select name="hall_ticket_access" style="padding: 6px; border-radius: 4px; border: 1px solid #cbd5e1;">
                                        <option value="0" <?php if($std['hall_ticket_access'] == 0) echo 'selected'; ?>>Denied (Locked)</option>
                                        <option value="1" <?php if($std['hall_ticket_access'] == 1) echo 'selected'; ?>>Granted (Can Download)</option>
                                    </select>
                                </td>
                                <td><button type="submit" name="update_permit" class="btn" style="padding: 6px 12px; font-size: 12px; background-color: #2a9d8f;">Save</button></td>
                            </form>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center; color: #666;'>No registered students found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTIVITY LOGS SECTION (Classified into Students and Lecturers) -->
    <div class="container" style="flex-direction: column;" id="logs-section">
        <div class="card" style="width: 100%; box-sizing: border-box;">
            <h3>📊 System Activity Logs (Auditing)</h3>
            <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">
                Review all actions performed across the system, classified separately for Students and Lecturers.
            </p>

            <!-- Students Logs Subsection -->
            <h4 style="color: #0d1b2a; border-left: 4px solid #00b4d8; padding-left: 10px; margin-top: 10px;">🎓 Students Actions & Activities</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Action Description</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $s_log_count = 1;
                    if ($student_logs && $student_logs->num_rows > 0) {
                        while($slog = $student_logs->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $s_log_count++ . "</td>";
                            echo "<td><strong>" . htmlspecialchars($slog['fullname']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($slog['action_description']) . "</td>";
                            echo "<td>" . htmlspecialchars($slog['created_at']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align: center; color: #666;'>No student activities recorded yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Lecturers Logs Subsection -->
            <h4 style="color: #0d1b2a; border-left: 4px solid #f77f00; padding-left: 10px; margin-top: 35px;">👨‍🏫 Lecturers Actions & Activities</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lecturer Name</th>
                        <th>Action Description</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $l_log_count = 1;
                    if ($lecturer_logs && $lecturer_logs->num_rows > 0) {
                        while($llog = $lecturer_logs->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $l_log_count++ . "</td>";
                            echo "<td><strong>" . htmlspecialchars($llog['fullname']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($llog['action_description']) . "</td>";
                            echo "<td>" . htmlspecialchars($llog['created_at']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align: center; color: #666;'>No lecturer activities recorded yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Curriculum & Assignments Tables Section -->
    <div class="container" style="flex-direction: column;">
        <div class="card" style="width: 100%; box-sizing: border-box;">
            <h3>📖 University Curriculum Overview (Year I to Final Year)</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Program</th>
                        <th>Academic Year</th>
                        <th>Semester</th>
                        <th>Course Code</th>
                        <th>Subject Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $c_counter = 1;
                    if ($curriculums_list && $curriculums_list->num_rows > 0) {
                        while($crow = $curriculums_list->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $c_counter++ . "</td>";
                            echo "<td>" . htmlspecialchars($crow['program_name']) . "</td>";
                            echo "<td><strong>" . htmlspecialchars($crow['academic_year']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($crow['semester']) . "</td>";
                            echo "<td>" . htmlspecialchars($crow['course_code']) . "</td>";
                            echo "<td>" . htmlspecialchars($crow['course_name']) . "</td>";
                            echo "<td><a href='index.php?delete_cur_id=" . $crow['id'] . "' class='action-delete' onclick='return confirm(\"Are you sure you want to delete this course from curriculum?\")'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center; color: #666;'>No curriculum records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="card" style="width: 100%; box-sizing: border-box;">
            <h3>📋 Assigned Modules & Lecturer Duties</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lecturer</th>
                        <th>Course</th>
                        <th>Module</th>
                        <th>Students / Group</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1;
                    if ($assignments_list && $assignments_list->num_rows > 0) {
                        while($row = $assignments_list->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $counter++ . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['fullname']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['module_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['target_group']) . "</td>";
                            echo "<td><a href='index.php?delete_id=" . $row['id'] . "' class='action-delete' onclick='return confirm(\"Are you sure you want to delete this assignment?\")'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: #666;'>No lecturer assignments found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 My Campus University System. All rights reserved.</p>
    </footer>

    <script>
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;
        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        setInterval(nextSlide, 4000);
    </script>
</body>
</html>