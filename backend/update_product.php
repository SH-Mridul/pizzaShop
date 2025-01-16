<?php
require 'login_check.php';
require 'database_connection.php';

// Check if 'id' is passed in the GET request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = 'Invalid product ID.';
    header('Location: products_list.php'); // Redirect to the products list page
    exit();
}

$id = intval($_GET['id']);

// Fetch product details from the database
$query = "SELECT name, price, image_path FROM products WHERE id = ?";
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
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Product</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
  <?php require 'menu.php'; ?>

  <!-- Dashboard Content -->
  <div class="container my-4 mb-4">
    <h5 class="mb-4 text-center">Update Product</h5> 
    <div class="row mt-4">
      <div class="col-md-3 mt-4"></div>
      
      <div class="col-md-6 mt-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <form method="post" action="update_product_action.php" enctype="multipart/form-data">
                <div class="mx-auto text-center">
                    <span class="text-danger">
                        <?php 
                            if (isset($_SESSION['error'])) { 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']); 
                            }
                        ?>
                    </span>
                    <span class="text-success">
                        <?php 
                            if (isset($_SESSION['success'])) { 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']); 
                            }
                        ?>
                    </span>
                </div>

                <!-- Hidden Field for Product ID -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <!-- Image Preview -->
                <div class="mb-3 text-center">
                    <label for="current-image" class="form-label">Current Image</label>
                    <div>
                        <img src="<?php echo "products/" . htmlspecialchars($product['image_path']); ?>" 
                             alt="Current Image" 
                             class="img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </div>

                <!-- Image Field -->
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control form-control-sm" id="image" name="image" accept="image/*">
                    <div class="mt-2 text-center">
                        <img id="image-preview" class="img-thumbnail d-none" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </div>
                
                <!-- Price Field -->
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control form-control-sm" id="price" name="price" placeholder="Enter price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-sm w-100">Update Product</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-3 mt-4"></div>
    </div>
  </div>

  <?php require 'footer.php'; ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JavaScript for Image Preview -->
  <script>
    document.getElementById('image').addEventListener('change', function(event) {
      const file = event.target.files[0];
      const preview = document.getElementById('image-preview');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      } else {
        preview.src = '';
        preview.classList.add('d-none');
      }
    });
  </script>
</body>
</html>
