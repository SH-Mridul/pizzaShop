<?php
    require 'login_check.php';
    require 'database_connection.php';

    // Query to count all products where status = 1
    $productQuery = "SELECT COUNT(*) AS total_products FROM products WHERE status = 1";
    $productResult = $conn->query($productQuery);
    $products = $productResult->fetch_assoc()['total_products'];

    // Query to count all orders where order_status = 1 (Placed Orders)
    $placedQuery = "SELECT COUNT(*) AS total_placed FROM orders WHERE order_status = 1";
    $placedResult = $conn->query($placedQuery);
    $placed = $placedResult->fetch_assoc()['total_placed'];

    // Query to count all orders where order_status = 2 (Pending Orders)
    $pendingQuery = "SELECT COUNT(*) AS total_pending FROM orders WHERE order_status = 2";
    $pendingResult = $conn->query($pendingQuery);
    $pending = $pendingResult->fetch_assoc()['total_pending'];

    // Query to count all orders where order_status = 3 (Completed Orders)
    $completedQuery = "SELECT COUNT(*) AS total_completed FROM orders WHERE order_status = 3";
    $completedResult = $conn->query($completedQuery);
    $completed = $completedResult->fetch_assoc()['total_completed'];
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
    <h5 class="mb-4 text-center">Dashboard</h5>
    <div class="row">
      <!-- Total Pendings -->
      <div class="col-md-6 mt-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="card-title"><?php echo $products; ?></h2>
            <p class="card-text">Total Items</p>
            <a href="product_list.php" class="btn btn-light">See List</a>
          </div>
        </div>
      </div>
      <!-- Completed Orders -->
      <div class="col-md-6 mt-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="card-title"><?php echo $completed; ?></h2>
            <p class="card-text">Completed Order List</p>
            <a href="completed_order_list.php" class="btn btn-light">See List</a>
          </div>
        </div>
      </div>

      <!-- new -->
        <div class="col-md-6 mt-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="card-title"><?php echo $pending; ?></h2>
            <p class="card-text">Pending Order List</p>
            <a href="pending_order_list.php" class="btn btn-light">See List</a>
          </div>
        </div>
      </div>

      <!-- Orders Placed -->
      <div class="col-md-6 mt-4">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="card-title"><?php echo $placed; ?></h2>
            <p class="card-text">Placed Order List</p>
            <a href="placed_order_list.php" class="btn btn-light">See List</a>
          </div>
        </div>
      </div>


    </div>
  </div>

   <?php require 'footer.php';?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
