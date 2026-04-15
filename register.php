<?php
include("config/db.php");

if(isset($_POST['register'])){

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password)
VALUES ('$name','$email','$password')";

if($conn->query($sql) === TRUE){
    echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
}else{
    echo "Error: " . $conn->error;
}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>

body{
    font-family: Arial, sans-serif;
    background: linear-gradient(120deg,#2980b9,#6dd5fa);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:0;
}

.container{
    background:white;
    padding:40px;
    width:350px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}

.container h2{
    margin-bottom:20px;
}

.container input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:14px;
}

.container input:focus{
    border-color:#2980b9;
    outline:none;
}

.container button{
    width:100%;
    padding:10px;
    border:none;
    background:#2980b9;
    color:white;
    font-size:16px;
    border-radius:5px;
    cursor:pointer;
}

.container button:hover{
    background:#1f6391;
}

.login-link{
    margin-top:15px;
    font-size:14px;
}

.login-link a{
    color:#2980b9;
    text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<h2>User Registration</h2>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="register">Register</button>

<div class="login-link">
Already have an account? <a href="login.php">Login</a>
</div>

</form>

</div>

</body>
</html>