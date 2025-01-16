<!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Products
            </a>
            <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                <li><a class="dropdown-item" href="add_new_product.php">Add New Product</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="product_list.php">Product List</a></li>
            </ul>
        </li>



          <li class="nav-item">
            <a class="nav-link" href="pending_order_list.php">Pending Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="completed_order_list.php">Completed Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="placed_order_list.php">Placed Orders</a>
          </li>
        </ul>
        <!-- User Dropdown -->
        <div class="dropdown me-3">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
             <?php if(isset($_SESSION['username'])){ echo $_SESSION['username']; } ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>