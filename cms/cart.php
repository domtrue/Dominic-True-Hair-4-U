<?php
session_start(); // Start the session

// Ensure there is a cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<p>Your cart is empty. <a href="shop.php">Continue Shopping</a></p>';
    exit();
}
//print_r ($_SESSION);
// Process form submissions for updating quantity and removing items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $key => $quantity) {
            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] = max(1, intval($quantity)); // Ensure quantity is at least 1
            }
        }
        // Recalculate order total
        $_SESSION['order_total'] = array_reduce($_SESSION['cart'], function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
    
    if (isset($_POST['remove_item'])) {
        $itemKey = intval($_POST['remove_item']);
        if (isset($_SESSION['cart'][$itemKey])) {
            unset($_SESSION['cart'][$itemKey]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the array
        }
        // Recalculate order total
        $_SESSION['order_total'] = array_reduce($_SESSION['cart'], function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}

// Retrieve cart items and calculate total
$cartItems = $_SESSION['cart'];
$cartTotal = isset($_SESSION['order_total']) ? $_SESSION['order_total'] : 0; // Ensure cartTotal is defined
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap');

body {
    background: #f4f4f4;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 800px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 2rem;
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
    z-index: 1; /* Ensure container is above other elements */
}

.container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('path-to-your-pattern-image.png') repeat;
    opacity: 0.1;
    z-index: 0; /* Ensure background pattern is behind content */
}

.logo {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1; /* Ensure logo is above background pattern */
}

.logo img {
    max-width: 150px;
}

h1 {
    text-align: center;
    color: #000;
    font-family: 'Merriweather', serif;
    margin: 0;
    position: relative;
    z-index: 1; /* Ensure heading is above background pattern */
}

.cart-items {
    margin-bottom: 2rem;
}

.cart-item {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 1rem 0;
    position: relative; /* Ensure cart items are positioned correctly */
    z-index: 2; /* Ensure items are above background pattern */
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item img {
    max-width: 100px;
    margin-right: 1rem;
}

.cart-item .item-details {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-item select {
    padding: 0.5rem;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 3; /* Ensure dropdown is clickable */
}

.cart-item select:focus {
    border-color: #4a148c;
    outline: none;
}

.cart-item button {
    background-color: #e57373;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: background-color 0.3s;
    position: relative;
    z-index: 3; /* Ensure button is clickable */
}

.cart-item button:hover {
    background-color: #d32f2f;
}

.cart-summary {
    margin-top: 1rem;
    border-top: 1px solid #ddd;
    padding-top: 1rem;
}

.cart-summary .summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.cart-summary .summary-item span {
    font-weight: bold;
}

.auth-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
}

.auth-buttons button {
    background-color: #4a148c; /* Primary color for the buttons */
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s, transform 0.2s;
    position: relative;
    z-index: 3; /* Ensure buttons are clickable */
}

.auth-buttons button:hover {
    background-color: #6a1b9a; /* Darker shade for hover effect */
}

.auth-buttons button:active {
    transform: scale(0.98); /* Slight scale effect for button press */
}

/* Optional: Focus state for accessibility */
.auth-buttons button:focus {
    outline: 2px solid #4a148c;
    outline-offset: 2px;
}


    </style>
</head>
<body>
    <div class="container">
        <h1>Your Shopping Cart</h1>

        <!-- Cart Update Form -->
        <form id="cart-form" method="POST">
    <div class="cart-items">
        <?php
        if (!empty($cartItems) && is_array($cartItems)) {
            foreach ($cartItems as $key => $item) {
                if (is_array($item) && isset($item['name'], $item['price'], $item['quantity'], $item['image']) && is_numeric($item['price']) && is_numeric($item['quantity'])) {
                    $itemTotal = $item['price'] * $item['quantity'];
                    echo '<div class="cart-item">';
                    echo '<img src="' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['name']) . '">';
                    echo '<div class="item-details">';
                    echo '<span>' . htmlspecialchars($item['name']) . '</span>';
                    echo '<span>$' . number_format($item['price'], 2) . '</span>'; // Display price as stored
                    echo '<span>';
                    echo '<select name="quantity[' . $key . ']" onchange="updateQuantity(this)">';
                    for ($i = 1; $i <= 10; $i++) {
                        $selected = $i == $item['quantity'] ? 'selected' : '';
                        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                    }
                    echo '</select>';
                    echo '</span>';
                    echo '<span>';
                    echo '<button type="submit" name="remove_item" value="' . $key . '">Remove</button>';
                    echo '</span>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<p>Cart item structure is incorrect.</p>';
                    echo '<pre>';
                    print_r($item); // Inspect the incorrect item structure
                    echo '</pre>';
                }
            }
        } else {
            echo '<p>Your cart is empty.</p>';
        }
        ?>
    </div>
    <!-- Hidden input to trigger AJAX -->
    <input type="hidden" name="update_quantity" value="1">
</form>


        <!-- Cart Summary -->
        <div class="cart-summary">
            <div class="summary-item">
                <span>Cart Total:</span>
                <span id="cart-total">$<?php echo number_format($cartTotal, 2); ?></span>
            </div>
        </div>

        <!-- Authentication Buttons -->
        <div class="auth-buttons">
            <button type="button" onclick="window.location.href='checkout.php'">Checkout</button>
            <button type="button" id="checkout-button" onclick="window.location.href='checkout.php'">Checkout as Guest</button>
        </div>
    </div>

    <script>
        // Handle AJAX form submission
        document.getElementById('cart-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission
    var formData = new FormData(document.getElementById('cart-form'));
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_cart.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById('cart-total').innerText = '$' + response.newTotal.toFixed(2);
                } else {
                    console.error('Failed to update cart:', response.message);
                }
            } catch (e) {
                console.error('Error parsing response:', e);
            }
        } else {
            console.error('Failed to update cart. Status:', xhr.status);
        }
    };
    xhr.onerror = function () {
        console.error('Request failed');
    };
    xhr.send(formData);
});

// Update cart total on quantity change
function updateQuantity(selectElement) {
    var form = document.getElementById('cart-form');
    form.submit(); // Submit the form to update the quantity
}


    </script>
</body>
</html>
