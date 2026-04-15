<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$conn->query("DELETE FROM research_papers WHERE id=$id AND uploaded_by=$user_id");

header("Location: profile.php");
?>