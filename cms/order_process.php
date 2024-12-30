<?php
session_start(); // Start a new session or resume an existing session.

include 'setup.php'; // Include the database setup or configuration file.

// Set payment status for debugging/testing purposes (can be replaced in production).
$_SESSION['payment_status'] = 'Success'; 

// Check if payment was successful and required session variables are set.
if (!isset($_SESSION['payment_status']) || $_SESSION['payment_status'] !== 'Success') {
    die("Required session variables are missing or payment was not successful."); // Terminate if payment isn't marked as successful.
}

// Check if essential session variables are available for processing the order.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_total']) || !isset($_SESSION['cart'])) {
    die("Missing required session data."); // Terminate if any critical session variable is missing.
}

// Initialize session variables into PHP variables for easier reference.
$userId = $_SESSION['user_id']; // ID of the logged-in user.
$grandTotal = $_SESSION['grand_total']; // Total order amount including taxes and shipping.
$cart = $_SESSION['cart']; // Array of cart items.
$gst = $_SESSION['gst']; // Goods and Services Tax amount.
$shippingCost = $_SESSION['shipping_cost']; // Shipping cost for the order.

// Function to handle the order processing.
function processOrder($userId, $cart, $grandTotal, $gst, $shippingCost) {
    global $conn; // Use the global database connection.

    // Generate a unique order ID (for example purposes; can be replaced with auto-increment IDs).
    $orderId = 'ORDER-' . uniqid();

    // Prepare an SQL statement to insert the order into the orders table.
    $stmt = $conn->prepare("INSERT INTO orders (user_id, grand_total, status, created_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param('id', $userId, $grandTotal); // Bind user ID and grand total to the query.

    if ($stmt->execute()) { // Execute the query.
        $orderId = $stmt->insert_id; // Retrieve the last inserted order ID.

        // Insert each cart item as an individual order item.
        foreach ($cart as $item) {
            $product_id = $item['id']; // Product ID from the cart.
            $quantity = $item['quantity']; // Quantity of the product.
            $price = $item['price']; // Price per unit.
            $subtotal = $item['price'] * $item['quantity']; // Calculate the subtotal for this item.

            // Prepare and execute an SQL statement to insert the order item.
            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmtItem->bind_param('iiidd', $orderId, $product_id, $quantity, $price, $subtotal);
            $stmtItem->execute();
        }

        // Close the main order statement.
        $stmt->close();
        return $orderId; // Return the generated order ID on success.
    } else {
        return false; // Return false if order insertion fails.
    }
}

// Call the order processing function.
$orderId = processOrder($userId, $cart, $grandTotal, $gst, $shippingCost); 

// Check if the order processing was successful.
if ($orderId) {
    // Prepare a query to fetch order details, including items and payment info, using joins.
    $stmtOrderDetails = $conn->prepare("SELECT orders.*, order_items.*, payment_details.*
                                      FROM orders
                                      JOIN order_items ON orders.order_id = order_items.order_id
                                      JOIN payment_details ON orders.order_id = payment_details.order_id
                                      WHERE orders.order_id = ?");
    $stmtOrderDetails->bind_param('i', $orderId); // Bind the order ID to the query.

    if ($stmtOrderDetails->execute()) { // Execute the query.
        $result = $stmtOrderDetails->get_result(); // Get the query result.
        $orderDetails = $result->fetch_assoc(); // Fetch the order details.

        // Store the fetched order details in a session variable for use on the success page.
        $_SESSION['order_details'] = $orderDetails;

        header('Location: order_success.php'); // Redirect to the order success page.
        exit(); // Exit script after redirection.
    } else {
        die("Failed to fetch order details. Please try again."); // Handle any failures in fetching order details.
    }

    $stmtOrderDetails->close(); // Close the order details statement.
} else {
    die("Order processing failed. Please try again."); // Terminate if order processing fails.
}

// Clean up session data after processing is complete.
session_unset(); // Clear all session variables.
session_destroy(); // Destroy the session completely.
?>
