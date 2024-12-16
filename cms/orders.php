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

// Prepare query based on role
if ($role === 'admin') {
    // Admin: Fetch all orders
    $query = "SELECT orders.*, accounts.firstname, accounts.lastname 
              FROM orders 
              JOIN accounts ON orders.user_id = accounts.id
              ORDER BY orders.created_at DESC";
    $stmt = $conn->prepare($query);
} else {
    // Customer: Fetch only their orders
    $query = "SELECT orders.*, accounts.firstname, accounts.lastname 
              FROM orders 
              JOIN accounts ON orders.user_id = accounts.id
              WHERE accounts.id = ?
              ORDER BY orders.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $accountId);
}

// Execute query
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
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
    <h2><?php echo ($role === 'admin') ? "All Orders" : "My Orders"; ?></h2>
    <table class="order-list">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User Name</th>
                <th>Grand Total</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname'], ENT_QUOTES) . " " . htmlspecialchars($row['lastname'], ENT_QUOTES) . "</td>";
                    echo "<td>$" . $row['grand_total'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td><a href='order_details.php?order_id=" . $row['order_id'] . "'>View Details</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
