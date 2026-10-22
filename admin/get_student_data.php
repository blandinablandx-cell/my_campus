<?php
include '../includes/db.php';

if (isset($_GET['username'])) {
    $username = trim($_GET['username']);
    
    $stmt = $conn->prepare("SELECT email, age, nationality, education_level, course FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
}
?>