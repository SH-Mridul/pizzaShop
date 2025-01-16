let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
}

let account = document.querySelector('.user-account');

document.querySelector('#user-btn').onclick = () => {
   account.classList.add('active');
}

document.querySelector('#close-account').onclick = () => {
   account.classList.remove('active');
}

let myOrders = document.querySelector('.my-orders');

document.querySelector('#order-btn').onclick = () => {
   myOrders.classList.add('active');
}

document.querySelector('#close-orders').onclick = () => {
   myOrders.classList.remove('active');
}

let cart = document.querySelector('.shopping-cart');

document.querySelector('#cart-btn').onclick = () => {
   cart.classList.add('active');
}

document.querySelector('#close-cart').onclick = () => {
   cart.classList.remove('active');
}

window.onscroll = () => {
   navbar.classList.remove('active');
   myOrders.classList.remove('active');
   cart.classList.remove('active');
};

let slides = document.querySelectorAll('.home-bg .home .slide-container .slide');
let index = 0;

function next() {
   slides[index].classList.remove('active');
   index = (index + 1) % slides.length;
   slides[index].classList.add('active');
}

function prev() {
   slides[index].classList.remove('active');
   index = (index - 1 + slides.length) % slides.length;
   slides[index].classList.add('active');
}

let accordion = document.querySelectorAll('.questions .accordion-container .accordion');

accordion.forEach(acco => {
   acco.onclick = () => {
      accordion.forEach(remove => remove.classList.remove('active'));
      acco.classList.add('active');
   }
});

var swiper = new Swiper(".review-slider", {
   loop: true,
   spaceBetween: 20,
   autoplay: {
      delay: 7500,
      disableOnInteraction: false,
   },

   breakpoints: {
      0: {
         slidesPerView: 1,

      },
      768: {
         slidesPerView: 2,

      },
      1024: {
         slidesPerView: 3,

      },

   },
});

let cartItems = [];

function addToCart(itemName, itemPrice, itemQty, itemId) {
   const existingItem = cartItems.find(item => item.name === itemName);

   if (existingItem) {
      existingItem.qty += itemQty;
   } else {
      cartItems.push({ name: itemName, price: itemPrice, qty: itemQty, product_id: itemId });
   }
   displayCartItems();
   updateTotalOrderCount();
}

function displayCartItems() {
   const cartContainer = document.getElementById("cart-items");
   cartContainer.innerHTML = "";

   cartItems.forEach((item, index) => {
      const cartBox = document.createElement("div");
      cartBox.classList.add("box");
      cartBox.innerHTML = `
         <a href="#" class="fas fa-times" onclick="removeItemFromCart(${index})"></a>
         <img src="images/pizza-1.jpg" alt="">
         <div class="content">
            <p>${item.name} <span> ${item.price}/- </span></p>
            <form action="" method="post">
               <input type="number" class="qty" name="qty" min="1" value="${item.qty}" max="100">
               <button type="button" class="fas fa-edit" onclick="updateItemQty('${item.name}', this.previousElementSibling.value)"></button>
            </form>
         </div>
      `;
      cartContainer.appendChild(cartBox);
   });
}
function updateTotalOrderCount() {
   const totalOrderCount = cartItems.reduce((total, item) => total + item.qty, 0);
   document.getElementById("total-order-count").textContent = `(${totalOrderCount})`;
}

function removeItemFromCart(index) {
   cartItems.splice(index, 1);
   displayCartItems();
   updateTotalOrderCount();
}
function updateItemQty(itemName, newQty) {
   const item = cartItems.find(item => item.name === itemName);
   if (item) {
      item.qty = parseInt(newQty);
      displayCartItems();
      updateTotalOrderCount();
   }
}
document.querySelectorAll("#menu .box .btn").forEach(btn => {
   btn.addEventListener("click", event => {
      event.preventDefault();
      const box = btn.closest(".box");
      const itemName = box.querySelector(".name").textContent;
      const itemPrice = parseInt(box.querySelector(".price span").textContent);
      const itemQty = parseInt(box.querySelector(".qty").value);
      const itemId = parseInt(box.querySelector(".id").value);
      addToCart(itemName, itemPrice, itemQty, itemId);
   });
});

document.addEventListener('DOMContentLoaded', () => {
   const registerForm = document.querySelector('form[name="register"]');
   const loginForm = document.querySelector('form[name="login"]');
   const logoutButton = document.getElementById('logout');

   registerForm.onsubmit = handleRegister;
   loginForm.onsubmit = handleLogin;
   logoutButton.onclick = logoutUser;

   // Check if a user is already logged in
   const currentUser = JSON.parse(localStorage.getItem('currentUser'));
   if (currentUser) {
      document.querySelector('.user-account .user p').textContent = `Welcome, ${currentUser.name}!`;
      logoutButton.style.display = 'block'; // Show logout button
   }
});

// Function to handle user registration
function handleRegister(event) {
   event.preventDefault();

   const name = document.querySelector('form[name="register"] input[name="name"]').value;
   const email = document.querySelector('form[name="register"] input[name="email"]').value;
   const password = document.querySelector('form[name="register"] input[name="pass"]').value;
   const confirmPassword = document.querySelector('form[name="register"] input[name="cpass"]').value;

   if (password !== confirmPassword) {
      alert("Passwords do not match!");
      return;
   }

   const users = JSON.parse(localStorage.getItem('users')) || [];

   if (users.some(user => user.email === email)) {
      alert("Email is already registered. Please use another.");
      return;
   }

   users.push({ name, email, password });
   localStorage.setItem('users', JSON.stringify(users));

   alert("Registered successfully!");
}

// Function to handle user login
function handleLogin(event) {
   event.preventDefault();

   const email = document.querySelector('form[name="login"] input[name="email"]').value;
   const password = document.querySelector('form[name="login"] input[name="pass"]').value;

   const users = JSON.parse(localStorage.getItem('users')) || [];

   const user = users.find(user => user.email === email && user.password === password);

   if (user) {
      localStorage.setItem('currentUser', JSON.stringify(user));
      alert("Login successful!");
      document.querySelector('.user-account .user p').textContent = `Welcome, ${user.name}!`;
      document.getElementById('logout').style.display = 'block'; // Show logout button
   } else {
      alert("Invalid email or password.");
   }
}

// Function to handle user logout
function logoutUser() {
   localStorage.removeItem('currentUser');
   alert("Logged out successfully!");
   document.querySelector('.user-account .user p').textContent = "You are not logged in now!";
   document.getElementById('logout').style.display = 'none'; // Hide logout button
}
