<?php
session_start();
require 'database.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            header("Location: home.php"); 
            exit(); 
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="source/css/login.css"> 
    <link rel="stylesheet" href="source/css/main.css"> 

</head>
<body>
   <div class="login_main">
      <div class="login_left">
    
            <h2>Login</h2>
            <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p class="signup_link">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    </div>
    <div class="login_right">
        <img src="source/Images/logo.png" alt="">
    </div>
    </div>
</body>
</html>

