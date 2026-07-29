<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

if(isset($_POST['save'])){

    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);

    $photo = "";

    if(isset($_FILES['photo']) && $_FILES['photo']['name'] != ""){

        $filename = time() . "_" . basename($_FILES["photo"]["name"]);
        $target = "../uploads/" . $filename;

        if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target)){
            $photo = $filename;
        }

    }

    $stmt = $conn->prepare("INSERT INTO members(photo,firstname,lastname,gender,birthdate,contact,address)
    VALUES(?,?,?,?,?,?,?)");

    $stmt->bind_param(
        "sssssss",
        $photo,
        $firstname,
        $lastname,
        $gender,
        $birthdate,
        $contact,
        $address
    );

    if($stmt->execute()){

        $message = "Member successfully registered.";

    }else{

        $message = "Failed to register member.";

    }

}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Member</title>

<style>

*{

margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI,sans-serif;

}

body{

background:#edf2f7;

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

}

.sidebar a:hover{

background:#3498db;

}

.main{

margin-left:260px;
padding:30px;

}

.container{

max-width:700px;
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,.15);

}

.container h2{

margin-bottom:20px;
color:#2c3e50;

}

.success{

background:#d4edda;
color:#155724;
padding:12px;
border-radius:8px;
margin-bottom:20px;

}

.row{

margin-bottom:18px;

}

label{

display:block;
margin-bottom:8px;
font-weight:bold;

}

input[type=text],
input[type=date],
textarea,
select{

width:100%;
padding:12px;
border:1px solid #ccc;
border-radius:8px;
font-size:15px;

}

textarea{

height:100px;
resize:none;

}

input[type=file]{

padding:10px;

}

button{

background:#3498db;
color:white;
padding:12px 25px;
border:none;
border-radius:8px;
cursor:pointer;
font-size:16px;

}

button:hover{

background:#2980b9;

}

.back{

display:inline-block;
margin-left:10px;
padding:12px 25px;
background:#6c757d;
color:white;
text-decoration:none;
border-radius:8px;

}

.back:hover{

background:#555;

}

img{

margin-top:10px;
width:120px;
height:120px;
border-radius:50%;
object-fit:cover;
display:none;

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

<div class="container">

<h2>Register Member</h2>

<?php

if($message!=""){

echo "<div class='success'>$message</div>";

}

?>

<form method="POST" enctype="multipart/form-data">

<div class="row">

<label>First Name</label>

<input
type="text"
name="firstname"
required>

</div>

<div class="row">

<label>Last Name</label>

<input
type="text"
name="lastname"
required>

</div>

<div class="row">

<label>Gender</label>

<select name="gender" required>

<option value="">Select Gender</option>

<option>Male</option>

<option>Female</option>

</select>

</div>

<div class="row">

<label>Birthdate</label>

<input
type="date"
name="birthdate"
required>

</div>

<div class="row">

<label>Contact Number</label>

<input
type="text"
name="contact">

</div>

<div class="row">

<label>Address</label>

<textarea
name="address"
required></textarea>

</div>

<div class="row">

<label>Member Photo</label>

<input
type="file"
name="photo"
accept="image/*"
onchange="previewImage(event)">

<img id="preview">

</div>

<button
type="submit"
name="save">

Register Member

</button>

<a
href="members.php"
class="back">

Back

</a>

</form>

</div>

</div>

<script>

function previewImage(event){

const img=document.getElementById("preview");

img.src=URL.createObjectURL(event.target.files[0]);

img.style.display="block";

}

</script>

</body>

</html>