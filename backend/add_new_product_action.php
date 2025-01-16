<?php
     require 'login_check.php';
    require 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($name) || empty($price) || empty($image['name'])) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: add_new_product.php');
        exit();
    }

    // Check if product exists by name
    $stmt = $conn->prepare("SELECT * FROM products WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Product with this name already exists.";
        header('Location: add_new_product.php');
        exit();
    }

    // Generate unique image name
    $unique_image_name = time() . '_' . rand(1000, 9999) . '_' . basename($image['name']);

    // Check and create 'products' folder if not exists
    $upload_dir = 'products/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create folder with permissions
    }

    // Move uploaded file to the 'products' folder
    $target_file = $upload_dir . $unique_image_name;
    if (!move_uploaded_file($image['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Failed to upload the image.";
        header('Location: add_new_product.php');
        exit();
    }

    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, price, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $price, $unique_image_name);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add the product. Please try again.";
    }

    $stmt->close();
    $conn->close();

    header('Location: add_new_product.php');
    exit();
}
?>
