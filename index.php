<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Campus - University Portal</title>
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
            padding: 15px 40px;
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
            gap: 12px;
            align-items: center;
        }
        .nav-links > a, .dropbtn {
            color: #ffffff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 4px;
            transition: background 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        .nav-links > a:hover, .dropbtn:hover {
            background-color: #00b4d8;
        }
        
        /* Dropdown Styling */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #1b263b;
            min-width: 190px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 4px;
            top: 100%;
            left: 0;
        }
        .dropdown-content a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 13px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .dropdown-content a:hover {
            background-color: #00b4d8;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Banner / Slider Styling */
        .banner-container {
            position: relative;
            max-width: 100%;
            height: 400px;
            overflow: hidden;
            background-color: #0d1b2a;
        }
        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 20px;
            box-sizing: border-box;
            background-size: cover;
            background-position: center;
        }
        .slide.active {
            opacity: 1;
        }
        .slide-content {
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .slide h2 {
            font-size: 38px;
            margin-bottom: 15px;
        }
        .slide p {
            font-size: 18px;
            margin-bottom: 25px;
            color: #f8f9fa;
        }
        .btn {
            background-color: #00b4d8;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 15px;
        }
        .btn:hover {
            background-color: #03045e;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
            flex: 1;
        }
        .card h3 {
            color: #0d1b2a;
            margin-top: 0;
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
            <a href="index.php">Home</a>
            <a href="auth/admissions.php">Admissions</a> 
            
            <!-- Academics Dropdown Menu -->
            <div class="dropdown">
                <button class="dropbtn">Academics ▾</button>
                <div class="dropdown-content">
                    <a href="academics/directorates.php">Directorates</a>
                    <a href="academics/centers_institutes.php">Centers & Institutes</a>
                </div>
            </div>

            <!-- ICT Services Dropdown Menu -->
            <div class="dropdown">
                <button class="dropbtn">ICT Services ▾</button>
                <div class="dropdown-content">
                    <a href="ict/downloads.php">Downloads</a>
                    <a href="ict/helpdesk.php">Student Helpdesk</a>
                </div>
            </div>

            <a href="galleries.php">Galleries</a>
            <a href="contacts.php">Contacts</a>
            
            <?php if(isset($_SESSION['username'])): ?>
                <a href="auth/logout.php" style="background: #d90429; padding: 6px 12px;">Logout</a>
            <?php else: ?>
                <a href="auth/register.php" style="background: #7209b7; padding: 6px 12px;">Register</a>
                <a href="auth/login.php" style="background: #00b4d8; padding: 6px 12px;">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flowing Banners / Slider Section -->
    <div class="banner-container">
        <div class="slide active" style="background-image: url('assets/images/banner1.jpg');">
            <div class="slide-content">
                <h2>Welcome to My Campus Portal</h2>
                <p>Your centralized hub for academic management, student results, and administrative tasks.</p>
                <a href="auth/register.php" class="btn">Register Now</a>
            </div>
        </div>
        <div class="slide" style="background-image: url('assets/images/banner2.jpg');">
            <div class="slide-content">
                <h2>Excellence in Academics & Research</h2>
                <p>Empowering students with modern skills, digital learning tools, and professional guidance.</p>
                <a href="auth/admissions.php" class="btn">Join Admissions</a>
            </div>
        </div>
        <div class="slide" style="background-image: url('assets/images/banner3.jpg');">
            <div class="slide-content">
                <h2>Reliable ICT & Campus Services</h2>
                <p>Enjoy seamless online fee tracking, instant exam result checking, and 24/7 technical support.</p>
                <a href="auth/login.php" class="btn">Access Portal</a>
            </div>
        </div>
        <div class="slide" style="background-image: url('assets/images/banner4.jpg');">
            <div class="slide-content">
                <h2>Student Life & Campus Galleries</h2>
                <p>Explore vibrant campus activities, events, and moments captured across our university.</p>
                <a href="galleries.php" class="btn">View Gallery</a>
            </div>
        </div>
    </div>

    <!-- Main Features Container -->
    <div class="container">
        <div class="card">
            <h3>Academics & Results</h3>
            <p>Students can easily access their semester examinations, GPA evaluations, and academic standing securely.</p>
        </div>
        <div class="card">
            <h3>Fee Management</h3>
            <p>Track tuition fee balances, payment histories, and generate financial statements online.</p>
        </div>
        <div class="card">
            <h3>ICT Services</h3>
            <p>Instructors and students can manage accounts, update portals, and access technical assistance.</p>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 My Campus University System. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Flowing Banners Animation -->
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