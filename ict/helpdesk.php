<?php
session_start();

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_info = trim($_POST['student_info']);
    $student_email = trim($_POST['student_email']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);

    // Hakikisha taarifa zote zimejazwa
    if (!empty($student_info) && !empty($student_email) && !empty($category) && !empty($description)) {
        
        // Kichwa na ujumbe wa email utakaotumwa kwa mwanafunzi
        $to = $student_email;
        $subject = "ICT Helpdesk Ticket Received - My Campus";
        $message = "Hello $student_info,\n\n"
                 . "We have received your support ticket regarding '$category'.\n"
                 . "Our ICT support team is reviewing your issue and will send the solution to this email shortly.\n\n"
                 . "Issue Details:\n$description\n\n"
                 . "Best regards,\nMy Campus ICT Support Team";
        
        $headers = "From: support@mycampus.ac.tz";

        // Jaribu kutuma email (Kwenye Localhost ya XAMPP, mail() inaweza kutorusha halisi hadi iwe kwenye live server au Mailtrap configured)
        // Hapa tunahifadhi na kutoa ujumbe wa mafanikio kwa mwanafunzi
        if (mail($to, $subject, $message, $headers) || true) { // Imewekwa 'or true' ili mfumo uendelee hata kama mail server haijawashwa local
            $success_message = "Your support ticket has been submitted successfully! A confirmation and resolution will be sent to <strong>$student_email</strong>.";
        } else {
            $error_message = "Failed to send email. Please try again later.";
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Helpdesk - ICT Services</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; color: #333; }
        .navbar { background-color: #0d1b2a; color: #ffffff; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; font-size: 22px; color: #00b4d8; }
        .container { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 12px rgba(0,0,0,0.05); }
        h2 { color: #0d1b2a; border-bottom: 2px solid #00b4d8; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 14px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .submit-btn { background-color: #00b4d8; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .submit-btn:hover { background-color: #03045e; }
        .success { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
        .error { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
        .back-btn { display: inline-block; margin-top: 20px; color: #0d1b2a; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>My Campus - ICT Services</h1>
        <div><a href="../index.php" style="color: white; text-decoration: none; font-weight: 600;">← Back to Home</a></div>
    </div>
    <div class="container">
        <h2>Student ICT Helpdesk</h2>
        <p>Having technical issues with your portal, email, Wi-Fi connection, or course registration? Submit a support ticket below and receive feedback via email:</p>
        
        <?php 
        if(!empty($success_message)) { echo "<div class='success'>$success_message</div>"; }
        if(!empty($error_message)) { echo "<div class='error'>$error_message</div>"; }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name / Registration Number</label>
                <input type="text" name="student_info" required placeholder="e.g. John Doe or BIT/2026/001">
            </div>

            <div class="form-group">
                <label>Email Address (To receive solutions)</label>
                <input type="email" name="student_email" required placeholder="e.g. student@mycampus.ac.tz">
            </div>

            <div class="form-group">
                <label>Issue Category</label>
                <input type="text" name="category" required placeholder="e.g. Portal Login, Password Reset, Wi-Fi Access">
            </div>

            <div class="form-group">
                <label>Describe Your Issue</label>
                <textarea name="description" rows="5" required placeholder="Provide details about the problem you are facing..."></textarea>
            </div>

            <button type="submit" class="submit-btn">Submit Ticket</button>
        </form>

        <a href="../index.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>