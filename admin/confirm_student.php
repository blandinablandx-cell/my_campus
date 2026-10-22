<?php
session_start();
include '../includes/db.php';

// Hakikisha admin ndiye aliyelogin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);

    // Badilisha status ya mwanafunzi kuwa Confirmed
    $sql = "UPDATE users SET status = 'Confirmed' WHERE id = $student_id AND role = 'student'";

    if ($conn->query($sql) === TRUE) {
        // Rudisha admin kwenye ukurasa wake wa dashboard baada ya kufanikiwa
        header("Location: dashboard.php"); // Badilisha iwe jina la dashboard yako ya admin
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>