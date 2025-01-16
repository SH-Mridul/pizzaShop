<?php
// Database configuration
$servername = "localhost";  // Server name (usually localhost for local setups)
$username = "root";         // MySQL username (default is root for XAMPP)
$password = "";             // MySQL password (leave empty if there is no password)
$dbname = "pizza_shop_db";       // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>