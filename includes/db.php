<?php
$host = getenv('MYSQLHOST') ? getenv('MYSQLHOST') : 'localhost';
$username = getenv('MYSQLUSER') ? getenv('MYSQLUSER') : 'root';
$password_db = getenv('MYSQLPASSWORD') ? getenv('MYSQLPASSWORD') : '';
$database = getenv('MYSQLDATABASE') ? getenv('MYSQLDATABASE') : 'defaultdb';
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

// AUTOMATIC TABLE CREATION & SEEDING:
// Automatically builds the 'users' table and a default admin account if it doesn't exist on Render
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    fullname VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$check_users = $conn->query("SELECT COUNT(*) as total FROM users");
if ($check_users) {
    $row = $check_users->fetch_assoc();
    if ($row['total'] == 0) {
        $default_pass = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (username, password, role, fullname) VALUES ('admin@campus.com', '$default_pass', 'Admin', 'System Administrator')");
    }
}
?>