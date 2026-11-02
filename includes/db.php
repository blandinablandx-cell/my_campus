<?php
$host = getenv('MYSQLHOST') ? getenv('MYSQLHOST') : 'localhost';
$username = getenv('MYSQLUSER') ? getenv('MYSQLUSER') : 'root';
$password_db = getenv('MYSQLPASSWORD') ? getenv('MYSQLPASSWORD') : '';
$database = getenv('MYSQLDATABASE') ? getenv('MYSQLDATABASE') : 'student_management3';
$port = (int) (getenv('MYSQLPORT') ? getenv('MYSQLPORT') : 3306);

// Anzisha muunganisho wa mysqli
$conn = mysqli_init();

// Weka SSL flags kama upo mtandaoni (kama Render au cloud DB inayohitaji SSL)
$flags = 0;
if (getenv('MYSQLHOST')) {
    $flags = MYSQLI_CLIENT_SSL;
}

// Unganisha na database
mysqli_real_connect($conn, $host, $username, $password_db, $database, $port, NULL, $flags);

// Hakikisha character set ni UTF-8 ili kuzuia matatizo ya maandishi (kama lugha za kipekee au emoji)
if ($conn) {
    mysqli_set_charset($conn, 'utf8mb4');
}

// Angalia kama kuna tatizo la muunganisho
if (mysqli_connect_errno()) {
    die("Failed to connect: " . mysqli_connect_error());
}
?>