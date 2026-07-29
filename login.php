<?php
session_start();
include "includes/db.php";

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

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == "admin") {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }

            exit();

        } else {
            $error = "Invalid password.";
        }

    } else {
        $error = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#4e73df,#1cc88a);
}

.login-box{
    width:380px;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.2);
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#333;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:8px;
    outline:none;
    font-size:15px;
}

input:focus{
    border-color:#4e73df;
}

button{
    width:100%;
    padding:12px;
    background:#4e73df;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:.3s;
}

button:hover{
    background:#224abe;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:5px;
    margin-bottom:15px;
    text-align:center;
}

</style>

</head>

<body>

<div class="login-box">

    <h2>Member Management System</h2>

    <?php
    if (!empty($error)) {
        echo "<div class='error'>$error</div>";
    }
    ?>

    <form method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>

</body>
</html>