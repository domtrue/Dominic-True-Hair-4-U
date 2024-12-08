<?php
session_start(); // Start the session

// Ensure cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Database connection details
include 'setup.php'; // Ensure this file contains your database credentials

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add item to cart
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1; // Default to 1 if quantity is not set

    // Fetch product details from the database
    $sql = "SELECT id, name, description, price, image FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $productData = $result->fetch_assoc();

        // Prepare product data
        $product = [
            'id' => $productData['id'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'price' => $productData['price'],
            'quantity' => $quantity,
            'image' => 'img/' . $productData['image'] // Adjust path as necessary
        ];

        // Add or update product in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $product;
        }
    } else {
        echo "Product not found.";
    }

    $stmt->close();
}

// Close connection
$conn->close();

// Count total items in the cart
$total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));

// Check if the request is an AJAX request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Return JSON response with total cart items
    header('Content-Type: application/json');
    echo json_encode(['totalItems' => $total_items]);
} else {
    // Redirect back to shop or cart page for non-AJAX requests
    header('Location: shop.php');
}

exit();
?>
