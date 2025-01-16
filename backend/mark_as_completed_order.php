<?php
require 'login_check.php'; // Ensure user is logged in
require 'database_connection.php'; // Database connection

// Catch the `order_id` from the GET request and sanitize it
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id > 0) {
    // Prepare the SQL query to update `order_status` to 2
    $query = "UPDATE orders SET order_status = 3 WHERE id = ?";
    
    // Prepare and execute the statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $order_id); // Bind the `order_id` parameter as an integer
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Success: Order status updated
            $_SESSION['success'] = "Order status has been marked as completed successfully.";
        } else {
            // Failure: No rows updated
            $_SESSION['error'] = "Failed to update order status. Please try again.";
        }
        
        $stmt->close(); // Close the prepared statement
    } else {
        // SQL error
        $_SESSION['error'] = "Error in SQL query. Please contact the administrator.";
    }
} else {
    // Invalid `order_id`
    $_SESSION['error'] = "Invalid Order ID.";
}

// Redirect back to `placed_order_list.php`
header("Location: pending_order_list.php");
exit;
?>
