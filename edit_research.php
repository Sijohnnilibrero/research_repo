<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch current research data
$result = $conn->query("SELECT * FROM research_papers WHERE id = $id AND uploaded_by = $user_id");
$row = $result->fetch_assoc();

if(!$row){
    header("Location: profile.php");
    exit();
}

$success = "";
$error = "";

if(isset($_POST['update'])){
    $title    = trim($_POST['title']);
    $author   = trim($_POST['author']);
    $abstract = trim($_POST['abstract']);
    $category = trim($_POST['category']);
    $year     = $_POST['year'];

    $sql = "UPDATE research_papers 
            SET title = ?, author = ?, abstract = ?, category = ?, year = ? 
            WHERE id = ? AND uploaded_by = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $title, $author, $abstract, $category, $year, $id, $user_id);

    if($stmt->execute()){
        $success = "✅ Research updated successfully!";
        // Refresh the data
        $result = $conn->query("SELECT * FROM research_papers WHERE id = $id");
        $row = $result->fetch_assoc();
    } else {
        $error = "Failed to update research.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Research</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .main {
            padding: 40px 20px;
            max-width: 1000px;           /* Wider container */
            margin: 0 auto;
        }
        .edit-container {
            background: white;
            padding: 45px 50px;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 35px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px 40px;
        }
        .form-group {
            margin-bottom: 8px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }
        input[type="text"], 
        input[type="number"], 
        textarea {
            width: 100%;
            padding: 13px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15.5px;
        }
        textarea {
            height: 140px;
            resize: vertical;
        }
        .full-width {
            grid-column: span 2;
        }
        .btn {
            padding: 14px 36px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-update {
            background: #f39c12;
            color: white;
            width: 100%;
            margin-top: 15px;
        }
        .btn-back {
            background: #7f8c8d;
            color: white;
            text-decoration: none;
            padding: 13px 30px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 25px;
        }
        .success { color: #27ae60; font-weight: bold; text-align: center; margin: 15px 0; }
        .error { color: #e74c3c; font-weight: bold; text-align: center; margin: 15px 0; }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo">Research Repository</div>
        <a href="index.php">HOME</a>
        <a href="upload.php">UPLOAD</a>
        <a href="profile.php" class="active">PROFILE</a>
    </div>
    <div class="nav-right">
        <span class="welcome">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="logout.php">LOGOUT</a>
    </div>
</div>

<div class="main">
    <div class="edit-container">
        <h2>Edit Research</h2>

        <?php if($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            
            <div class="form-group">
                <label>Research Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
            </div>

            <div class="form-group">
                <label>Author Name</label>
                <input type="text" name="author" value="<?= htmlspecialchars($row['author']) ?>" required>
            </div>

            <div class="form-group full-width">
                <label>Abstract</label>
                <textarea name="abstract" required><?= htmlspecialchars($row['abstract']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>" required>
            </div>

            <div class="form-group">
                <label>Year</label>
                <input type="number" name="year" value="<?= htmlspecialchars($row['year']) ?>" required>
            </div>

            <div class="form-group full-width">
                <button type="submit" name="update" class="btn btn-update">Update Research</button>
            </div>
        </form>

        <!-- Back Button -->
        <div style="text-align: center;">
            <a href="profile.php" class="btn-back">← Back to My Profile</a>
        </div>
    </div>
</div>

</body>
</html>