<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centers & Institutes - My Campus</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        .navbar {
            background-color: #0d1b2a;
            color: #ffffff;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 22px;
            color: #00b4d8;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
        }
        h2 {
            color: #0d1b2a;
            border-bottom: 2px solid #00b4d8;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: #00b4d8;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>My Campus</h1>
        <div>
            <a href="../index.php" style="color: white; text-decoration: none; font-weight: 600;">← Back to Home</a>
        </div>
    </div>

    <div class="container">
        <h2>Centers & Institutes</h2>
        <p>Explore specialized research centers and professional training institutes operating under My Campus University:</p>
        <ul>
            <li>Center for Information and Communication Technology (ICT Center)</li>
            <li>Center for Professional Development & Consultancy</li>
            <li>Institute of Networks and Smart Systems Research</li>
        </ul>
        <a href="../index.php" class="back-btn">← Back to Dashboard</a>
    </div>

</body>
</html>