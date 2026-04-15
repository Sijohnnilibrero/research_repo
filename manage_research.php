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
    <title>Manage Research - Admin</title>
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .container {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 40px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #2980b9;
            color: white;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .btn {
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-right: 6px;
        }
        .btn-view { 
            background: #2980b9; 
            color: white; 
        }
        .btn-download { 
            background: #27ae60; 
            color: white; 
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📊 Manage Research</h1>
    <p>All research papers and uploaded files</p>

    <!-- Research Papers from Database -->
    <h2>📚 Research Papers (Database)</h2>
    
    <?php
    $sql = "SELECT * FROM research_papers ORDER BY created_at DESC";
    $result = $conn->query($sql);
    ?>

    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Uploaded By</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['category'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['uploaded_by'] ?? 'Unknown'); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="view_paper.php?id=<?php echo $row['id']; ?>" class="btn btn-view">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center; padding:40px;">No research papers yet.</td></tr>
        <?php endif; ?>
    </table>

    <!-- Uploaded Files -->
    <h2>📁 Uploaded Files (Uploads Folder)</h2>

    <?php
    $upload_dir = "uploads/";
    if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $files = array_diff(scandir($upload_dir), ['.', '..']);
    ?>

    <table>
        <tr>
            <th>File Name</th>
            <th>Size</th>
            <th>Date Uploaded</th>
            <th>Actions</th>
        </tr>
        
        <?php if(count($files) > 0): ?>
            <?php foreach($files as $file): 
                $file_path = $upload_dir . $file;
                $file_size = round(filesize($file_path) / 1024, 2) . " KB";
                $file_time = date("M d, Y H:i", filemtime($file_path));
            ?>
            <tr>
                <td><?php echo htmlspecialchars($file); ?></td>
                <td><?php echo $file_size; ?></td>
                <td><?php echo $file_time; ?></td>
                <td>
                    <!-- View Button - Opens in new tab (best effort) -->
                    <a href="<?php echo $file_path; ?>" 
                       target="_blank" 
                       class="btn btn-view">👁️ View</a>
                    
                    <!-- Download Button -->
                    <a href="<?php echo $file_path; ?>" 
                       download 
                       class="btn btn-download">⬇️ Download</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center; padding:40px;">No files uploaded yet.</td></tr>
        <?php endif; ?>
    </table>

    <br>
    <a href="admin.php" style="font-size: 16px; color: #2980b9;">← Back to Admin Dashboard</a>
</div>

</body>
</html>