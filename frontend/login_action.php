<?php
session_start();
include("database_connection.php"); // Include your database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['notification'] = "Email and Password cannot be empty!";
        header("Location: index.php"); // Redirect back to login page
        exit();
    }

    // Check if user exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if the password is correct (using md5 hash)
        if (md5($password) === $user['password']) {
            // Password matches, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['notification'] = "Hello,".$user['name']."!";
            // Redirect to a protected page (dashboard, profile, etc.)
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['notification'] = "Incorrect password!";
        }
    } else {
        $_SESSION['notification'] = "Email not registered!";
    }

    // If there's an error, redirect back to the login page
    header("Location: index.php");
    exit();
}
?>
