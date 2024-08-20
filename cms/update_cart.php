<?php
session_start(); // Start the session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['product_id']); // Get the product ID
    $quantity = intval($_POST['quantity']); // Get the new quantity

    // Ensure the quantity is valid
    if ($quantity > 0) {
        // Update the quantity in the cart
        $_SESSION['cart'][$productId] = $quantity;
    } else {
        // If quantity is zero or invalid, remove the item from the cart
        unset($_SESSION['cart'][$productId]);
    }

    // Optionally, you can check if the cart is empty and unset the cart if needed
    if (empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
}

// Redirect back to the cart page
header("Location: cart.php");
exit();
?>
