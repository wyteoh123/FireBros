// Shopping Cart Functionality
const addToCartButtons = document.querySelectorAll('.add-to-cart');
const cartSidebar = document.querySelector('.cart-sidebar');
const closeCartButton = document.querySelector('.close-cart');
const overlay = document.createElement('div');
overlay.className = 'overlay';
document.body.appendChild(overlay);

let cartItems = [];

// Load cart from localStorage
if (localStorage.getItem('cartItems')) {
    cartItems = JSON.parse(localStorage.getItem('cartItems'));
    updateCart();
}

addToCartButtons.forEach(button => {
    button.addEventListener('click', function() {
        const menuItem = this.closest('.menu-item');
        const itemName = menuItem.querySelector('h3').textContent;
        const itemPrice = parseFloat(menuItem.querySelector('.price').textContent.replace('RM', '').trim());
        const itemImage = menuItem.querySelector('.item-image img').src;
        
        // Check if item already exists in cart
        const existingItem = cartItems.find(item => item.name === itemName);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cartItems.push({
                name: itemName,
                price: itemPrice,
                image: itemImage,
                quantity: 1
            });
        }
        
        updateCart();
        saveCartToLocalStorage();
        
        // Show cart sidebar
        cartSidebar.classList.add('active');
        overlay.classList.add('active');
    });
});

closeCartButton.addEventListener('click', function() {
    cartSidebar.classList.remove('active');
    overlay.classList.remove('active');
});

overlay.addEventListener('click', function() {
    cartSidebar.classList.remove('active');
    this.classList.remove('active');
});

function updateCart() {
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartTotalElement = document.querySelector('.cart-total span');
    
    cartItemsContainer.innerHTML = '';
    
    let total = 0;
    
    cartItems.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const cartItemElement = document.createElement('div');
        cartItemElement.className = 'cart-item';
        cartItemElement.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <div class="cart-item-price">RM${itemTotal.toFixed(2)}</div>
            </div>
            <div class="cart-item-actions">
                <button class="decrease-item" data-index="${index}">-</button>
                <span class="cart-item-quantity">${item.quantity}</span>
                <button class="increase-item" data-index="${index}">+</button>
                <button class="remove-item" data-index="${index}"><i class="fas fa-trash"></i></button>
            </div>
        `;
        
        cartItemsContainer.appendChild(cartItemElement);
    });
    
    cartTotalElement.textContent = `RM${total.toFixed(2)}`;
    
    // Add event listeners to new buttons
    document.querySelectorAll('.decrease-item').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            if (cartItems[index].quantity > 1) {
                cartItems[index].quantity -= 1;
            } else {
                cartItems.splice(index, 1);
            }
            updateCart();
            saveCartToLocalStorage();
        });
    });
    
    document.querySelectorAll('.increase-item').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            cartItems[index].quantity += 1;
            updateCart();
            saveCartToLocalStorage();
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            cartItems.splice(index, 1);
            updateCart();
            saveCartToLocalStorage();
        });
    });
}

function saveCartToLocalStorage() {
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
}

// Checkout button
const checkoutButton = document.querySelector('.checkout-btn');
checkoutButton.addEventListener('click', function() {
    if (cartItems.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    alert('Thank you for your order! Total: RM' + document.querySelector('.cart-total span').textContent.replace('RM', ''));
    cartItems = [];
    updateCart();
    saveCartToLocalStorage();
    cartSidebar.classList.remove('active');
    overlay.classList.remove('active');
});
// 平滑滚动到锚点
document.querySelectorAll('.item-card').forEach(card => {
    card.addEventListener('click', function() {
        // 如果有动画需求可以在这里添加
        window.location.href = this.getAttribute('onclick').replace("window.location.href='", "").replace("'", "");
    });
});