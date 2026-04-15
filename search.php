<?php
session_start();
include("config/db.php");

$query = "";

if(isset($_GET['query'])){
$query = $_GET['query'];
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Search Results</title>
</head>

<body>

<h1>Search Results</h1>

<a href="index.php">Back to Home</a>

<hr>

<?php

$sql = "SELECT * FROM research_papers 
        WHERE title LIKE '%$query%' 
        OR author LIKE '%$query%'";

$result = $conn->query($sql);

if($result->num_rows > 0){

while($row = $result->fetch_assoc()){

echo "<h3>".$row['title']."</h3>";
echo "<p>Author: ".$row['author']."</p>";
echo "<p>Year: ".$row['year']."</p>";
echo "<a href='research.php?id=".$row['id']."'>View Research</a>";
echo "<hr>";

}

}else{

echo "No research found.";

}

?>

</body>
</html>