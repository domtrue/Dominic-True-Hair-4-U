<?php
session_start();

include 'setup.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if payment status is set and is successful
if (!isset($_SESSION['payment_status']) || $_SESSION['payment_status'] !== 'Success') {
    die("Required session variables are missing or payment was not successful.");
}

// Check if user details and cart session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_total']) || !isset($_SESSION['cart'])) {
    die("Missing required session data.");
}

// Initialize session variables
$user_id = $_SESSION['user_id'];
$grandTotal = $_SESSION['grand_total'];
$cart = $_SESSION['cart'];
$cardName = $_SESSION['card_name'];
$country_region = $_SESSION['country'];
$zip_code = $_SESSION['zip'];
$gst = $_SESSION['gst'];
$shippingCost = $_SESSION['shipping_cost'];


// Function to process the order
function processOrder($userId, $cart, $grandTotal, $gst, $shippingCost, $cardName, $countryRegion, $zipCode) {
    global $db;

    $orderId = 'ORDER-' . uniqid(); // Example order ID generation

    // Prepare SQL to insert order details into database
    $stmt = $db->prepare("INSERT INTO orders (user_id, grand_total, status, created_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param('id', $userId, $orderTotal);

    if ($stmt->execute()) {
        $orderId = $stmt->insert_id; // Get the last inserted order ID

        // Insert order items
        foreach ($cart as $item) {
            $stmtItem = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmtItem->bind_param('iiidd', $orderId, $item['id'], $item['quantity'], $item['price'], $item['price'] * $item['quantity']);
            $stmtItem->execute();
        }

        // Insert payment details
        $stmtPayment = $db->prepare("INSERT INTO payment_details (order_id, card_name, zip_code, country, payment_method_id, status, created_at) VALUES (?, ?, ?, ?, ?, 'Success', NOW())");
        $stmtPayment->bind_param('isssss', $orderId, $cardName, $zipCode, $countryRegion, $_SESSION['payment_method_id']);
        $stmtPayment->execute();

        $stmt->close();
        $stmtItem->close();
        $stmtPayment->close();

        return $orderId;
    } else {
        return false;
    }
}

// Process the order
$orderId = processOrder($userId, $cart, $orderTotal, $gst, 9.00, $cardName, $countryRegion, $zipCode, $grandTotal);

// If order processing is successful, retrieve detailed information about the order
if ($orderId) {
    // Prepare the SELECT query to fetch order details along with items and payment information
    $stmtOrderDetails = $db->prepare("SELECT orders.*, order_items.*, payment_details.*
                                      FROM orders
                                      JOIN order_items ON orders.order_id = order_items.order_id
                                      JOIN payment_details ON orders.order_id = payment_details.order_id
                                      WHERE orders.order_id = ?");
    $stmtOrderDetails->bind_param('i', $orderId);
    
    if ($stmtOrderDetails->execute()) {
        $result = $stmtOrderDetails->get_result();
        $orderDetails = $result->fetch_assoc();

        // Store order details in session to be displayed later
        $_SESSION['order_details'] = $orderDetails;

        header('Location: order_success.php');
        exit();
    } else {
        die("Failed to fetch order details. Please try again.");
    }

    $stmtOrderDetails->close();
} else {
    die("Order processing failed. Please try again.");
}

// Clean up session data after processing the order
session_unset(); // Clear session variables
session_destroy(); // Destroy session
?>
