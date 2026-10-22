<?php
session_start();
include '../includes/db.php';

// Hakikisha ni mwalimu pekee anayeweza kuingia
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'lecturer') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

// Kuchakata fomu ya kuingiza alama zikitumwa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_name = $_POST['course_name'];
    $marks = $_POST['marks'];

    // Hifadhi alama kwenye database (hakikisha table ya results ipo au itengenezwe)
    $sql = "INSERT INTO results (student_id, course_name, marks) VALUES ('$student_id', '$course_name', '$marks')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Alama zimehifadhiwa kwa mafanikio!";
    } else {
        $message = "Hitilafu: " . $conn->error;
    }
}

// Kuchukua orodha ya wanafunzi ili mwalimu aweze kuwachagua
$students_sql = "SELECT id, fullname FROM users WHERE role = 'student'";
$students_result = $conn->query($students_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Grades - Lecturer Portal</title>
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
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
        }
        h2 {
            color: #0d1b2a;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .form-group select, .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .submit-btn {
            width: 100%;
            background-color: #0d1b2a;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #00b4d8;
        }
        .msg {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #00b4d8;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Enter Student Grades</h2>
        <a href="dashboard.php" style="color: white; text-decoration: none;">← Dashboard</a>
    </div>

    <div class="container">
        <h2>Weka Alama za Mwanafunzi</h2>
        <?php if($message != "") { echo "<div class='msg'>$message</div>"; } ?>

        <form method="POST">
            <div class="form-group">
                <label for="student_id">Chagua Mwanafunzi</label>
                <select name="student_id" id="student_id" required>
                    <option value="">-- Chagua Mwanafunzi --</option>
                    <?php
                    if ($students_result->num_rows > 0) {
                        while($s = $students_result->fetch_assoc()) {
                            echo "<option value='".$s['id']."'>".$s['fullname']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course_name">Jina la Somo (Course Name)</label>
                <input type="text" id="course_name" name="course_name" required placeholder="Mfano: Web Programming">
            </div>
            <div class="form-group">
                <label for="marks">Alama (Marks)</label>
                <input type="number" id="marks" name="marks" required placeholder="Mfano: 85">
            </div>
            <button type="submit" class="submit-btn">Hifadhi Alama</button>
        </form>

        <a href="dashboard.php" class="back-link">← Rudi kwenye Dashibodi ya Mwalimu</a>
    </div>

</body>
</html>