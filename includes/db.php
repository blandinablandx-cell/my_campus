<?php
$host = getenv('MYSQLHOST') ? getenv('MYSQLHOST') : 'localhost';
$username = getenv('MYSQLUSER') ? getenv('MYSQLUSER') : 'root';
$password_db = getenv('MYSQLPASSWORD') ? getenv('MYSQLPASSWORD') : '';
$database = getenv('MYSQLDATABASE') ? getenv('MYSQLDATABASE') : 'student_management3';
$port = (int) (getenv('MYSQLPORT') ? getenv('MYSQLPORT') : 3306);

$conn = mysqli_init();

$flags = 0;
if (getenv('MYSQLHOST')) {
    $flags = MYSQLI_CLIENT_SSL;
}

mysqli_real_connect($conn, $host, $username, $password_db, $database, $port, NULL, $flags);

if ($conn) {
    mysqli_set_charset($conn, 'utf8mb4');
}

if (mysqli_connect_errno()) {
    die("Failed to connect: " . mysqli_connect_error());
}
?>