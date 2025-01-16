<?php
require 'login_check.php';
require 'database_connection.php';

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: products_list.php');
    exit();
}

// Retrieve product ID from the form
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['error'] = 'Invalid product ID.';
    header('Location: products_list.php');
    exit();
}

$id = intval($_POST['id']);

// Retrieve form data
$name = trim($_POST['name']);
$price = floatval($_POST['price']);
$image = $_FILES['image'];

// Validate name: Ensure the name is unique, excluding the current product
$query = "SELECT id FROM products WHERE name = ? AND id != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $name, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = 'Product name already exists. Please use a different name.';
    header("Location: update_product.php?id=$id");
    exit();
}
$stmt->close();

// Fetch current product details
$query = "SELECT image_path FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'Product not found.';
    header('Location: products_list.php');
    exit();
}

$product = $result->fetch_assoc();
$currentImage = $product['image_path'];
$stmt->close();

// Handle image upload
$newImagePath = $currentImage; // Default to the current image if no new image is uploaded

if (isset($image) && $image['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($image['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['error'] = 'Invalid image type. Only JPG, PNG, and GIF are allowed.';
        header("Location: update_product.php?id=$id");
        exit();
    }

    // Generate a new unique name for the uploaded image
    $newImageName = uniqid() . '-' . basename($image['name']);
    $newImagePath = $newImageName;

    // Delete the current image if it exists
    if (!empty($currentImage) && file_exists('products/' . $currentImage)) {
        unlink('products/' . $currentImage);
    }

    // Move the new uploaded image to the products directory
    if (!move_uploaded_file($image['tmp_name'], 'products/'.$newImagePath)) {
        $_SESSION['error'] = 'Failed to upload the image.';
        header("Location: update_product.php?id=$id");
        exit();
    }
}

// Update the product details in the database
$query = "UPDATE products SET name = ?, price = ?, image_path = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('sdsi', $name, $price, $newImagePath, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Product updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update the product. Please try again.';
}

// Redirect back to the update form or products list
header("Location: update_product.php?id=$id");
exit();
