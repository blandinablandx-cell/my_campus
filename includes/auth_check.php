<?php
session_start();
function checkRole($required_role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $required_role) {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>