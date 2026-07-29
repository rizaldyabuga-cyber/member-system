<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: members.php");
    exit();
}

$id = intval($_GET['id']);

// Get member information
$stmt = $conn->prepare("SELECT fullname FROM members WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: members.php");
    exit();
}

$member = $result->fetch_assoc();

if (isset($_POST['delete'])) {

    $delete = $conn->prepare("DELETE FROM members WHERE id=?");
    $delete->bind_param("i", $id);

    if ($delete->execute()) {
        header("Location: members.php?deleted=1");
        exit();
    } else {
        $error = "Failed to delete member.";
    }
}

if (isset($_POST['cancel'])) {
    header("Location: members.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delete Member</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI,Tahoma,sans-serif;
}

body{
background:#f5f7fb;
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
color:#fff;
text-align:center;
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
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
}

.card{
background:#fff;
padding:35px;
width:100%;
max-width:550px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,.1);
text-align:center;
}

.card h1{
margin-bottom:20px;
color:#dc3545;
}

.card p{
font-size:18px;
margin-bottom:25px;
line-height:1.6;
}

.member-name{
font-weight:bold;
color:#2c3e50;
}

.btn-group{
display:flex;
justify-content:center;
gap:15px;
flex-wrap:wrap;
}

button{
padding:12px 25px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:16px;
transition:.3s;
}

.delete-btn{
background:#dc3545;
color:white;
}

.delete-btn:hover{
background:#b52a37;
}

.cancel-btn{
background:#6c757d;
color:white;
}

.cancel-btn:hover{
background:#545b62;
}

.error{
background:#f8d7da;
color:#721c24;
padding:12px;
margin-bottom:20px;
border-radius:6px;
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

.btn-group{
flex-direction:column;
}

button{
width:100%;
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

<div class="card">

<h1>Delete Member</h1>

<?php
if(isset($error)){
    echo "<div class='error'>$error</div>";
}
?>

<p>
Are you sure you want to delete
<br><br>
<span class="member-name">
<?php echo htmlspecialchars($member['fullname']); ?>
</span>
?
</p>

<form method="POST">

<div class="btn-group">

<button type="submit" name="delete" class="delete-btn">
Delete
</button>

<button type="submit" name="cancel" class="cancel-btn">
Cancel
</button>

</div>

</form>

</div>

</div>

</body>
</html>