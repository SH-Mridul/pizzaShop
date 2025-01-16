<?php
    require 'login_check.php';
    require 'database_connection.php';
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
  
  <?php require 'menu.php';?>

  <!-- Dashboard Content -->
  <div class="container my-4">
    <h5 class="mb-4 text-center">Add New Product</h5>
    <div class="row mt-4">
      
       <div class="col-md-3 mt-4"></div>
       
      <div class="col-md-6 mt-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <form method="post" action="add_new_product_action.php" enctype="multipart/form-data">

                <div class="mx-auto text-center">
                    <span class="text-danger text-center">
                        <?php 
                            if(isset($_SESSION['error'])){ 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']); 
                            }
                        ?>
                    </span>

                    <span class="text-success text-center">
                        <?php 
                            if(isset($_SESSION['success'])){ 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']); 
                            }
                        ?>
                    </span>
                </div>

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter name" required>
                </div>
                
                <!-- Image Field -->
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control form-control-sm" id="image" name="image" accept="image/*" required>
                </div>
                
                <!-- Price Field -->
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control form-control-sm" id="price" name="price" placeholder="Enter price" required>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-sm w-100">Submit</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-3 mt-4"></div>
    </div>
  </div>

   <?php require 'footer.php';?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
