<?php 
    // Start the session
    session_start();

    // Include the database configuration file
    require 'database_connection.php';

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form inputs and sanitize them
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        
        // Prepare and execute the SQL statement with both email and password
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if a matching user was found
        if ($result->num_rows == 1) {
            // Fetch the user's data
            $user = $result->fetch_assoc();
            
            // Set session variables
            $_SESSION['username'] = $user['username']; // Assuming you have a username field
            $_SESSION['is_login'] = true;
            $_SESSION['user_type'] = "admin";
            unset($_SESSION['error']);
            // Redirect to the dashboard or any other page
            header("Location: index.php");
            exit;
        } else {
            // Invalid credentials
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
        }
        
        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
?>