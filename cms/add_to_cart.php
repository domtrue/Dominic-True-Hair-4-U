<?php
session_start(); // Start the session

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']); // Get the product ID from the URL

    // Initialize the cart if it doesn't already exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Add or update the product in the cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += 1; // Increment quantity if product is already in cart
    } else {
        $_SESSION['cart'][$productId] = 1; // Add new product to cart with quantity 1
    }

    // Redirect to the shop or cart page
    header("Location: shop.php");
    exit();
} else {
    // Handle case where 'id' is not set
    echo "Product ID is missing!";
}
?>
