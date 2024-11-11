<?php
session_start();
require 'database.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a statement to select user data based on the provided username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password using password_verify()
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username']; 

            // Redirect to the home page
            header("Location: home.php");
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid password.";
        }
    } else {
        // User not found
        $error_message = "User not found.";
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

