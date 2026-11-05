<?php
// Replace these with your actual Render Cloud Database credentials
$host = 'your_render_host';
$user = 'your_render_username';
$pass = 'your_render_password';
$db   = 'defaultdb';
$port = 3306; // Change if Render provided a different port

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read your local SQL backup file
$sql = file_get_contents('my_campus (1).sql');

if ($conn->multi_query($sql)) {
    echo "Success! Database and all tables have been imported to Render.";
} else {
    echo "Import error: " . $conn->error;
}
?>