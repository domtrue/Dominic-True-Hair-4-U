<?php
session_start(); // Start the session

// Check if the cart exists
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Database connection details
    include 'setup.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to get product details based on cart items
    $productIds = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT id, name, image, price FROM products WHERE id IN ($productIds)";
    $result = $conn->query($sql);

    // Calculate subtotal for the cart items
    $subtotal = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productId = $row['id'];
            $quantity = $_SESSION['cart'][$productId];
            $subtotal += $row['price'] * $quantity;
        }
    }

    // Store the subtotal in session
    $_SESSION['order_total'] = $subtotal;

    ?>
    <style>
            body {
                background: #ddd;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: sans-serif;
                font-size: 0.8rem;
                font-weight: bold;
                margin: 0;
            }

            .card {
                max-width: 950px;
                width: 90%;
                background: #fff;
                box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                border-radius: 1rem;
                overflow: hidden;
                display: flex;
                flex-direction: row;
                margin: auto;
            }

            .cart {
                width: 65%;
                padding: 2rem;
                border-right: 1px solid #ddd;
                overflow-y: auto;
            }

            .checkout {
                width: 35%;
                padding: 2rem;
            }

            .cart-item {
                display: flex;
                align-items: center;
                padding: 1rem 0;
                border-bottom: 1px solid #ddd;
            }

            .cart-item img {
                width: 3.5rem;
                margin-right: 1rem;
            }

            .cart-item .details {
                flex: 1;
            }

            .cart-item .details strong {
                display: block;
                margin-bottom: 0.5rem;
            }

            .cart-item select {
                padding: 0.5rem;
                border: 1px solid #ddd;
                margin-right: 1rem;
            }

            .cart-item .price {
                margin-right: 1rem;
            }

            .cart-item .remove {
                cursor: pointer;
                color: red;
                font-size: 1rem;
                margin-left: 1rem;
            }

            .summary {
                padding-top: 1rem;
            }

            .summary select, .summary input {
                width: 100%;
                padding: 0.5rem;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 0.5rem;
            }

            .checkout .form-group {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
            }

            .checkout .form-group label {
                margin-right: 1rem;
                flex: 0 0 30%; /* Adjust as needed for spacing */
                font-weight: bold;
            }

            .checkout .form-group input, 
            .checkout .form-group select {
                flex: 1; /* Takes up the remaining space */
                padding: 0.5rem;
                border: 1px solid #ddd;
                border-radius: 0.5rem;
            }

            .summary .total {
                font-weight: bold;
                margin-bottom: 1rem;
            }

            .summary .btn {
                background-color: #000;
                color: #fff;
                padding: 1rem;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                width: 100%;
                text-align: center;
            }

            .back-to-shop {
                display: flex;
                align-items: center;
                font-size: 0.9rem;
                color: black;
                text-decoration: none;
                margin-top: 1rem;
            }

            .back-to-shop img {
                width: 1rem;
                margin-right: 0.5rem;
            }

            .empty-message {
                text-align: center;
                font-size: 1.2rem;
                color: #555;
            }
        </style>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Cart</title>
        <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    </head>
    <body>
        <div class="card">
            <div class="cart">
                <h2>Your Cart</h2>
                <?php
                // Display the cart items
                if ($result->num_rows > 0) {
                    $result->data_seek(0); // Reset result pointer
                    while ($row = $result->fetch_assoc()) {
                        $productId = $row['id'];
                        $quantity = $_SESSION['cart'][$productId];
                        $totalPrice = $row['price'] * $quantity;

                        echo '<form method="post" action="update_cart.php" class="cart-item">';
                        echo '<img src="img/' . $row['image'] . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<div class="details">';
                        echo '<strong>' . htmlspecialchars($row['name']) . '</strong>';
                        echo '<select name="quantity" onchange="this.form.submit()">';
                        for ($i = 1; $i <= 10; $i++) {
                            echo '<option value="' . $i . '"' . ($i == $quantity ? ' selected' : '') . '>' . $i . '</option>';
                        }
                        echo '</select>';
                        echo '<span class="price">Price: $' . number_format($totalPrice, 2) . '</span>';
                        echo '<a href="remove_from_cart.php?id=' . $productId . '" class="remove">x</a>';
                        echo '<input type="hidden" name="product_id" value="' . $productId . '">';
                        echo '</div>';
                        echo '</form>';
                    }
                } else {
                    echo '<p class="empty-message">Your cart is empty.</p>';
                }
                ?>
                <a href="shop.php" class="back-to-shop"><img src="https://img.icons8.com/small/16/000000/long-arrow-left.png" alt="Back Arrow"> Back to Shop</a>
            </div>

            <div class="checkout">
                <h3>Checkout</h3>
                <form action="shipping.php" method="post">

                    <div class="form-group">
                        <label for="promo">Promo Code:</label>
                        <input type="text" id="promo" name="promo" placeholder="Enter promo code">
                    </div>

                    <div class="summary">
                        <div class="total">
                            <span>Total:</span>
                            $<?php echo number_format($subtotal, 2); ?>
                        </div>
                        <button type="submit" class="btn">Proceed to Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    </html>
    <?php
    $conn->close();
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Cart</title>
        <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    </head>
    <body>
        <div class="card">
            <div class="cart">
                <h2>Your Cart</h2>
                <p class="empty-message">Your cart is empty.</p>
                <a href="shop.php" class="back-to-shop"><img src="https://img.icons8.com/small/16/000000/long-arrow-left.png" alt="Back Arrow"> Back to Shop</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
