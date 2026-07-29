```php
<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$totalMembers = $conn->query("SELECT COUNT(*) AS total FROM members")->fetch_assoc()['total'];
$totalAdmins = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='admin'")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'")->fetch_assoc()['total'];

$members = $conn->query("SELECT * FROM members ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI,sans-serif;
}

body{
    background:#f4f6f9;
}

.sidebar{

    width:250px;
    background:#2c3e50;
    position:fixed;
    top:0;
    left:0;
    height:100%;
    color:white;
    padding:20px;

}

.sidebar h2{

    text-align:center;
    margin-bottom:30px;

}

.sidebar a{

    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    margin:8px 0;
    border-radius:8px;
    transition:.3s;

}

.sidebar a:hover{

    background:#3498db;

}

.main{

    margin-left:270px;
    padding:30px;

}

.cards{

    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:30px;

}

.card{

    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
    text-align:center;

}

.card h3{

    color:#777;
    margin-bottom:10px;

}

.card h1{

    color:#3498db;

}

table{

    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,.1);

}

table th{

    background:#3498db;
    color:white;
    padding:12px;

}

table td{

    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:center;

}

img{

    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:50%;

}

.btn{

    background:#3498db;
    color:white;
    text-decoration:none;
    padding:8px 15px;
    border-radius:6px;

}

.btn:hover{

    background:#2980b9;

}

.top{

display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;

}

</style>

</head>

<body>

<div class="sidebar">

<h2>Member System</h2>

<a href="dashboard.php">Dashboard</a>

<a href="members.php">Members</a>

<a href="users.php">Users</a>

<a href="add_users.php">Add User</a>

<a href="../logout.php">Logout</a>

</div>

<div class="main">

<div class="top">

<h2>Welcome, <?php echo $_SESSION['username']; ?></h2>

<a href="add_members.php" class="btn">+ Register Member</a>

</div>

<div class="cards">

<div class="card">

<h3>Total Members</h3>

<h1><?php echo $totalMembers; ?></h1>

</div>

<div class="card">

<h3>Total Admins</h3>

<h1><?php echo $totalAdmins; ?></h1>

</div>

<div class="card">

<h3>Total Users</h3>

<h1><?php echo $totalUsers; ?></h1>

</div>

</div>

<h2 style="margin-bottom:15px;">Latest Registered Members</h2>

<table>

<tr>

<th>Photo</th>

<th>First Name</th>

<th>Last Name</th>

<th>Gender</th>

<th>Contact</th>

<th>Address</th>

</tr>

<?php

while($row=$members->fetch_assoc()){

?>

<tr>

<td>

<?php

if($row['photo']==""){

echo "No Photo";

}else{

?>

<img src="../uploads/<?php echo $row['photo']; ?>">

<?php } ?>

</td>

<td><?php echo $row['firstname']; ?></td>

<td><?php echo $row['lastname']; ?></td>

<td><?php echo $row['gender']; ?></td>

<td><?php echo $row['contact']; ?></td>

<td><?php echo $row['address']; ?></td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>
```
