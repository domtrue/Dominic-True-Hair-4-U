<?php
include 'setup.php'; // Database connection

if (isset($_POST['region']) && isset($_POST['shipping_type'])) {
    $regionId = $_POST['region'];
    $shippingType = $_POST['shipping_type'];

    // Fetch shipping cost from the database based on region and shipping type
    $sql = "SELECT price FROM shipping_rates WHERE region_id = ? AND shipping_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $regionId, $shippingType);
    $stmt->execute();
    $stmt->bind_result($shippingCost);
    $stmt->fetch();
    $stmt->close();

    // Calculate the grand total (assuming $orderTotal is stored in session)
    session_start();
    $orderTotal = $_SESSION['order_total'];
    $grandTotal = $orderTotal + $shippingCost;

    // Return the shipping cost and grand total as JSON
    echo json_encode([
        'shippingCost' => $shippingCost,
        'grandTotal' => $grandTotal
    ]);
}
?>
