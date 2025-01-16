<?php
session_start();
include 'database_connection.php'; // Replace with your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['notification'] = 'You must log in to place an order.';
    header('Location: index.php');
    exit();
}

try {
    // Retrieve user input
    $user_id = $_SESSION['user_id'];
    $customer_name = htmlspecialchars($_POST['name']);
    $contact_number = htmlspecialchars($_POST['number']);
    $building_name = htmlspecialchars($_POST['building_name']);
    $street_name = htmlspecialchars($_POST['street_name']);
    $cartData = json_decode($_POST['cart_data'], true);

    // Validate cart data
    if (empty($cartData) || !is_array($cartData)) {
        $_SESSION['notification'] = 'Cart is empty. Unable to place the order.';
        header('Location: index.php');
        exit();
    }

    // Start transaction
    $conn->autocommit(false);

    // Insert order into the `orders` table
    $insertOrderQuery = "INSERT INTO orders (user_id, customer_name, contact_number, building_name, street_name) 
                         VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bind_param("issss", $user_id, $customer_name, $contact_number, $building_name, $street_name);
    $stmt->execute();

    // Get the last inserted order ID
    $order_id = $conn->insert_id;

    // Insert cart items into the `order_details` table
    $insertOrderDetailsQuery = "INSERT INTO order_details (order_id, product_id, quantity) 
                                VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertOrderDetailsQuery);

    foreach ($cartData as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['qty'];
        $stmt->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    // Set success notification
    $_SESSION['notification'] = 'Order placed successfully!';
    header('Location: index.php');
    exit();
} catch (Exception $e) {
    // Rollback transaction in case of an error
    $conn->rollback();

    // Log the error and set a failure notification
    error_log("Order placement error: " . $e->getMessage());
    $_SESSION['notification'] = 'An error occurred while placing the order. Please try again.';
    header('Location: index.php');
    exit();
} finally {
    // Close the statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->autocommit(true); // Restore autocommit mode
    $conn->close();
}
?>
