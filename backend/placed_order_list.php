<?php
    require 'login_check.php';
    require 'database_connection.php';


$query = "
    SELECT 
        o.id AS order_id,
        o.customer_name,
        o.contact_number,
        CONCAT(o.building_name, ', ', o.street_name) AS location,
        od.product_id,
        od.quantity,
        p.name AS product_name,
        p.price AS product_price
    FROM 
        orders o
    INNER JOIN 
        order_details od ON o.id = od.order_id
    INNER JOIN 
        products p ON od.product_id = p.id
    WHERE 
        o.order_status = 1 AND od.status = 1
";


$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Process the fetched data into a structured array
$orders = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'id' => $order_id,
            'customer_name' => $row['customer_name'],
            'location' => $row['location'],
            'products' => [],
            'total_price' => 0
        ];
    }
    $product_total_price = $row['quantity'] * $row['product_price'];
    $orders[$order_id]['products'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['product_price'],
        'total_price' => $product_total_price
    ];
    $orders[$order_id]['total_price'] += $product_total_price;
}

$stmt->close();
$conn->close();
$counter = 0;
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
    <h5 class="mb-4 text-center">Placed Orders</h5>
    <div class="row">
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
    
        <!-- order -->
        
        <?php foreach ($orders as $order): ?>
          <div class="col-md-12 mt-4">
              <div class="card shadow-sm mb-4">
                  <div class="card-body">
                      <h5 class="card-title fw-bold">Customer: <?= htmlspecialchars($order['customer_name']) ?></h5>
                      <p class="card-text">Total Price: <?= htmlspecialchars($order['total_price']) ?></p>
                      <p class="card-text">Location: <?= htmlspecialchars($order['location']) ?></p>

                      <!-- Order List with Arrow for Collapsible Section -->
                      <div class="d-flex justify-content-between align-items-center mb-3">
                          <span class="fw-bold">Order List</span>
                          <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#productList<?= htmlspecialchars($order['id']) ?>" aria-expanded="false" aria-controls="productList<?= htmlspecialchars($order['id']) ?>">
                              <span>&#9662;</span> <!-- Downward Arrow -->
                          </button>
                      </div>

                      <!-- Collapsible Product List -->
                      <div class="collapse" id="productList<?= htmlspecialchars($order['id']) ?>">
                          <ul class="list-group">
                              <?php foreach ($order['products'] as $product): ?>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                  <span>Item Name: <?= htmlspecialchars($product['product_name']) ?></span>
                                  <span>Quantity: <?= htmlspecialchars($product['quantity']) ?></span>
                                  <span>Price: <?= htmlspecialchars($product['price']) ?></span>
                                  <span>Total Price: <?= htmlspecialchars($product['total_price']) ?></span>
                              </li>
                              <?php endforeach; ?>
                          </ul>
                      </div>

                      <!-- Centered Completed Button -->
                      <div class="text-center mt-3">
                          <?php
                            $order_id_var = $order['id'];
                          ?>
                          <a href="mark_as_pending_order.php?order_id=<?php echo urlencode($order_id_var); ?>" class="btn btn-sm btn-success">Mark as Pending Order</a>
                      </div>
                  </div>
              </div>
          </div>
          <?php endforeach; ?>


        
        <!-- order end -->
    </div>
  </div>

   <?php require 'footer.php';?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
