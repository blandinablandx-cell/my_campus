<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions - My Campus</title>
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
        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background-color: #00b4d8;
        }
        .header-banner {
            background: linear-gradient(rgba(13, 27, 42, 0.8), rgba(13, 27, 42, 0.8)), url('../assets/images/banner1.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 60px 20px;
            position: relative;
        }
        .header-banner h2 {
            font-size: 36px;
            margin-bottom: 10px;
            color: #00b4d8;
        }
        .header-banner p {
            font-size: 16px;
            color: #adb5bd;
        }
        /* Kitufe cha Kurudi Home */
        .home-tab {
            position: absolute;
            top: 20px;
            left: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #0d1b2a;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .home-tab:hover {
            background: #00b4d8;
            color: white;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .section-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .section-card h3 {
            color: #0d1b2a;
            border-bottom: 2px solid #00b4d8;
            padding-bottom: 10px;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #1b263b;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        ul, ol {
            padding-left: 20px;
            line-height: 1.6;
        }
        li {
            margin-bottom: 10px;
        }
        .btn-apply {
            display: inline-block;
            background-color: #00b4d8;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        .btn-apply:hover {
            background-color: #03045e;
        }
        footer {
            background-color: #0d1b2a;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <h1>My Campus</h1>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="admissions.php" style="background-color: #00b4d8;">Admissions</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <!-- Header Banner na Kitufe cha Kurudi Home -->
    <div class="header-banner">
        <a href="../index.php" class="home-tab">← Back to Home</a>
        <h2>University Admissions Portal</h2>
        <p>Your gateway to academic excellence, professional growth, and global opportunities.</p>
    </div>

    <div class="container">

        <!-- 1. Programs Offered -->
        <div class="section-card">
            <h3>Programs Offered</h3>
            <p>We offer accredited undergraduate, diploma, and certificate programs specializing in modern ICT, Networks, Electrical Systems, and Engineering:</p>
            <table>
                <thead>
                    <tr>
                        <th>Program Code</th>
                        <th>Program Name</th>
                        <th>Duration</th>
                        <th>Minimum Entry Requirements</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CS</td>
                        <td>Bachelor of Science in Computer Science</td>
                        <td>3 Years</td>
                        <td>Principal pass in Advanced Mathematics and Physics.</td>
                    </tr>
                    <tr>
                        <td>IT</td>
                        <td>Bachelor of Science in Information Technology</td>
                        <td>3 Years</td>
                        <td>At least two principal passes in science subjects.</td>
                    </tr>
                    <tr>
                        <td>NET</td>
                        <td>Bachelor of Science in Computer Networks & Security</td>
                        <td>3 Years</td>
                        <td>Principal passes in Mathematics, Physics or ICT-related subjects.</td>
                    </tr>
                    <tr>
                        <td>DE</td>
                        <td>Bachelor of Engineering in Digital Electricity & Smart Systems</td>
                        <td>4 Years</td>
                        <td>Principal passes in Advanced Mathematics and Physics.</td>
                    </tr>
                    <tr>
                        <td>ECE</td>
                        <td>Diploma in Electrical & Computer Engineering</td>
                        <td>2 Years</td>
                        <td>Form Four certificate with passes in Mathematics and Physics.</td>
                    </tr>
                    <tr>
                        <td>EN</td>
                        <td>Diploma in Civil & Mechanical Engineering</td>
                        <td>2 Years</td>
                        <td>Holder of Certificate in related technical fields.</td>
                    </tr>
                    <tr>
                        <td>BA</td>
                        <td>Bachelor of Business Administration</td>
                        <td>3 Years</td>
                        <td>Two principal passes in Arts or Business subjects.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 2. Admission Criteria -->
        <div class="section-card">
            <h3>Admission Criteria</h3>
            <p>To be eligible for admission into My Campus University, applicants must fulfill the following minimum criteria:</p>
            <ul>
                <li><strong>For Bachelor Degree Programs:</strong> Possession of an Advanced Certificate of Secondary Education Examination (ACSEE) with at least two principal passes in relevant subjects.</li>
                <li><strong>For Diploma Programs:</strong> Possession of a Basic Technician Certificate (NTA Level 4) or Form Four secondary education certificate with required passes in science and mathematics.</li>
                <li><strong>Age & Conduct:</strong> Applicants must be of sound character and meet specific prerequisites set by respective academic boards.</li>
            </ul>
        </div>

        <!-- 3. Fee Structure -->
        <div class="section-card">
            <h3>Fee Structure (Per Academic Year)</h3>
            <p>Tuition fees vary depending on the chosen program of study (Minimum tuition fee starts from 1,200,000 TZS). Below is the estimated fee breakdown:</p>
            <table>
                <thead>
                    <tr>
                        <th>Fee Component</th>
                        <th>Amount (TZS)</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tuition Fee (Minimum)</td>
                        <td>1,200,000 TZS</td>
                        <td>Paid per academic year (can be paid in two installments).</td>
                    </tr>
                    <tr>
                        <td>Registration & ICT Fee</td>
                        <td>150,000 TZS</td>
                        <td>Covers portal maintenance, identity cards, and library access.</td>
                    </tr>
                    <tr>
                        <td>Examination Fee</td>
                        <td>100,000 TZS</td>
                        <td>Mandatory annual assessment fee.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 4. How to Apply Online -->
        <div class="section-card">
            <h3>How to Apply Online</h3>
            <p>Follow these simple steps to submit your application to My Campus University:</p>
            <ol style="line-height: 1.8;">
                <li>Navigate to the official admissions registration page.</li>
                <li>Fill in all your personal details, academic qualifications, and select your preferred course code (e.g., NET, DE, ECE, etc.).</li>
                <li>Complete the security verification (Math Captcha) to prove you are human.</li>
                <li>Submit your application form and wait for confirmation via email.</li>
                <li>Once confirmed by the administration, you will receive your unique Registration Number (RegNo) and temporary password via email to access your portal.</li>
            </ol>
            <a href="register.php" class="btn-apply">Proceed to Online Application</a>
        </div>

    </div>

    <footer>
        <p>&copy; 2026 My Campus University System. All rights reserved.</p>
    </footer>

</body>
</html>