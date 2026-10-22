<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my_campus";

// Tengeneza muunganisho wa database
$conn = new mysqli($host, $user, $pass, $dbname);

// Angalia kama kuna tatizo la kuunganisha
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Weka aina ya herufi kuwa utf8mb4
$conn->set_charset("utf8mb4");
?>