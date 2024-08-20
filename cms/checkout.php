<?php
session_start(); // Start the session

// Ensure there is a cart to checkout
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo '<h1>Checkout</h1>';
    echo '<form action="process_order.php" method="post">';
    echo '<label for="name">Name:</label><br>';
    echo '<input type="text" id="name" name="name" required><br><br>';
    echo '<label for="address">Address:</label><br>';
    echo '<input type="text" id="address" name="address" required><br><br>';
    echo '<input type="submit" value="Place Order">';
    echo '</form>';
} else {
    echo '<p>Your cart is empty.</p>';
}
?>
