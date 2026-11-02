<?php
$host = getenv('MYSQLHOST') ?:'localhost';
$username = getenv('MYSQLUSER') ?:'root';
$password_db = getenv('MYSQLPASSWORD') ?:'';
$database = getenv('MYSQLDATABASE') ?:'student_management3';
$port =(int) getenv('MYSQLPORT') ?: 3306;

$conn =mysqli_init();
if (getenv('MYSQLHOST')) {
    mysqli_set_charset($conn,'NULL','NULL', 'NULL', 'NULL', 'NULL');\
    $flags = MYSQLI_CLIENT_SSL;
} else {
    $flags = 0;
}
mysqli_real_connect($conn, $host, $username_db, $password_db, $database, $port, NULL, $flags);
if (mysqli_connect_errno()) {
    die("failed to connect: " . $conn->connect_error );
}
?>