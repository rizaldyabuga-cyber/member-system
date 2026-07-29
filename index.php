<?php
session_start();

// If user is already logged in, redirect based on role
if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] == "admin") {
        header("Location: admin/dashboard.php");
        exit();
    }

    if ($_SESSION['role'] == "user") {
        header("Location: user/dashboard.php");
        exit();
    }
}

// If not logged in, redirect to login page
header("Location: login.php");
exit();
?>