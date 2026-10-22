<?php
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "my_campus";

$conn = new mysqli($host, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die("Imeshindwa kuunganisha: " . $conn->connect_error);
}

$admin_user = "admin255";
$raw_password = "12345";
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

// Hapa tunatumia 'Administrator' au 'admin' kutegemeana na unavyotaka. 
// Hakikisha inafanana na value uliyoweka kwenye option ya fomu ya login.
$role = "Administrator"; 

$sql = "INSERT INTO users (username, password, role) VALUES ('$admin_user', '$hashed_password', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "Akaunti ya Admin imeundwa kwa mafanikio! Sasa unaweza kulogin.";
} else {
    echo "Hitilafu (Inawezekana username ipo tayari kwenye database): " . $conn->error;
}

$conn->close();
?>