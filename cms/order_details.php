<?php
include 'setup.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
// Check user role
$role = $_SESSION['role']; // 'admin' or 'customer'
$accountId = $_SESSION['user_id']; // Current user's ID

// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['id'])) {
    $accountId = $_SESSION['user_details']['id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $firstName = "User";
    $accountId = null;
}

// Get the order ID from the URL
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId > 0) {
    // Fetch order details
    $orderQuery = "SELECT orders.order_id, orders.user_id, orders.grand_total, orders.status, orders.created_at, accounts.firstname, accounts.lastname
                   FROM orders
                   JOIN accounts ON orders.user_id = accounts.id
                   WHERE orders.order_id = ?";
    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $orderResult = $stmt->get_result();
    $orderDetails = $orderResult->fetch_assoc();

    if ($orderDetails) {
        // Fetch order items
        $itemsQuery = "SELECT order_items.product_id, order_items.quantity, order_items.price, order_items.subtotal, products.name as product_name
                       FROM order_items
                       JOIN products ON order_items.product_id = products.id
                       WHERE order_items.order_id = ?";
        $stmt = $conn->prepare($itemsQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $itemsResult = $stmt->get_result();
        $orderItems = $itemsResult->fetch_all(MYSQLI_ASSOC);
    } else {
        // Order not found
        echo "<p>Order not found.</p>";
        exit;
    }
} else {
    // Invalid order ID
    echo "<p>Invalid order ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Details - Admin Dashboard</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php
// Include appropriate navbar
if ($role === 'admin') {
    include 'admin_navbar.php';
} else {
    include 'customer_navbar.php';
} 
?>
<div class="content">
    <h2>Order Details</h2>
    <div class="dashboard">
        <h3>Order ID: <?= $orderDetails['order_id'] ?></h3>
        <p>User: <?= $orderDetails['firstname'] . ' ' . $orderDetails['lastname'] ?></p>
        <p>Status: <?= $orderDetails['status'] ?></p>
        <p>Grand Total: $<?= number_format($orderDetails['grand_total'], 2) ?></p>
        <p>Created At: <?= date('Y-m-d H:i:s', strtotime($orderDetails['created_at'])) ?></p>

        <h4>Items in this Order</h4>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price Each</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name'], ENT_QUOTES) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
