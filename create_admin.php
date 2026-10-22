<?php
include 'includes/db.php';

$username = 'admin255';
$password = '12345';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin'; // Hii iwe 'admin' ndogo ili iendane na fomu yako ya login
$fullname = 'System Administrator';
$email = 'admin@mycampus.com';

// Weka taarifa za admin kwenye database
$sql = "INSERT INTO users (username, password, role, fullname, email) VALUES ('$username', '$hashed_password', '$role', '$fullname', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "<h2 style='color:green;'>Akaunti ya Admin imeundwa kwa mafanikio! Sasa unaweza kulogin.</h2>";
} else {
    echo "<h2 style='color:red;'>Imeshindikana (Inawezekana username ipo tayari): " . $conn->error . "</h2>";
}
$conn->close();
?>