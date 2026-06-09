<?php
date_default_timezone_set('Asia/Riyadh');
// $url2 = 'https://appcims.com/whiteapp/';
$url2 = 'http://localhost/whiteappupdated/';

// Database credentials
// $servername = "localhost";  // Database server
// $username = "appciark_admin";         // Database username
// $password = "Anand@raj029";             // Database password
// $dbname = "appciark_whiteapp";  // Database name

$servername = "localhost";  // Database server
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "3rdparty";  // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . ". Check your database credentials and server status.");
}

// Set connection encoding
$conn->set_charset("utf8mb4");

// Test the connection
// if ($conn->ping()) {
//     echo "Connected successfully to the database!";
// } else {
//     echo "Failed to connect to the database.";
// }

// If the connection is successful, the script continues
?>