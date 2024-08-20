<?php
session_start(); // Start the session

// Check if an ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = intval($_GET['id']); // Get the product ID from the URL

    // Check if the cart exists and if the item is in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$productId]);

        // Optionally, you can check if the cart is empty and unset the cart if needed
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit();
} else {
    // If no ID is provided, redirect back to the cart page with an error message
    header("Location: cart.php?error=invalid");
    exit();
}
?>
