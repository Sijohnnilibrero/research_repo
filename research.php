<?php
session_start();
include("config/db.php");

if(!isset($_GET['id'])){
    echo "Research not found.";
    exit();
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM research_papers WHERE id=$id");

if($result->num_rows == 0){
    echo "Research not found.";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>

<title><?php echo $row['title']; ?></title>
<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="navbar">

<div class="logo">
Research Repository
</div>

<div class="nav-links">

<a href="index.php">Home</a>

<?php
if(isset($_SESSION['user_name'])){
echo "<span>Welcome, ".$_SESSION['user_name']."</span>";
echo "<a href='upload.php'>Upload</a>";
echo "<a href='logout.php'>Logout</a>";
}else{
echo "<a href='login.php'>Login</a>";
echo "<a href='register.php'>Register</a>";
}
?>

</div>

</div>


<div class="container">

<div class="research-view">

<h1><?php echo $row['title']; ?></h1>

<p><strong>Author:</strong> <?php echo $row['author']; ?></p>

<p><strong>Category:</strong> <?php echo $row['category']; ?></p>

<p><strong>Year:</strong> <?php echo $row['year']; ?></p>

<h3>Abstract</h3>

<p><?php echo $row['abstract']; ?></p>

<br>

<a href="<?php echo $row['file_path']; ?>" download>
<button>Download Research</button>
</a>

<?php
if(isset($_SESSION['user_id'])){
?>

<br><br>

<a href="delete.php?id=<?php echo $row['id']; ?>" 
onclick="return confirm('Are you sure you want to delete this research?');">
<button style="background:red;">Delete Research</button>
</a>

<?php
}
?>

<br><br>

<iframe 
src="<?php echo $row['file_path']; ?>" 
width="100%" 
height="600px">
</iframe>

</div>

</div>

</body>

</html>