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

$sql = "SELECT * FROM members WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: members.php");
    exit();
}

$member = $result->fetch_assoc();

if (isset($_POST['update'])) {

    $fullname = trim($_POST['fullname']);
    $gender = $_POST['gender'];
    $age = intval($_POST['age']);
    $address = trim($_POST['address']);
    $contact = trim($_POST['contact']);

    $update = $conn->prepare("UPDATE members SET fullname=?, gender=?, age=?, address=?, contact=? WHERE id=?");
    $update->bind_param(
        "ssissi",
        $fullname,
        $gender,
        $age,
        $address,
        $contact,
        $id
    );

    if ($update->execute()) {
        $success = "Member updated successfully.";
    } else {
        $error = "Failed to update member.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Member</title>

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
color:white;
text-align:center;
margin-bottom:30px;
}

.sidebar a{
display:block;
color:white;
padding:15px;
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

.card{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,.1);
max-width:700px;
}

h1{
margin-bottom:20px;
}

label{
display:block;
margin-top:15px;
font-weight:bold;
}

input,select,textarea{
width:100%;
padding:12px;
margin-top:6px;
border:1px solid #ccc;
border-radius:6px;
font-size:15px;
}

textarea{
resize:none;
height:100px;
}

button{
margin-top:20px;
padding:12px 25px;
border:none;
background:#3498db;
color:white;
border-radius:6px;
cursor:pointer;
font-size:16px;
}

button:hover{
background:#2980b9;
}

.success{
background:#d4edda;
color:#155724;
padding:10px;
border-radius:5px;
margin-bottom:15px;
}

.error{
background:#f8d7da;
color:#721c24;
padding:10px;
border-radius:5px;
margin-bottom:15px;
}

.back{
display:inline-block;
margin-top:15px;
text-decoration:none;
color:#3498db;
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
<a href="../logout.php">Logout</a>

</div>

<div class="main">

<div class="card">

<h1>Edit Member</h1>

<?php
if(isset($success)){
    echo "<div class='success'>$success</div>";
}

if(isset($error)){
    echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<label>Full Name</label>
<input type="text" name="firstname"
value="<?php echo htmlspecialchars($member['firstname']); ?>" required>

<input type="text" name="lastname"
value="<?php echo htmlspecialchars($member['lastname']); ?>" required>

<label>Gender</label>

<select name="gender">

<option value="Male"
<?php if($member['gender']=="Male") echo "selected"; ?>>
Male
</option>

<option value="Female"
<?php if($member['gender']=="Female") echo "selected"; ?>>
Female
</option>

</select>

<label>Birth date</label>

<input
type="date"
name="birthdate"
value="<?php echo $member['birthdate']; ?>"
required>

<label>Address</label>

<textarea
name="address"
required><?php echo htmlspecialchars($member['address']); ?></textarea>

<label>Contact Number</label>

<input
type="text"
name="contact"
value="<?php echo htmlspecialchars($member['contact']); ?>"
required>

<button name="update">Update Member</button>

</form>

<a class="back" href="members.php">← Back to Members</a>

</div>

</div>

</body>
</html>