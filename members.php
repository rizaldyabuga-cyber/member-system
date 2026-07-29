<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    $stmt = $conn->prepare("SELECT * FROM members
        WHERE firstname LIKE ?
        OR lastname LIKE ?
        OR contact LIKE ?
        ORDER BY id DESC");

    $like = "%".$search."%";
    $stmt->bind_param("sss",$like,$like,$like);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $result = $conn->query("SELECT * FROM members ORDER BY id DESC");

}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Members</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI,sans-serif;
}

body{
background:#eef2f7;
}

.sidebar{

position:fixed;
left:0;
top:0;
width:240px;
height:100%;
background:#2c3e50;
padding:20px;

}

.sidebar h2{

color:white;
text-align:center;
margin-bottom:30px;

}

.sidebar a{

display:block;
text-decoration:none;
color:white;
padding:12px;
margin-bottom:10px;
border-radius:8px;
transition:.3s;

}

.sidebar a:hover{

background:#3498db;

}

.main{

margin-left:260px;
padding:30px;

}

.top{

display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;

}

.btn{

background:#3498db;
color:white;
padding:10px 18px;
border-radius:8px;
text-decoration:none;
font-weight:bold;

}

.btn:hover{

background:#2980b9;

}

.search-box{

display:flex;
gap:10px;
margin-bottom:20px;

}

.search-box input{

width:350px;
padding:10px;
border:1px solid #ccc;
border-radius:8px;
outline:none;

}

.search-box button{

padding:10px 20px;
border:none;
background:#27ae60;
color:white;
border-radius:8px;
cursor:pointer;

}

.search-box button:hover{

background:#1e8449;

}

table{

width:100%;
border-collapse:collapse;
background:white;
border-radius:12px;
overflow:hidden;
box-shadow:0 5px 15px rgba(0,0,0,.1);

}

table th{

background:#3498db;
color:white;
padding:14px;

}

table td{

padding:12px;
text-align:center;
border-bottom:1px solid #ddd;

}

table tr:hover{

background:#f9f9f9;

}

img{

width:70px;
height:70px;
border-radius:50%;
object-fit:cover;

}

.edit{

background:#f39c12;
color:white;
padding:8px 14px;
text-decoration:none;
border-radius:6px;

}

.delete{

background:#e74c3c;
color:white;
padding:8px 14px;
text-decoration:none;
border-radius:6px;

}

.edit:hover{

background:#d68910;

}

.delete:hover{

background:#c0392b;

}

</style>

</head>

<body>

<div class="sidebar">

<h2>Member System</h2>

<a href="dashboard.php">Dashboard</a>

<a href="members.php">Members</a>

<a href="users.php">Users</a>

<a href="../logout.php">Logout</a>

</div>

<div class="main">

<div class="top">

<h2>Manage Members</h2>

<a href="add_members.php" class="btn">
+ Register Member
</a>

</div>

<form method="GET">

<div class="search-box">

<input
type="text"
name="search"
placeholder="Search member..."
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">

Search

</button>

</div>

</form>

<table>

<tr>

<th>ID</th>

<th>Photo</th>

<th>First Name</th>

<th>Last Name</th>

<th>Gender</th>

<th>Birthdate</th>

<th>Contact</th>

<th>Address</th>

<th>Action</th>

</tr>

<?php

if($result->num_rows>0)

while($row=$result->fetch_assoc()){

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>

<?php

if($row['photo']==""){

echo "No Photo";

}else{

?>

<img src="../uploads/<?php echo $row['photo']; ?>">

<?php

}

?>

</td>

<td><?php echo htmlspecialchars($row['firstname']); ?></td>

<td><?php echo htmlspecialchars($row['lastname']); ?></td>

<td><?php echo $row['gender']; ?></td>

<td><?php echo $row['birthdate']; ?></td>

<td><?php echo htmlspecialchars($row['contact']); ?></td>

<td><?php echo htmlspecialchars($row['address']); ?></td>

<td>

<a
class="edit"
href="edit_members.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a
class="delete"
onclick="return confirm('Delete this member?')"
href="delete_members.php?id=<?php echo $row['id']; ?>">
Delete
</a>

</td>

</tr>

<?php

}