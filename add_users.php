<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$success = "";
$error = "";

if (isset($_POST['add_user'])) {

   $fullname = trim($_POST['fullname']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = $_POST['role'];

    if (
    empty($fullname) ||
    empty($username) ||
    empty($password) ||
    empty($confirm_password) ||
    empty($role)
) {
        $error = "Please fill in all fields.";
    }

    elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    }

    else {

        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $error = "Username already exists.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users(fullname, username, password, role) VALUES (?, ?, ?, ?)");
$insert->bind_param("ssss", $fullname, $username, $hashedPassword, $role);

            if ($insert->execute()) {

                $success = "User added successfully.";

            } else {

                $error = "Failed to add user.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add User</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI,Tahoma,sans-serif;
}

body{
background:#f4f6f9;
}

.sidebar{
position:fixed;
left:0;
top:0;
width:230px;
height:100%;
background:#2c3e50;
padding-top:20px;
}

.sidebar h2{
text-align:center;
color:#fff;
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:15px;
color:white;
text-decoration:none;
transition:.3s;
}

.sidebar a:hover{
background:#34495e;
}

.main{
margin-left:230px;
padding:30px;
display:flex;
justify-content:center;
}

.card{
background:#fff;
width:100%;
max-width:550px;
padding:30px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,.1);
}

.card h1{
text-align:center;
margin-bottom:25px;
color:#2c3e50;
}

label{
display:block;
margin-top:15px;
margin-bottom:5px;
font-weight:bold;
}

input,
select{
width:100%;
padding:12px;
border:1px solid #ccc;
border-radius:6px;
font-size:15px;
}

input:focus,
select:focus{
outline:none;
border-color:#3498db;
}

button{
width:100%;
margin-top:25px;
padding:12px;
background:#3498db;
color:white;
border:none;
border-radius:6px;
font-size:16px;
cursor:pointer;
transition:.3s;
}

button:hover{
background:#2980b9;
}

.success{
background:#d4edda;
color:#155724;
padding:12px;
margin-bottom:20px;
border-radius:6px;
text-align:center;
}

.error{
background:#f8d7da;
color:#721c24;
padding:12px;
margin-bottom:20px;
border-radius:6px;
text-align:center;
}

.back{
display:block;
text-align:center;
margin-top:20px;
text-decoration:none;
color:#3498db;
font-weight:bold;
}

@media(max-width:768px){

.sidebar{
position:relative;
width:100%;
height:auto;
}

.main{
margin-left:0;
padding:20px;
}

}

</style>

</head>

<body>

<div class="sidebar">

<h2>Admin Panel</h2>

<a href="dashboard.php">Dashboard</a>
<a href="members.php">Members</a>
<a href="users.php">Users</a>
<a href="add_user.php">Add User</a>
<a href="../logout.php">Logout</a>

</div>

<div class="main">

<div class="card">

<h1>Add User</h1>

<?php
if($success!=""){
    echo "<div class='success'>$success</div>";
}

if($error!=""){
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST">
    
<label>Full Name</label>

<input
type="text"
name="fullname"
required>

<label>Username</label>
<input
type="text"
name="username"
required>

<label>Password</label>
<input
type="password"
name="password"
required>

<label>Confirm Password</label>
<input
type="password"
name="confirm_password"
required>

<label>Role</label>

<select name="role" required>

<option value="">Select Role</option>

<option value="admin">
Administrator
</option>

<option value="user">
User
</option>

</select>

<button type="submit" name="add_user">
Create User
</button>

</form>

<a class="back" href="users.php">
← Back to Users
</a>

</div>

</div>

</body>

</html>