<?php

session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){

$id = $_GET['id'];

$sql = "DELETE FROM research_papers WHERE id=$id";

if($conn->query($sql)){
    header("Location: index.php");
}else{
    echo "Delete failed.";
}

}

?>