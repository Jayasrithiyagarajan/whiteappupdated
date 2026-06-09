<?php
// session_start();
 // Ensure session is started

// Ensure user is logged in
if (!isset($_SESSION['role']) || !isset($_SESSION['username'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the username

// Database connection
$servername = "localhost";  
$username_db = "appciark_admin";       
$password = "Anand@raj029";              
$dbname = "appciark_whiteapp";          

$conn2 = new mysqli($servername, $username_db, $password, $dbname);

// Check the connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}

// Mark all notifications as read for the current user
$query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$stmt = $conn2->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->close();

// Close the database connection
$conn2->close();

// Redirect back to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>