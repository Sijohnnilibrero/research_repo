<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user details
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .profile-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .profile-header h2 {
            margin-top: 0;
            color: #2c3e50;
        }
        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .info-item {
            font-size: 1.05rem;
        }
        .info-item strong {
            color: #2980b9;
        }

        .section-title {
            color: #2c3e50;
            margin: 40px 0 20px 0;
            border-bottom: 2px solid #2980b9;
            padding-bottom: 10px;
        }

        .research-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 25px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .card h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
        }
        .card .meta {
            margin: 8px 0;
            color: #555;
            font-size: 0.98rem;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-view { background: #2980b9; color: white; }
        .btn-edit { background: #f39c12; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-back {
            background: #7f8c8d;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 30px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .no-uploads {
            text-align: center;
            padding: 60px 20px;
            color: #777;
            font-size: 1.1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
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

    <h2>My Profile</h2>

    <div class="profile-header">
        <div class="profile-info">
            <div class="info-item">
                <strong>Name:</strong> <?= htmlspecialchars($user['name']) ?>
            </div>
            <div class="info-item">
                <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
            </div>
        </div>
    </div>

    <h2 class="section-title">My Research Uploads</h2>

    <div class="research-grid">

        <?php
        $research = $conn->query("SELECT * FROM research_papers 
                                  WHERE uploaded_by = $user_id 
                                  ORDER BY uploaded_at DESC");

        if($research->num_rows == 0):
        ?>
            <div class="no-uploads">
                <p>You haven't uploaded any research yet.</p>
                <a href="upload.php" class="btn btn-view" style="margin-top:15px;">Upload Your First Research</a>
            </div>
        <?php else: ?>
            <?php while($row = $research->fetch_assoc()): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p class="meta"><strong>Author:</strong> <?= htmlspecialchars($row['author'] ?? 'N/A') ?></p>
                    <p class="meta"><strong>Category:</strong> <?= htmlspecialchars($row['category'] ?? 'N/A') ?></p>
                    <p class="meta"><strong>Year:</strong> <?= htmlspecialchars($row['year'] ?? 'N/A') ?></p>

                    <div class="action-buttons">
                        <a href="research.php?id=<?= $row['id'] ?>" class="btn btn-view">View</a>
                        <a href="edit_research.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_research.php?id=<?= $row['id'] ?>" 
                           class="btn btn-delete"
                           onclick="return confirm('Are you sure you want to delete this research?')">
                            Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>

    <!-- Back Button -->
    <a href="index.php" class="btn-back">← Back to Dashboard</a>

</div>

</body>
</html>