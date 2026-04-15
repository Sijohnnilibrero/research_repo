<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

if(isset($_POST['upload'])){
    $title    = trim($_POST['title']);
    $author   = trim($_POST['author']);
    $abstract = trim($_POST['abstract']);
    $category = $_POST['category'];
    $year     = $_POST['year'];

    $file = $_FILES['file'];

    if($file['error'] == 0){
        $filename = time() . '_' . basename($file['name']);
        $folder = "uploads/" . $filename;
        $tmpname = $file['tmp_name'];

        if(move_uploaded_file($tmpname, $folder)){
            $uploaded_by = $_SESSION['user_id'];

            $sql = "INSERT INTO research_papers (title, author, abstract, category, year, file_path, uploaded_by, uploaded_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $title, $author, $abstract, $category, $year, $folder, $uploaded_by);

            if($stmt->execute()){
                $success = "✅ Research paper uploaded successfully!";
            } else {
                $error = "Database error: Failed to save record.";
            }
        } else {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "Please select a valid file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Research</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .main {
            padding: 30px 20px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .upload-container {
            background: white;
            padding: 35px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.12);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        .form-horizontal {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px 35px;
        }
        .form-group {
            margin-bottom: 5px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #34495e;
            font-size: 14.5px;
        }
        input[type="text"], 
        input[type="number"], 
        select {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }
        textarea {
            width: 100%;
            height: 85px;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            resize: vertical;
        }
        .full-width {
            grid-column: span 2;
        }
        .btn {
            padding: 13px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-upload {
            background: #2980b9;
            color: white;
            width: 100%;
            margin-top: 15px;
        }
        .btn-back {
            background: #7f8c8d;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
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
        <a href="upload.php" class="active">UPLOAD</a>
        <a href="profile.php">PROFILE</a>
    </div>
    <div class="nav-right">
        <span class="welcome">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="logout.php">LOGOUT</a>
    </div>
</div>

<div class="main">
    <div class="upload-container">
        <h2>Upload Research Paper</h2>

        <?php if($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="form-horizontal">
            
            <div class="form-group">
                <label>Research Title</label>
                <input type="text" name="title" placeholder="Enter research title" required>
            </div>

            <div class="form-group">
                <label>Author Name</label>
                <input type="text" name="author" placeholder="Your name or authors" required>
            </div>

            <div class="form-group full-width">
                <label>Abstract</label>
                <textarea name="abstract" placeholder="Brief summary of your research..." required></textarea>
            </div>

            <div class="form-group">
                <label>Select Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php
                    $result = $conn->query("SELECT * FROM categories");
                    while($row = $result->fetch_assoc()){
                        echo "<option value='".htmlspecialchars($row['name'])."'>".htmlspecialchars($row['name'])."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Year</label>
                <input type="number" name="year" placeholder="e.g. 2026" min="2000" max="2030" required>
            </div>

            <div class="form-group full-width">
                <label>Choose File (PDF, DOCX recommended)</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx" required>
            </div>

            <div class="form-group full-width">
                <button type="submit" name="upload" class="btn btn-upload">Upload Research Paper</button>
            </div>
        </form>

        <div style="text-align: center;">
            <a href="index.php" class="btn-back">← Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>