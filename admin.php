<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .dashboard {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        .welcome {
            background: linear-gradient(120deg, #2980b9, #6dd5fa);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .cards-horizontal {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 25px;
            padding: 10px 0;
        }
        .admin-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            min-width: 280px;
            flex-shrink: 0;
        }
        .admin-card h3 {
            margin: 15px 0 8px 0;
            color: #333;
        }
        .admin-card p {
            color: #666;
            font-size: 14.5px;
            margin-bottom: 15px;
        }
        .admin-card a {
            display: inline-block;
            padding: 12px 25px;
            background: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .admin-card a:hover {
            background: #1f6da8;
        }
    </style>
</head>
<body>

    <div class="dashboard">
        <div class="welcome">
            <h1>👨‍💼 Admin Dashboard</h1>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
        </div>

        <div class="cards-horizontal">
            <div class="admin-card">
                <h3>📊 Manage Research</h3>
                <p>View, approve, delete research papers and uploaded files</p>
                <a href="manage_research.php">Manage Research</a>
            </div>

            <div class="admin-card">
                <h3>👥 Manage Users</h3>
                <p>View and manage all registered users</p>
                <a href="users.php">Manage Users</a>
            </div>

            <div class="admin-card">
                <h3>⚙️ System</h3>
                <p>Settings, Reports & Logs</p>
                <a href="#">System Settings</a>
            </div>
        </div>

        <br><br>
        <a href="logout.php" style="color: #e74c3c; font-weight: bold; text-decoration: none;">Logout</a>
    </div>

</body>
</html>