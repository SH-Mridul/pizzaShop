let products = [
    { name: 'Margherita', price: 400, image: 'image/pizza-4.jpg' },
    { name: 'Pepperoni', price: 350, image: 'image/pizza-7.jpg' },
    { name: 'BBQ Chicken', price: 450, image: 'image/pizza-6.jpg' },
    { name: 'Hawaiian', price: 400, image: 'image/pizza-5.jpg' }
]; // Initial available pizzas

let editingProductIndex = null; // Variable to track the index of the product being edited
let completedOrders = [
    { name: 'BBQ Magic', quantity: 3, price: 900 },
    { name: 'BBQ Chicken', quantity: 1, price: 400 }
]; // Sample completed orders
let placedOrders = []; // Array to store placed orders

function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Check credentials
    if (username === 'admin' && password === 'admin') {
        document.getElementById('loginForm').style.display = 'none'; // Hide login form
        document.querySelector('header').style.display = 'block'; // Show header
        document.querySelector('main').style.display = 'block'; // Show main content
        showView('dashboardView'); // Show the dashboard
    } else {
        document.getElementById('error-message').style.display = 'block'; // Show error message
    }
}

function showView(viewId) {
    const views = document.querySelectorAll('.view');
    views.forEach(view => view.style.display = 'none');
    document.getElementById(viewId).style.display = 'block';
}

function showDashboard() {
    showView('dashboardView');
}

function showProducts() {
    showView('productsView');
    displayProducts(); // Display products when the view is shown
}

function displayProducts() {
    const productsContainer = document.getElementById('productsContainer');
    productsContainer.innerHTML = ''; // Clear previous products

    products.forEach((product, index) => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>Price: ${product.price}</p>
            <button onclick="deletePizza(${index})">Delete</button>
            <button onclick="editPizza(${index})">Update</button>
        `;
        productsContainer.appendChild(productCard);
    });
}

function showPendingOrders() {
    showView('pendingOrdersView');
}

function showCompletedOrders() {
    showView('completedOrdersView');
    displayCompletedOrders(); // Display completed orders when the view is shown
}

function displayCompletedOrders() {
    const ordersContainer = document.getElementById('completedOrdersContainer');
    ordersContainer.innerHTML = ''; // Clear previous orders

    completedOrders.forEach((order, index) => {
        const orderCard = document.createElement('div');
        orderCard.className = 'order-card';
        orderCard.innerHTML = `
            <h3>Pizza: ${order.name}</h3>
            <p>Quantity: ${order.quantity}</p>
            <p>Total Price: ${order.price}</p>
            <button class="delete-btn" onclick="deleteOrder(this)">Delete Order</button>
        `;
        ordersContainer.appendChild(orderCard);
    });
}

function deleteOrder(button) {
    // Find the parent order card element
    const orderCard = button.parentElement; // Get the order card that contains the button
    orderCard.remove(); // Remove the order card from the DOM
}

function showPlacedOrders() {
    showView('placedOrdersView');
    displayPlacedOrders(); // Display placed orders when the view is shown
}

function displayPlacedOrders() {
    const ordersContainer = document.getElementById('placedOrdersContainer');
    ordersContainer.innerHTML = ''; // Clear previous orders

    placedOrders.forEach((order, index) => {
        const orderCard = document.createElement('div');
        orderCard.className = 'order-card';
        orderCard.innerHTML = `
            <h3>${order.name}</h3>
            <p>Price: ${order.price}</p>
            <button onclick="deletePlacedOrder(${index})">Delete</button>
        `;
        ordersContainer.appendChild(orderCard);
    });
}

function deletePlacedOrder(index) {
    placedOrders.splice(index, 1); // Remove placed order from array
    displayPlacedOrders(); // Refresh placed orders list
}

function showAddPizzaForm() {
    document.getElementById('pizzaForm').style.display = 'block';
    document.getElementById('formTitle').innerText = 'Add New Pizza';
    clearForm();
    editingProductIndex = null; // Reset editing index
}

function savePizza() {
    const name = document.getElementById('pizzaName').value;
    const price = document.getElementById('pizzaPrice').value;
    const imageInput = document.getElementById('pizzaImage');

    // Check if an image file is selected
    let image;
    if (imageInput.files.length > 0) {
        image = URL.createObjectURL(imageInput.files[0]);
    } else {
        alert('Please select an image!');
        return;
    }

    if (name && price) {
        const newPizza = { name, price, image };

        if (editingProductIndex !== null) {
            products[editingProductIndex] = newPizza; // Update existing product
        } else {
            products.push(newPizza); // Add new product
        }

        document.getElementById('pizzaForm').style.display = 'none'; // Hide form
        displayProducts(); // Refresh product list
        clearForm(); // Clear form inputs
        editingProductIndex = null; // Reset editing index
    } else {
        alert('Please fill all fields!');
    }
}

function editPizza(index) {
    const product = products[index];
    document.getElementById('pizzaName').value = product.name;
    document.getElementById('pizzaPrice').value = product.price;
    document.getElementById('formTitle').innerText = 'Update Pizza';
    document.getElementById('pizzaForm').style.display = 'block';
    editingProductIndex = index; // Set the product being edited
}

function deletePizza(index) {
    products.splice(index, 1); // Remove product from array
    displayProducts(); // Refresh product list
}

function clearForm() {
    document.getElementById('pizzaName').value = '';
    document.getElementById('pizzaPrice').value = '';
    document.getElementById('pizzaImage').value = '';
}
