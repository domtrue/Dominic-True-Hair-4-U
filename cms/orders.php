<?php
include 'setup.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['account_id'])) {
    $accountId = $_SESSION['user_details']['account_id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $firstName = "User";
    $accountId = null;
}

// Fetch orders and associated user names from the database
$query = "SELECT orders.*, accounts.firstname, accounts.lastname 
          FROM orders 
          JOIN accounts ON orders.user_id = accounts.id
          ORDER BY orders.created_at DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="content">
    <h2>Order List</h2>
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
