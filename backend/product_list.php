<?php
require 'login_check.php';
require 'database_connection.php';  // Make sure this file is included
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
  <?php require 'menu.php'; ?>

  <!-- Dashboard Content -->
  <div class="container mt-5">
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

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <?php
      // Fetch products where status = 1
      $query = "SELECT id, name, price, image_path FROM products WHERE status = 1";
      $result = $conn->query($query);  // Use $conn here

      if ($result && $result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
          $id = $product['id'];
          $name = htmlspecialchars($product['name']);
          $price = htmlspecialchars($product['price']);
          $imagePath = htmlspecialchars($product['image_path']);
          ?>
          <div class="col">
            <div class="card text-center shadow-sm">
              <img src="<?php echo "products/".$imagePath; ?>" 
                   class="card-img-top mx-auto" 
                   alt="<?php echo $name; ?>" 
                   style="width: 150px; height: 150px; object-fit: cover;">
              <div class="card-body">
                <h5 class="card-title fw-bold"><?php echo $name; ?></h5>
                <p class="card-text">Price: <?php echo $price; ?></p>
                <div class="d-flex justify-content-center gap-2">
                  <a href="delete_product.php?id=<?php echo $id; ?>" 
                     class="btn btn-sm btn-outline-danger delete-btn" 
                     data-name="<?php echo $name; ?>">Delete</a>
                  <a href="update_product.php?id=<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">Update</a>
                </div>
              </div>
            </div>
          </div>
          <?php
        }
      } else {
        echo '<p class="text-center">No products found.</p>';
      }
      ?>
    </div>
  </div>

  <?php require 'footer.php'; ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JavaScript for Confirmation -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Get all delete buttons
      const deleteButtons = document.querySelectorAll('.delete-btn');

      // Add click event listener to each button
      deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
          event.preventDefault(); // Prevent default anchor action
          
          const productName = button.getAttribute('data-name');
          const confirmDelete = confirm(`Are you sure you want to delete the product "${productName}"?`);
          
          if (confirmDelete) {
            // If confirmed, proceed to the link
            window.location.href = button.href;
          }
        });
      });
    });
  </script>
</body>
</html>
