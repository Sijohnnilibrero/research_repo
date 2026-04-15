<?php
session_start();
include("config/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Repository</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Additional modern styles - you can move these to style.css later */
        .main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .filter-bar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        .filter-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        .filter-form input[type="text"],
        .filter-form select,
        .filter-form input[type="number"] {
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            min-width: 220px;
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
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .card h3 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            font-size: 1.35rem;
        }
        .card p {
            margin: 8px 0;
            color: #555;
        }
        .card .meta {
            font-size: 0.95rem;
            color: #777;
        }
        .view-btn {
            margin-top: 18px;
            display: inline-block;
            background: #2980b9;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .view-btn:hover {
            background: #1f6da8;
        }
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #777;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<!-- NAVBAR (your existing navbar - kept as is) -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo">Research Repository</div>
        <a href="index.php" class="active">HOME</a>
    </div>
    <div class="nav-right">
        <?php if(isset($_SESSION['user_name'])): ?>
            <span class="welcome">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="profile.php">PROFILE</a>
            <a href="logout.php">LOGOUT</a>
        <?php else: ?>
            <a href="login.php">LOG IN</a>
            <a href="register.php">REGISTER</a>
        <?php endif; ?>
    </div>
</div>

<?php if(!isset($_SESSION['user_name'])): ?>

    <!-- NOTICE BOX + LANDING (your existing code) -->
    <div class="notice-box"> ... </div>
    <div class="landing"> ... </div>

<?php else: ?>

    <div class="main">
        <h1 class="page-title">Research Papers</h1>

        <!-- SEARCH + FILTER BAR -->
        <div class="filter-bar">
            <form method="GET" class="filter-form">
                <input type="text" name="query" placeholder="Search research..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                
                <select name="category">
                    <option value="">All Categories</option>
                    <?php
                    $cat_result = $conn->query("SELECT * FROM categories");
                    while($cat = $cat_result->fetch_assoc()){
                        $selected = (isset($_GET['category']) && $_GET['category'] == $cat['name']) ? 'selected' : '';
                        echo "<option value='".htmlspecialchars($cat['name'])."' $selected>".htmlspecialchars($cat['name'])."</option>";
                    }
                    ?>
                </select>

                <input type="number" name="year" placeholder="Year" value="<?= htmlspecialchars($_GET['year'] ?? '') ?>">
                
                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <!-- RESEARCH GRID -->
        <div class="research-grid">

            <?php
            $query = "SELECT * FROM research_papers WHERE 1=1";

            if(!empty($_GET['query'])){
                $q = $conn->real_escape_string($_GET['query']);
                $query .= " AND (title LIKE '%$q%' OR author LIKE '%$q%')";
            }

            if(!empty($_GET['category'])){
                $category = $conn->real_escape_string($_GET['category']);
                $query .= " AND category='$category'";
            }

            if(!empty($_GET['year'])){
                $year = (int)$_GET['year'];
                $query .= " AND year='$year'";
            }

            $query .= " ORDER BY uploaded_at DESC";

            $result = $conn->query($query);

            if($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p class="meta"><strong>Author:</strong> <?= htmlspecialchars($row['author'] ?? 'N/A') ?></p>
                    <p class="meta"><strong>Category:</strong> <?= htmlspecialchars($row['category'] ?? 'N/A') ?></p>
                    <p class="meta"><strong>Year:</strong> <?= htmlspecialchars($row['year'] ?? 'N/A') ?></p>
                    
                    <a href="research.php?id=<?= $row['id'] ?>" class="view-btn">View Research</a>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="no-results">
                    <p>No research papers found matching your criteria.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

<?php endif; ?>

</body>
</html>