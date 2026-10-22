<?php
session_start();
include '../includes/db.php';

// Hakikisha ni Lecturer pekee anayeweza kuingia
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'lecturer') {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];
$success = "";
$error = "";

// 1. Attendance Submission
if (isset($_POST['save_attendance'])) {
    $date = $_POST['date'];
    $module = trim($_POST['module']);
    $students_status = $_POST['status'] ?? [];

    if (!empty($date) && !empty($module)) {
        foreach ($students_status as $student_id => $status) {
            $stmt = $conn->prepare("INSERT INTO attendance (student_id, module, date, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $student_id, $module, $date, $status);
            $stmt->execute();
        }
        $success = "Attendance recorded successfully for date: $date";
    } else {
        $error = "Please fill in all attendance fields!";
    }
}

// 2. Upload Marks Submission
if (isset($_POST['upload_marks'])) {
    $student_id = $_POST['student_id'];
    $subject = trim($_POST['subject']);
    $marks = floatval($_POST['marks']);
    $total_marks = floatval($_POST['total_marks']);

    if (!empty($student_id) && !empty($subject)) {
        $stmt = $conn->prepare("INSERT INTO results (student_id, subject, marks, total_marks) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdd", $student_id, $subject, $marks, $total_marks);

        if ($stmt->execute()) {
            $success = "Marks uploaded successfully!";
        } else {
            $error = "Error uploading marks: " . $conn->error;
        }
    } else {
        $error = "Please fill in all marks fields!";
    }
}

// 3. Post Assignment Submission
if (isset($_POST['post_assignment'])) {
    $title = trim($_POST['title']);
    $course = trim($_POST['course']);
    $deadline = $_POST['deadline'];
    
    if (!empty($title) && !empty($course) && !empty($deadline) && isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == 0) {
        $file_name = $_FILES['assignment_file']['name'];
        $file_tmp = $_FILES['assignment_file']['tmp_name'];
        $upload_dir = "../uploads/assignments/";
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $new_file_name = time() . "_" . basename($file_name);
        $target_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $stmt = $conn->prepare("INSERT INTO assignments (title, course, file_path, deadline) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $course, $target_path, $deadline);
            if ($stmt->execute()) {
                $success = "Assignment posted successfully!";
            } else {
                $error = "Database Error: " . $conn->error;
            }
        } else {
            $error = "Failed to upload assignment file!";
        }
    } else {
        $error = "Please fill in all assignment details and attach a file!";
    }
}

// Chota taarifa za Mhadhiri
$stmt = $conn->prepare("SELECT fullname, email, education_level, salary, course FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$lecturer = $stmt->get_result()->fetch_assoc();

// Chota orodha ya wanafunzi
$students_list = $conn->query("SELECT id, fullname, reg_no FROM users WHERE role = 'student'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Portal - My Campus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a;
            --accent: #0284c7;
            --accent-hover: #0369a1;
            --bg-light: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-light); 
            margin: 0; 
            padding: 0; 
            color: var(--text-main); 
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 280px; 
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); 
            position: fixed; 
            height: 100%; 
            top: 0; 
            left: 0; 
            padding-top: 30px; 
            box-sizing: border-box; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }
        .sidebar h2 { 
            color: #ffffff; 
            text-align: center; 
            font-size: 20px; 
            font-weight: 700;
            margin-bottom: 35px; 
            letter-spacing: 0.5px;
        }
        .sidebar button { 
            width: 100%; 
            background: none; 
            border: none; 
            text-align: left; 
            padding: 15px 25px; 
            font-size: 14px; 
            font-weight: 500;
            color: #94a3b8; 
            display: block; 
            cursor: pointer; 
            transition: all 0.3s ease; 
        }
        .sidebar button:hover, .sidebar button.active { 
            background-color: rgba(2, 132, 199, 0.15); 
            color: #38bdf8; 
            border-left: 4px solid #38bdf8;
        }
        .sidebar .logout-link {
            padding: 15px 25px; 
            display: block; 
            text-decoration: none; 
            color: #f87171; 
            font-size: 14px; 
            font-weight: 600;
            margin-top: 40px; 
            transition: 0.2s;
        }
        .sidebar .logout-link:hover {
            background-color: rgba(239, 68, 68, 0.1);
        }

        /* Main Content Layout */
        .main-content { 
            margin-left: 280px; 
            padding: 40px; 
            box-sizing: border-box; 
        }
        .header { 
            background: var(--card-bg); 
            padding: 22px 35px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
            margin-bottom: 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border: 1px solid var(--border-color);
        }
        .header h2 {
            margin: 0;
            color: var(--primary);
            font-size: 22px;
        }

        /* Cards & Tabs */
        .card { 
            background: var(--card-bg); 
            padding: 35px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
            margin-bottom: 25px; 
            display: none; 
            border: 1px solid var(--border-color);
            animation: fadeIn 0.4s ease-in-out;
        }
        .card.active-tab { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h3 { 
            margin-top: 0; 
            color: var(--primary); 
            font-size: 18px;
            font-weight: 600;
            border-bottom: 2px solid var(--bg-light); 
            padding-bottom: 12px; 
            margin-bottom: 25px; 
        }

        /* Profile Details Grid */
        .info-row { 
            display: flex; 
            margin-bottom: 18px; 
            border-bottom: 1px solid #f1f5f9; 
            padding-bottom: 12px; 
        }
        .info-label { 
            width: 200px; 
            font-weight: 600; 
            color: var(--text-muted); 
            font-size: 14px; 
        }
        .info-value { 
            flex: 1; 
            font-weight: 600; 
            color: var(--primary); 
            font-size: 14px; 
        }
        .badge { background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; }
        .salary-badge { background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; }

        /* Forms & Inputs */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px; color: var(--primary); }
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 14px; 
            background: #fafaf9;
            transition: all 0.3s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
        }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 14px; border-bottom: 1px solid var(--border-color); text-align: left; font-size: 14px; }
        th { background: #f8fafc; color: var(--text-muted); font-weight: 600; }
        tr:hover td { background: #fdfdfd; }

        /* Buttons */
        .btn { 
            background: var(--accent); 
            color: white; 
            border: none; 
            padding: 14px 24px; 
            font-weight: 600; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 14px; 
            transition: background 0.2s;
        }
        .btn:hover { background: var(--accent-hover); }

        .logout-btn { 
            background: #ef4444; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: 600; 
            transition: background 0.2s;
        }
        .logout-btn:hover { background: #dc2626; }

        /* Alerts */
        .alert-success { background-color: #dcfce7; color: #166534; padding: 14px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; font-weight: 500; border: 1px solid #bbf7d0; }
        .alert-error { background-color: #fee2e2; color: #991b1b; padding: 14px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; font-weight: 500; border: 1px solid #fecaca; }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div class="sidebar">
        <h2>My Campus 🎓</h2>
        <button onclick="switchTab(event, 'profileTab')" id="btn_profileTab" class="active">👤 My Profile</button>
        <button onclick="switchTab(event, 'attendanceTab')" id="btn_attendanceTab">📋 Mark Attendance</button>
        <button onclick="switchTab(event, 'marksTab')" id="btn_marksTab">📊 Upload Marks</button>
        <button onclick="switchTab(event, 'assignmentTab')" id="btn_assignmentTab">📚 Post Assignment</button>
        <a href="../auth/logout.php" class="logout-link">🚪 Logout</a>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="header">
            <h2>Welcome back, <?php echo htmlspecialchars($lecturer['fullname']); ?>! 👋</h2>
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if($success) echo "<div class='alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert-error'>$error</div>"; ?>

        <!-- 1. MY PROFILE TAB -->
        <div id="profileTab" class="card active-tab">
            <h3>👤 Lecturer Profile Overview</h3>
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($lecturer['fullname']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email Address:</div>
                <div class="info-value"><?php echo htmlspecialchars($lecturer['email']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Education Level:</div>
                <div class="info-value"><span class="badge"><?php echo !empty($lecturer['education_level']) ? htmlspecialchars($lecturer['education_level']) : 'Not Specified'; ?></span></div>
            </div>
            <div class="info-row">
                <div class="info-label">Monthly Salary:</div>
                <div class="info-value"><span class="salary-badge"><?php echo !empty($lecturer['salary']) ? number_format($lecturer['salary']) . " TZS" : 'Not Set'; ?></span></div>
            </div>
            <div class="info-row">
                <div class="info-label">Assigned Course(s):</div>
                <div class="info-value"><strong><?php echo !empty($lecturer['course']) ? htmlspecialchars($lecturer['course']) : 'No course assigned yet'; ?></strong></div>
            </div>
        </div>

        <!-- 2. MARK ATTENDANCE TAB -->
        <div id="attendanceTab" class="card">
            <h3>📋 Mark Student Attendance</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label>Module / Course Subject *</label>
                    <input type="text" name="module" required placeholder="e.g. Web Development and Technologies">
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Reg Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($s = $students_list->fetch_assoc()): 
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($s['reg_no']); ?></td>
                            <td>
                                <select name="status[<?php echo $s['id']; ?>]" style="padding: 8px; border-radius: 6px; border: 1px solid var(--border-color);">
                                    <option value="Present">Present</option>
                                    <option value="Absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <button type="submit" name="save_attendance" class="btn" style="margin-top: 25px;">Save Attendance</button>
            </form>
        </div>

        <!-- 3. UPLOAD MARKS TAB -->
        <div id="marksTab" class="card">
            <h3>📊 Upload Student Marks</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Select Student *</label>
                    <select name="student_id" required>
                        <option value="">-- Choose Student --</option>
                        <?php 
                        $students_list->data_seek(0);
                        while($s = $students_list->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['fullname']) . " (" . htmlspecialchars($s['reg_no']) . ")"; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Subject / Module Name *</label>
                    <input type="text" name="subject" required placeholder="e.g. Database Systems">
                </div>
                <div class="form-group">
                    <label>Marks Scored *</label>
                    <input type="number" step="0.1" name="marks" required placeholder="e.g. 85">
                </div>
                <div class="form-group">
                    <label>Total Marks (Out of) *</label>
                    <input type="number" step="0.1" name="total_marks" required value="100">
                </div>
                <button type="submit" name="upload_marks" class="btn">Upload Marks</button>
            </form>
        </div>

        <!-- 4. POST ASSIGNMENT TAB -->
        <div id="assignmentTab" class="card">
            <h3>📚 Post Assignment</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Assignment Title *</label>
                    <input type="text" name="title" required placeholder="e.g. PHP Database Integration Task">
                </div>
                <div class="form-group">
                    <label>Course / Program *</label>
                    <input type="text" name="course" required placeholder="e.g. Information Systems and Network Engineering">
                </div>
                <div class="form-group">
                    <label>Submission Deadline *</label>
                    <input type="date" name="deadline" required>
                </div>
                <div class="form-group">
                    <label>Attach Assignment File (PDF, DOCX) *</label>
                    <input type="file" name="assignment_file" required style="background: #fafaf9; padding: 10px;">
                </div>
                <button type="submit" name="post_assignment" class="btn">Post & Send Assignment</button>
            </form>
        </div>

    </div>

    <!-- JavaScript ya Udhibiti wa Tabs -->
    <script>
    function switchTab(evt, tabName) {
        let cards = document.getElementsByClassName("card");
        for (let i = 0; i < cards.length; i++) {
            cards[i].classList.remove("active-tab");
        }
        let buttons = document.getElementsByClassName("sidebar")[0].getElementsByTagName("button");
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove("active");
        }
        document.getElementById(tabName).classList.add("active-tab");
        if(evt) {
            evt.currentTarget.classList.add("active");
        } else {
            document.getElementById('btn_' + tabName).classList.add("active");
        }
    }

    window.onload = function() {
        <?php if(isset($_POST['save_attendance'])): ?>
            switchTab(null, 'attendanceTab');
        <?php elseif(isset($_POST['upload_marks'])): ?>
            switchTab(null, 'marksTab');
        <?php elseif(isset($_POST['post_assignment'])): ?>
            switchTab(null, 'assignmentTab');
        <?php else: ?>
            switchTab(null, 'profileTab');
        <?php endif; ?>
    };
    </script>
</body>
</html>