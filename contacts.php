<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts - My Campus University</title>
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
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.05);
        }
        h2 {
            color: #0d1b2a;
            border-bottom: 2px solid #00b4d8;
            padding-bottom: 10px;
            margin-top: 0;
        }
        p {
            line-height: 1.6;
            color: #475569;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .contact-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .contact-card:hover {
            transform: translateY(-4px);
            box-shadow: 0px 6px 15px rgba(0,0,0,0.08);
        }
        .contact-card h3 {
            color: #0d1b2a;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .contact-card p {
            font-size: 14px;
            margin-bottom: 15px;
            color: #64748b;
        }
        .contact-btn {
            display: inline-block;
            background-color: #00b4d8;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.3s;
        }
        .contact-btn:hover {
            background-color: #03045e;
        }
        .whatsapp-btn { background-color: #25d366; }
        .whatsapp-btn:hover { background-color: #128c7e; }
        
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            color: #0d1b2a;
            text-decoration: none;
            font-weight: 600;
        }
        .back-btn:hover {
            color: #00b4d8;
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

    <div class="navbar">
        <h1>My Campus - Contacts</h1>
        <div>
            <a href="index.php" style="color: white; text-decoration: none; font-weight: 600;">← Back to Home</a>
        </div>
    </div>

    <div class="container">
        <h2>Get in Touch With Us</h2>
        <p>Have questions regarding admissions, examinations, technical support, or general enquiries? Reach out to us through any of the official channels below:</p>

        <div class="contact-grid">
            
            <!-- 1. Phone Call -->
            <div class="contact-card">
                <h3>Phone Call</h3>
                <p>Speak directly with our university reception or admission desk.</p>
                <p><strong>+255 712 345 678</strong></p>
                <a href="tel:+255712345678" class="contact-btn">Call Now</a>
            </div>

            <!-- 2. Normal SMS / Text -->
            <div class="contact-card">
                <h3>Normal SMS</h3>
                <p>Send us a direct text message for quick inquiries.</p>
                <p><strong>+255 712 345 678</strong></p>
                <a href="sms:+255712345678" class="contact-btn">Send SMS</a>
            </div>

            <!-- 3. WhatsApp -->
            <div class="contact-card">
                <h3>WhatsApp Chat</h3>
                <p>Chat with our student support team instantly via WhatsApp.</p>
                <p><strong>+255 712 345 678</strong></p>
                <a href="https://wa.me/255712345678?text=Hello%20My%20Campus%20Support,%20I%20have%20an%20inquiry." target="_blank" class="contact-btn whatsapp-btn">Chat on WhatsApp</a>
            </div>

            <!-- 4. Instagram -->
            <div class="contact-card">
                <h3>Instagram</h3>
                <p>Follow our campus life, galleries, and events updates.</p>
                <p><strong>@mycampus_uni</strong></p>
                <a href="https://instagram.com/mycampus_uni" target="_blank" class="contact-btn" style="background-color: #e1306c;">Follow on IG</a>
            </div>

            <!-- 5. Facebook -->
            <div class="contact-card">
                <h3>Facebook</h3>
                <p>Join our official university community page.</p>
                <p><strong>My Campus University</strong></p>
                <a href="https://facebook.com/mycampus_uni" target="_blank" class="contact-btn" style="background-color: #1877f2;">Visit Facebook</a>
            </div>

            <!-- 6. Twitter / X -->
            <div class="contact-card">
                <h3>Twitter (X)</h3>
                <p>Get instant announcements and official notices.</p>
                <p><strong>@mycampus_tz</strong></p>
                <a href="https://twitter.com/mycampus_tz" target="_blank" class="contact-btn" style="background-color: #000000;">Follow on X</a>
            </div>

        </div>

        <a href="index.php" class="back-btn">← Back to Dashboard</a>
    </div>

    <footer>
        <p>&copy; 2026 My Campus University System. All rights reserved.</p>
    </footer>

</body>
</html>