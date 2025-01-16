<?php
    session_start();
    require 'database_connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Complete Responsive Pizza Shop Website Design</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->

<header class="header">
   <a href="#" class="logo"><i class="fa fa-pie-chart" aria-hidden="true"></i> Pizza </a>

   <section class="flex">

      <a href="#home" class="logo"></a>

      <nav class="navbar">
         <a href="#home">Home</a>
         <a href="#about">About</a>
         <a href="#menu">Menu</a>
         <a href="#order">Order</a>
         <a href="#questions">Questions</a>
         <a href="#review">Review</a>
      </nav>

      <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="order-btn" class="fas fa-box" <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['name']) && !isset($_SESSION['email'])){echo "style='display:none'"; } ?>></div>
            <div id="cart-btn" class="fas fa-shopping-cart"></div>
            <div class="fas fa-sign-out-alt" onclick="window.location.href='logout.php';" <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['name']) && !isset($_SESSION['email'])){echo "style='display:none'"; } ?>></div> <!-- Added cursor style -->
            <div id="user-btn" class="fas fa-user" <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && isset($_SESSION['email'])){echo "style='display:none;cursor:pointer'"; } ?>></div>
      </div>


   </section>

</header>

<!-- header section ends -->

<div class="user-account">
   <section>
      <div id="close-account"><span>close</span></div>
      
      <!-- Welcome message -->
      <div class="user">
         <p><span></span></p>
         <button id="logout" class="btn" style="display: none;">Logout</button>
      </div>
      
      <div class="flex">
         <!-- Login Form -->
         <form action="login_action.php" method="post">  <!-- Remove action and method -->
            <h3>Login Now</h3>
            <input type="email" name="email" required class="box" placeholder="Enter your email" maxlength="50">
            <input type="password" name="pass" required class="box" placeholder="Enter your password" maxlength="20">
            <button type="submit" class="btn">Login Now</button>  <!-- Use button instead of input for styling -->
         </form>
         
         <!-- Register Form -->
         <form action="registration_action.php" method="post">  <!-- Remove action and method -->
            <h3>Register Now</h3>
            <input type="text" name="name" required class="box" placeholder="Enter your name" maxlength="20">
            <input type="email" name="email" required class="box" placeholder="Enter your email" maxlength="50">
            <input type="password" name="pass" required class="box" placeholder="Enter your password" maxlength="20">
            <input type="password" name="cpass" required class="box" placeholder="Confirm your password" maxlength="20">
            <button type="submit" class="btn">Register Now</button>  <!-- Use button instead of input for styling -->
         </form>
      </div>
   </section>
</div>




   <div class="my-orders">

      <section>

         <div id="close-orders"><span>close</span></div>

         <h3 class="title"> my orders </h3>
         <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && isset($_SESSION['email'])){?>

            <?php
               $user_id = $_SESSION['user_id'];
               $query = "
                  SELECT 
                     o.id AS order_id,
                     o.customer_name,
                     o.contact_number,
                     o.order_status,
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
                     o.user_id = $user_id AND od.status = 1
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
                           'total_price' => 0,
                           'order_status' => $row['order_status']
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

               $counter = 0;
            ?>


            <?php foreach ($orders as $order): ?>
            <div style="border: 1px solid #ccc; border-radius: 8px; padding: 16px; margin: 16px 0; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 600px;">
               <h5 style="margin: 0; font-size: 18px; font-weight: bold;">Customer: <?= htmlspecialchars($order['customer_name']) ?></h5>
               <p style="margin: 8px 0; font-size: 16px;">Total Price: <?= htmlspecialchars($order['total_price']) ?></p>
               <p style="margin: 8px 0; font-size: 16px;">Location: <?= htmlspecialchars($order['location']) ?></p>

               <div>
                  <div style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; margin-top: 16px; font-weight: bold;" onclick="toggleUniqueOrderList(<?php echo $order['id']; ?>)">
                     <span>Order List</span>
                     <span id="unique-arrow" style="font-size: 16px;">&#9662;</span>
                  </div>

                  <ul id="uniqueOrderItems<?php echo $order['id']; ?>" style="list-style: none; padding: 0; margin: 16px 0 0; display: none;">
                  <?php foreach ($order['products'] as $product): ?>   
                  <li style="display: flex; justify-content: space-between; padding: 8px; border-bottom: 1px solid #ddd;">
                        <span>Item Name: <?= htmlspecialchars($product['product_name']) ?></span>
                        <span>Quantity: <?= htmlspecialchars($product['quantity']) ?></span>
                        <span>Price:  <?= htmlspecialchars($product['price']) ?></span>
                        <span>Total Price: <?= htmlspecialchars($product['total_price']) ?></span>
                     </li>
                     <?php endforeach; ?>
                  </ul>
               </div>

               <div style="text-align: center; margin-top: 16px;">
                  <span style="display: inline-block; padding: 4px 8px; font-size: 12px; font-weight: bold; color: white; background-color: <?php if($order['order_status'] == 1){ echo "#FFC107"; }else if($order['order_status'] == 2){ echo "#17A2B8"; }else{ echo "#28A745"; } ?>; border-radius: 12px; text-align: center;">
                    <?php
                     if($order['order_status'] == 1){
                        echo "Awaiting";
                     }else if($order['order_status'] == 2){
                        echo "Preparing";
                     }else{
                         echo "Completed";
                     }
                    ?>
                  </span>
               </div>
            </div>
         <?php endforeach; ?>

       <?php } ?>
      </section>

   </div>


<div class="cart-icon">
   <span>Cart</span> <span id="total-order-count">(0)</span>
</div>

<!-- Shopping Cart Section -->
<div class="shopping-cart">
   <section>
      <!-- Close button in the cart -->
      <div id="close-cart">
         <span>close</span>
      </div>

      <!-- Cart items will be dynamically inserted here by JavaScript -->
      <div id="cart-items"></div>

      <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && isset($_SESSION['email'])){?>
            <a href="#order" class="btn">order now</a>
      <?php }?>

   </section>
</div>



      <a href="#order" class="btn">order now</a>

   </section>

</div>

<div class="home-bg">
   <video autoplay muted loop class="bg-video">
      <source src="images/flame.mp4" type="video/mp4">
      
   </video>
   
   <section class="home" id="home">

      <div class="slide-container">

         <div class="slide active">
            <div class="image">
               <img src="images/home-img-1.png" alt="">
            </div>
            <div class="content">
               <h3>homemade  Pizza</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

         <div class="slide">
            <div class="image">
               <img src="images/home-img-2.png" alt="">
            </div>
            <div class="content">
               <h3>Cheesy Pepperoni</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

         <div class="slide">
            <div class="image">
               <img src="images/home-img-3.png" alt="">
            </div>
            <div class="content">
               <h3>Four Cheese</h3>
               <div class="fas fa-angle-left" onclick="prev()"></div>
               <div class="fas fa-angle-right" onclick="next()"></div>
            </div>
         </div>

      </div>

   </section>

</div>

<!-- about section starts  -->

<section class="about" id="about">

   <h1 class="heading">about us</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/about-1.svg" alt="">
         <h3>made with love</h3>
         <p>Every pizza we serve is handicrafted with fresh ingredients and a passion for quality. We make sure every bite brings a smile to your face.</p>
         <a href="#menu" class="btn">our menu</a>
      </div>

      <div class="box">
         <img src="images/about-2.svg" alt="">
         <h3>30 minutes delivery</h3>
         <p>Craving pizza fast? We guarantee hot and fresh pizza delivered to your door in just 30 minutes. Because your time and hunger matter to us.</p>
         <a href="#menu" class="btn">our menu</a>
      </div>

      <div class="box">
         <img src="images/about-3.svg" alt="">
         <h3>share with freinds</h3>
         <p> Pizza is best enjoyed with great company! share your favourite slicesbwith friends and make every gathering a memorable one .</p>
         <a href="#menu" class="btn">our menu</a>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- menu section starts  -->

<section id="menu" class="menu">

   <h1 class="heading">our menu</h1>

   <div class="box-container">

   <?php
      // Fetch products where status = 1
      $query = "SELECT id, name, price, image_path FROM products WHERE status = 1";
      $result = $conn->query($query);  // Use $conn here
        while ($product = $result->fetch_assoc()) {
          $id = $product['id'];
          $name = htmlspecialchars($product['name']);
          $price = htmlspecialchars($product['price']);
          $imagePath = htmlspecialchars($product['image_path']);
          ?>
          
          <div class="box">
            <div class="price"><span><?php echo $price; ?></span>/-</div>
            <img src="<?php echo '../backend/products/'.$imagePath; ?>" alt="">
            <div class="name"><?php echo $name; ?></div>
            <form action="" method="post">
               <input type="hidden"  value="<?php echo $id; ?>" class="id" name="id">
               <input type="number" min="1" max="100" value="1" class="qty" name="qty">

                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && isset($_SESSION['email'])){?>
                  <input type="submit" value="add to cart" name="add_to_cart" class="btn">
               <?php }else{?>
                  <input type="button" value="add to cart" class="btn" onclick=" alert('You have to log in first!');">
               <?php }?>

            </form>
         </div>
      
          <?php } ?>
   </div>

</section>

<!-- menu section ends -->

<!-- order section starts  -->

<section class="order" id="order">

   <h1 class="heading">order now</h1>

   <form action="place_order.php" method="post" onsubmit="submitCartData(event)">

      <div class="display-orders">
         <p>Classic Pizza<span>( 300/- x 2 )</span></p>
         <p>BBQ Magic<span>( 400/- x 1 )</span></p>
         <p>BBQ Beef Delight<span>( 400/- x 4 )</span></p>
        
      </div>

      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" class="box" required placeholder="enter your name" maxlength="20">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="text" name="number" class="box" required placeholder="enter your number" min="0">
         </div>
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="building_name" class="box" required placeholder="building name" maxlength="50">
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street_name" class="box" required placeholder="street name." maxlength="50">
         </div>
      </div>
          <input type="hidden" name="cart_data" id="cart-data">
         <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name']) && isset($_SESSION['email'])){?>
            <input type="submit" value="order now" class="btn">
         <?php }else{?>
            <input type="button" value="order now" class="btn" onclick=" alert('You have to log in first!');">
         <?php }?>

   </form>

   <script>
      function submitCartData(event) {
         // Prevent default form submission
         event.preventDefault();

         // Get the cartItems array (assumed already defined in your script)
         if (typeof cartItems === 'undefined' || !Array.isArray(cartItems)) {
            alert('Cart is empty!');
            return;
         }

         // Add cartItems to the hidden field as JSON
         const cartField = document.getElementById('cart-data');
         cartField.value = JSON.stringify(cartItems);

         // Submit the form programmatically
         event.target.submit();
      }
</script>

</section>

<!-- order section ends -->

<!-- ques section starts  -->

<section class="questions" id="questions">

   <h1 class="heading">QUESTIONS</h1>

   <div class="accordion-container">

      <div class="accordion active">
         <div class="accordion-heading">
            <span>how does it work?</span>
            <i class="fas fa-angle-down"></i>
         
         </div>
         <p class="accrodion-content">
            You can easily browse our menu and order your favourite pizza with your favourite toppings. Once your order is confirmed ,we freshly pepare your pizza and deliver it straight to your door.
         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>how long does it take for delivery?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         Our average delivery time is between 30-45 minutes, depending on your location and the size of the order. We aim to deliver your pizza hot and fresh.
         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>can I order for huge parties?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
         Absulately!! We cater to large orders for parties,events, and gatherings.Just let us know in advanced, and we will make sure your pizzas are ready when you need them.
         </p>
      </div>

      <div class="accordion">
         <div class="accordion-heading">
            <span>how much protein it contains?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
      The protien content varies depending on the pizza toppings you choose. A typical pizza with cheese and meat toppings contains around 12-15 grams of protien per slice.
         </p>
      </div>


      <div class="accordion">
         <div class="accordion-heading">
            <span>is it cooked with oil?</span>
            <i class="fas fa-angle-down"></i>
         </div>
         <p class="accrodion-content">
      Yes, we use high-quality olive oil to enhance the flavour of our pizza dough and toppings. Olive oil is a healthy fat option that adds a rich taste to our pizzas.
         </p>
      </div>

   </div>

</section>

<!-- ques section ends -->

 <!-- review section starts  -->
<section class="review" id="review">
   <h1 class="heading"> Customer's   <span>Review</span></h1>
   <div class=" swiper review-slider">
      <div class="swiper-wrapper">


         <div class="swiper-slide box">
         <img src="images/pic-male.jpg" height="50px" width="50px">
         <p>Absolutely amazing pizza! The crust was perfectly crispy, and the toppings were so fresh.</p> 
         <h3>Sajjad Sajid</h3>
         <div class="stars">
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star-half"></i>
         </div>
      </div>

      <div class="swiper-slide box">
         <img src="images/pic-female.jpg" height="50px" width="50px">
         <p>Great flavors and generous toppings! I ordered the Pepperoni Feast.</p> 
         <h3>Aklima Akter</h3>
         <div class="stars">
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         </div>
      </div>


      <div class="swiper-slide box">
         <img src="images/pic-male.jpg" height="50px" width="50px">
         <p>Incredible service and amazing pizza! I ordered the BBQ pizza, and it was packed with flavor.</p> 
         <h3>Irfan Muntasir</h3>
         <div class="stars">
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star-half"></i>
         </div>
      </div>


      <div class="swiper-slide box">
         <img src="images/pic-female.jpg" height="50px" width="50px">
         <p>Best cheese pizza I've had! I'm so happy to find a place that offers delicious cheese options.</p> 
         <h3>Shahnaj Jafar</h3>
         <div class="stars">
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star"></i>
         <i class="fa fa-star-half"></i>
         </div>
      </div>


   </div>
   </div>
 




</section>

  <!-- review section ends  -->

<!-- footer section starts  -->

<section class="footer">

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>phone number</h3>
         <p>0186*******</p>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>our address</h3>
         <p>Halishahar, k-Block - Chittagong</p>
      </div>

      <div class="box">
         <i class="fas fa-clock"></i>
         <h3>opening hours</h3>
         <p>00:11am to 00:10pm</p>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>email address</h3>
         <p>tasnemnabila@gmail.com</p>
      </div>

   </div>

  
</section>

<!-- footer section ends -->
<!-- custom js file link  -->
<?php if (isset($_SESSION['notification'])) { ?>
<script type="text/javascript">
        window.onload = function() {
            alert("<?php echo $_SESSION['notification'];?>");
        }
</script>
<?php unset($_SESSION['notification']); } ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>
   function toggleUniqueOrderList(id) {
      const orderItems = document.getElementById('uniqueOrderItems'+id);
      const arrow = document.getElementById('unique-arrow');

      if (orderItems.style.display === 'none' || orderItems.style.display === '') {
         orderItems.style.display = 'block';
         arrow.innerHTML = '&#9652;'; // Up arrow
      } else {
         orderItems.style.display = 'none';
         arrow.innerHTML = '&#9662;'; // Down arrow
      }
   }
</script>

</body>
</html>