<?php
session_start();
include("config/db.php");

if(isset($_POST['login'])){
    $identifier = trim($_POST['email']); 
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ? OR name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        
        if(password_verify($password, $row['password'])){
            
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];   // ← Important

            // Redirect based on role
            if($row['role'] === 'admin'){
                header("Location: admin.php");   // Change this if your admin page has different name
                exit();
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Login</h2>
            <p class="subtitle">Access your account</p>
            
            <?php if(isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" 
                       name="email" 
                       placeholder="Email address or Username" 
                       required>
                
                <input type="password" 
                       name="password" 
                       placeholder="Password" 
                       required>
                
                <button type="submit" name="login">Login</button>
            </form>
            
            <p class="switch">
                Don't have an account? <a href="register.php">Register now nigga</a>
            </p>
        </div>
    </div>
</body>
</html>