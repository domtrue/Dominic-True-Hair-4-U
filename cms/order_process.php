<?php
session_start();
include 'setup.php'; 

if (!isset($_SESSION['account_id']) || !isset($_SESSION['cart'])) {
    die("Invalid session or cart is empty.");
}

$account_id = $_SESSION['account_id'];
$cart = $_SESSION['cart']; // Assuming $cart is an array of product_id => quantity pairs
$total_price = calculateTotalPrice($cart, $db); // Define this function to compute the total price

try {
    $db->beginTransaction();
    
    // Insert order
    $stmt = $db->prepare("INSERT INTO orders (account_id, order_date, status, total_price) VALUES (?, NOW(), 'Pending', ?)");
    $stmt->execute([$account_id, $total_price]);
    $order_id = $db->lastInsertId();

    // Insert order items
    $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $product_id => $quantity) {
        $price = getProductPrice($product_id, $db); // Fetch price from products table
        $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }

    $db->commit();

    // Clear cart session
    unset($_SESSION['cart']);

    echo "Order placed successfully!";
} catch (Exception $e) {
    $db->rollBack();
    die("Error recording order: " . $e->getMessage());
}
?>
