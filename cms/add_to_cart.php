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

if (isset($_GET['id']) && isset($_GET['type'])) {
    $productId = intval($_GET['id']);
    $type = $_GET['type'];
    $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

    // Determine the table based on the type
    $table = ($type === 'voucher') ? 'gift_vouchers' : 'products';

    // Fetch item details from the corresponding table
    $sql = "SELECT id, name, description, price, image FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $productData = $result->fetch_assoc();

        $product = [
            'id' => $productData['id'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'price' => $productData['price'],
            'quantity' => $quantity,
            'image' => 'img/' . $productData['image']
        ];

        // Add to cart logic
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
        echo "Item not found.";
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
