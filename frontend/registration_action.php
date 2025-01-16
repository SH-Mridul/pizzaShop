<?php
// Start the session to handle notifications
session_start();

// Include database connection file
include('database_connection.php');

// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$cpass = $_POST['cpass'];

// Basic validation
if (empty($name) || empty($email) || empty($pass) || empty($cpass)) {
    $_SESSION['notification'] = "All fields are required.";
    header("Location: index.php");
    exit();
}

// Check if email already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['notification'] = "Email already exists. Please use a different email.";
    header("Location: index.php");
    exit();
}

// Check if passwords match
if ($pass !== $cpass) {
    $_SESSION['notification'] = "Passwords do not match.";
    header("Location: index.php");
    exit();
}

// Hash the password with MD5
$hashed_pass = md5($pass);

// Insert the user into the database
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $hashed_pass);

if ($stmt->execute()) {
    $_SESSION['notification'] = "Registration successful. You can now log in.";
    header("Location: index.php");
} else {
    $_SESSION['notification'] = "An error occurred during registration. Please try again.";
    header("Location: index.php");
}

$stmt->close();
$conn->close();
?>
