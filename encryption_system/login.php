<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_user";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = md5($_POST['password']); // Encrypt password using MD5
    
    $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $user;  // Start session on successful login
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
$conn->close();
?>

