<?php
include 'setup.php'; // Ensure this file contains your database connection details
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['account_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Get the logged-in user's account_id from the session
$account_id = $_SESSION['account_id']; 

// Prepare and execute the SQL query
$sql = "SELECT 
            orders.order_id,
            orders.order_date,
            accounts.name AS customer_name,
            products.product_name,
            order_items.quantity,
            order_items.price AS item_price,
            orders.total_price,
            orders.status
        FROM 
            orders
        INNER JOIN 
            accounts ON orders.account_id = accounts.account_id
        INNER JOIN 
            order_items ON orders.order_id = order_items.order_id
        INNER JOIN 
            products ON order_items.product_id = products.product_id
        WHERE 
            accounts.account_id = ?
        ORDER BY 
            orders.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $account_id); // Bind the account_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch and display the order data
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Order ID: " . $row['order_id'] . "<br>";
        echo "Order Date: " . $row['order_date'] . "<br>";
        echo "Customer Name: " . $row['customer_name'] . "<br>";
        echo "Product: " . $row['product_name'] . "<br>";
        echo "Quantity: " . $row['quantity'] . "<br>";
        echo "Item Price: $" . number_format($row['item_price'], 2) . "<br>";
        echo "Total Price: $" . number_format($row['total_price'], 2) . "<br>";
        echo "Status: " . $row['status'] . "<br><hr>";
    }
} else {
    echo "No orders found.";
}

$stmt->close();
?>
