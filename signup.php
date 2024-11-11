<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        // Redirect to login.php after signup is successful
        header("Location: login.php");
        exit(); // Ensure the script stops after redirection
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="source/css/signup.css"> 
    <link rel="stylesheet" href="source/css/main.css"> 

</head>
<body>
    <div class="center fade"> 
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p class="signup_link">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>