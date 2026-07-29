<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>

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
color:#fff;
text-decoration:none;
transition:.3s;
}

.sidebar a:hover{
background:#34495e;
}

.main{
margin-left:230px;
padding:30px;
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
flex-wrap:wrap;
gap:15px;
}

.header h1{
color:#2c3e50;
}

.table-container{
background:#fff;
border-radius:10px;
overflow-x:auto;
box-shadow:0 5px 15px rgba(0,0,0,.1);
}

table{
width:100%;
border-collapse:collapse;
min-width:700px;
}

table th{
background:#3498db;
color:white;
padding:15px;
text-align:left;
}

table td{
padding:15px;
border-bottom:1px solid #eee;
}

table tr:hover{
background:#f8f9fa;
}

.role{
padding:5px 12px;
border-radius:20px;
font-size:14px;
font-weight:bold;
display:inline-block;
}

.admin{
background:#d4edda;
color:#155724;
}

.user{
background:#d1ecf1;
color:#0c5460;
}

.badge{
background:#28a745;
color:white;
padding:5px 10px;
border-radius:20px;
font-size:13px;
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

.header{
flex-direction:column;
align-items:flex-start;
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
<a href="../logout.php">Logout</a>

</div>

<div class="main">

<div class="header">
<h1>System Users</h1>

<span class="badge">
Total Users:
<?php echo $result->num_rows; ?>
</span>

</div>

<div class="table-container">

<table>

<thead>

<tr>
<th>ID</th>
<th>Username</th>
<th>Role</th>
</tr>

</thead>

<tbody>

<?php
if($result->num_rows>0){

while($row=$result->fetch_assoc()){
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo htmlspecialchars($row['username']); ?></td>

<td>

<?php
if($row['role']=="admin"){
?>

<span class="role admin">
Administrator
</span>

<?php
}else{
?>

<span class="role user">
User
</span>

<?php
}
?>

</td>

</tr>

<?php
}

}else{
?>

<tr>

<td colspan="3" style="text-align:center;">
No users found.
</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</body>
</html>