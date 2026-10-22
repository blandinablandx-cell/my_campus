<?php
session_start();
include '../includes/db.php';

// Hakikisha ni admin pekee anayeweza kuingia
if (!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    
    // Hifadhi nenosiri kama Plain Text (Kama halijajazwa tumia '123456')
    $password = !empty($_POST['password']) ? trim($_POST['password']) : '123456';
    
    $role = $_POST['role'];
    $course = trim($_POST['course']);
    
    // Fanya mwanafunzi awe Confirmed moja kwa moja (automatic)
    $status = ($role == 'student') ? 'Confirmed' : 'Active';
    $expire_date = !empty($_POST['expire_date']) ? "'" . $_POST['expire_date'] . "'" : "NULL";

    if (!empty($fullname) && !empty($username) && !empty($course)) {
        
        // 1. Tengeneza RegNo kimitambo BILA KUTUMIA / (Mfano: IT2026001)
        $current_year = date('Y');
        
        // Pata herufi za mwanzo za kozi au kifupisho chake (Mfano: ISNE, CS, IT)
        $words = explode(" ", $course);
        $acronym = "";
        foreach ($words as $w) {
            $acronym .= strtoupper($w[0]);
        }
        if(strlen($acronym) < 2) {
            $acronym = strtoupper(substr($course, 0, 3));
        }

        // 2. Hesabu wanafunzi wangapi wa kozi hii waliojisajili mwaka huu ili kupata namba ya mtiririko
        $count_query = $conn->query("SELECT COUNT(*) as total FROM users WHERE course = '$course' AND reg_no LIKE '$acronym$current_year%'");
        $row_count = $count_query->fetch_assoc();
        $next_number = intval($row_count['total']) + 1;
        
        // Fomati namba iwe na tarakimu 3 (Mfano: 001, 002)
        $formatted_number = str_pad($next_number, 3, '0', STR_PAD_LEFT);
        
        // Muundo mpya wa RegNo (Mfano: IT2026001)
        $reg_no = $acronym . $current_year . $formatted_number;

        // 3. Hifadhi au sasisha kwenye database
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if ($check->num_rows > 0) {
            $sql = "UPDATE users SET fullname='$fullname', reg_no='$reg_no', email='$email', password='$password', role='$role', course='$course', status='$status', expire_date=$expire_date WHERE username='$username'";
            if ($conn->query($sql) === TRUE) {
                $success = "Student registered successfully! Generated RegNo: <strong>$reg_no</strong>";
            } else {
                $error = "Database Error: " . $conn->error;
            }
        } else {
            $sql = "INSERT INTO users (fullname, reg_no, username, email, password, role, course, status, expire_date) 
                    VALUES ('$fullname', '$reg_no', '$username', '$email', '$password', '$role', '$course', '$status', $expire_date)";
            
            if ($conn->query($sql) === TRUE) {
                $success = "New user added and auto-confirmed successfully! Generated RegNo: <strong>$reg_no</strong>";
            } else {
                $error = "Database Error: " . $conn->error;
            }
        }
    } else {
        $error = "Please fill in all required fields!";
    }
}

// Chota wale wanafunzi ambao bado hawajapewa reg_no (ndio watakaotokea kwenye Auto-Fill)
$students_list = $conn->query("SELECT username, fullname, email, course FROM users WHERE role = 'student' AND (reg_no IS NULL OR reg_no = '')");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - Admin Portal</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px; }
        .form-container { max-width: 600px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.05); margin: auto; }
        h2 { color: #0d1b2a; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #1b263b; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .btn { background: #00b4d8; color: white; border: none; padding: 12px; width: 100%; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #03045e; }
        .back-link { display: inline-block; margin-top: 15px; color: #0d1b2a; text-decoration: none; font-size: 13px; font-weight: 600; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .auto-fill-box { background: #eff6ff; border: 1px dashed #3b82f6; padding: 12px; border-radius: 6px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add & Auto-Generate RegNo</h2>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 20px;">Select a pending student to auto-fill. RegNo will be generated automatically and status will be set to Confirmed.</p>
        
        <?php if($error) echo "<div class='alert-error'>$error</div>"; ?>
        <?php if($success) echo "<div class='alert-success'>$success</div>"; ?>
        
        <!-- SEHEMU YA AUTO-FILL -->
        <div class="auto-fill-box">
            <div class="form-group" style="margin-bottom: 0;">
                <label style="color: #1e40af;">⚡ Select Pending Student (Auto-Fill):</label>
                <select id="quickSelectUser" class="form-group" style="margin-top: 5px; background: white;">
                    <option value="">-- Choose unassigned student --</option>
                    <?php while($row = $students_list->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['username']); ?>" 
                                data-fullname="<?php echo htmlspecialchars($row['fullname']); ?>"
                                data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                data-course="<?php echo htmlspecialchars($row['course']); ?>">
                            <?php echo htmlspecialchars($row['fullname']) . " (" . htmlspecialchars($row['username']) . ") - [" . htmlspecialchars($row['course']) . "]"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="fullname" id="fullname" required placeholder="e.g. John Doe">
            </div>
            
            <div class="form-group">
                <label>Registration Number (RegNo) - <em>Auto-generated</em></label>
                <input type="text" disabled value="Generated automatically (e.g. IT2026001)" style="background: #f1f5f9; color: #64748b; font-style: italic;">
            </div>

            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" id="username" required placeholder="e.g. joshua255">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" placeholder="e.g. user@mycampus.ac.tz">
            </div>
            <div class="form-group">
                <label>Password (Plain Text) *</label>
                <input type="text" name="password" placeholder="Enter password (default: 123456)">
            </div>
            <div class="form-group">
                <label>Role *</label>
                <select name="role" required>
                    <option value="student">Student</option>
                    <option value="lecturer">Lecturer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Course / Program *</label>
                <input type="text" name="course" id="course" required placeholder="e.g. Information Systems and Network Engineering">
            </div>
            <div class="form-group">
                <label>Expiry Date (For Students)</label>
                <input type="date" name="expire_date" value="<?php echo date('Y-m-d', strtotime('+3 years')); ?>">
            </div>
            <button type="submit" class="btn">Generate RegNo & Register User</button>
        </form>
        <a href="manage_users.php" class="back-link">← Back to Manage Users</a>
    </div>

    <!-- JavaScript ya Auto-Fill -->
    <script>
    document.getElementById('quickSelectUser').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        if(this.value !== "") {
            document.getElementById('username').value = this.value;
            document.getElementById('fullname').value = selectedOption.getAttribute('data-fullname');
            document.getElementById('email').value = selectedOption.getAttribute('data-email');
            document.getElementById('course').value = selectedOption.getAttribute('data-course');
        } else {
            document.getElementById('username').value = '';
            document.getElementById('fullname').value = '';
            document.getElementById('email').value = '';
            document.getElementById('course').value = '';
        }
    });
    </script>
</body>
</html>