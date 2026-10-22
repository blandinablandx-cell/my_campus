<?php
session_start();
include '../includes/db.php';

// Hakikisha ni mwanafunzi pekee anayeweza kuingia
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];

// Kuchukua ID ya mwanafunzi aliyelogin kutoka kwenye table ya users
$user_sql = "SELECT id FROM users WHERE username = '$username'";
$user_res = $conn->query($user_sql);
$user_row = $user_res->fetch_assoc();
$student_id = $user_row['id'];

// Kuchukua matokeo ya mwanafunzi huyu kutoka kwenye table ya results
$results_sql = "SELECT course_name, marks FROM results WHERE student_id = '$student_id'";
$results_res = $conn->query($results_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results - Student Portal</title>
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
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #0d1b2a;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #00b4d8;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>My Academic Results</h2>
        <a href="dashboard.php" style="color: white; text-decoration: none;">← Dashboard</a>
    </div>

    <div class="container">
        <h2>Matokeo Yangu ya Mitihani</h2>
        <p>Hapa chini kuna orodha ya masomo na alama zako zilizorekodiwa na wahadhiri.</p>

        <table>
            <thead>
                <tr>
                    <th>Jina la Somo (Course Name)</th>
                    <th>Alama (Marks)</th>
                    <th>Hali (Status)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($results_res->num_rows > 0) {
                    while($row = $results_res->fetch_assoc()) {
                        $status = ($row['marks'] >= 50) ? "<span style='color:green; font-weight:bold;'>Pass</span>" : "<span style='color:red; font-weight:bold;'>Fail</span>";
                        echo "<tr>
                                <td>" . $row['course_name'] . "</td>
                                <td>" . $row['marks'] . "</td>
                                <td>" . $status . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center;'>Hakuna matokeo yaliyopatikana kwa sasa.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="back-link">← Rudi kwenye Dashibodi</a>
    </div>

</body>
</html>