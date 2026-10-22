<?php
session_start();
include '../includes/db.php';

// Hakikisha ni mwalimu (lecturer) pekee anayeweza kuingia
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'lecturer') {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard - My Campus</title>
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
            max-width: 900px;
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
        .cards-grid {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
            flex: 1;
            min-width: 250px;
            border-left: 4px solid #1b263b;
        }
        .card h3 {
            margin-top: 0;
            color: #0d1b2a;
        }
        .logout-btn {
            background-color: #d90429;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>My Campus - Lecturer Portal</h2>
        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <h2>Karibu Mwl. <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Hii ni dashibodi ya mwalimu kwa ajili ya kusimamia masomo na kuingiza alama.</p>

        <div class="cards-grid">
            <div class="card">
                <h3>Ingiza Alama (Enter Grades)</h3>
                <p>Weka alama za mitihani na tathmini za kuendelea kwa wanafunzi.</p>
                <a href="enter_grades.php" style="color: #00b4d8; text-decoration: none; font-weight: bold;">Ingiza Alama →</a>
            </div>
        </div>
    </div>

</body>
</html>