<?php
require 'login_check.php';
require 'database_connection.php';

// Check if 'id' is passed in the GET request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = 'Invalid product ID.';
    header('Location: product_list.php');
    exit();
}

$id = intval($_GET['id']); // Sanitize and validate the product ID

// Update the product status to 0 (soft delete)
$query = "UPDATE products SET status = 0 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['success'] = 'Product deleted successfully.';
    } else {
        $_SESSION['error'] = 'Product not found or already deleted.';
    }
} else {
    $_SESSION['error'] = 'Failed to delete the product. Please try again.';
}

$stmt->close();

// Redirect back to the products list
header('Location: product_list.php');
exit();
