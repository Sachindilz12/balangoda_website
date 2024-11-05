<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost'; 
$db = 'task_db'; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
