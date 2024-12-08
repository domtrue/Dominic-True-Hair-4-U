<?php
session_start(); // Start session to track cart and order

// Database connection
include 'setup.php';

// Ensure there is a cart to proceed with checkout
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: shop.php'); // Redirect if cart is empty
    exit();
}

// Retrieve order subtotal from the session
if (!isset($_SESSION['order_total'])) {
    header('Location: shop.php'); // Redirect if order total is not set
    exit();
}

$orderTotal = $_SESSION['order_total']; // Store subtotal

// Fetch regions from the database
$sql = "SELECT region_id, region_name FROM regions";
$result = $conn->query($sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'],
        $_POST['address'], $_POST['city'], $_POST['region'], $_POST['postal_code'], 
        $_POST['shipping-type'])
    ) {
        $regionId = $_POST['region'];
        $shippingType = $_POST['shipping-type'];

        // Fetch shipping cost from the database
        $sql = "SELECT price FROM shipping_rates WHERE region_id = ? AND shipping_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $regionId, $shippingType);
        $stmt->execute();
        $stmt->bind_result($shippingCost);
        $stmt->fetch();
        $stmt->close();

        // Calculate the grand total
        $grandTotal = $orderTotal + $shippingCost;
        
        // Calculate GST at 15% of the grand total
        $gst = $grandTotal * 0.15;

        // Store the grand total and GST in the session
        $_SESSION['grand_total'] = $grandTotal;
        $_SESSION['shipping_cost'] = $shippingCost;
        $_SESSION['gst'] = $gst;

        // Store guest info in session for review
        $_SESSION['guest_info'] = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'region' => $regionId,
            'postal_code' => $_POST['postal_code'],
            'shipping_type' => $shippingType
        ];

        // Redirect to payment
        header('Location: payment.php');
        exit();
    } else {
        echo "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Checkout</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <div class="container">
        <h1>Guest Checkout</h1>
        <form action="" method="post">
            <h2>Personal Details</h2>
            <div class="form-group">
                <div class="form-control">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-control">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-control">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-control">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
            </div>

            <h2>Shipping Information</h2>
            <div class="form-group">
                <div class="form-control full-width">
                    <label for="address">Street Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-control">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-control">
                    <label for="region">Region:</label>
                    <select id="region" name="region" required>
                        <option value="">Select Region</option>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['region_id']}'>{$row['region_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-control">
                    <label for="postal_code">Postal Code:</label>
                    <input type="text" id="postal_code" name="postal_code" required>
                </div>
            </div>

            <h2>Shipping Method</h2>
            <div class="form-group">
                <label for="shipping-type">Shipping Type:</label>
                <select id="shipping-type" name="shipping-type" required class="full-width">
                    <option value="">Select Shipping Type</option>
                    <option value="urban">Urban Delivery</option>
                    <option value="rural">Rural Delivery</option>
                </select>
            </div>

            <div class="summary-section">
                <h2>Order Summary</h2>
                <div class="summary-item">
                    <span>Subtotal:</span> $<?php echo number_format($orderTotal, 2); ?>
                </div>
                <div class="summary-item">
                    <span>Shipping:</span> <span class="shipping">$<?php echo isset($shippingCost) ? number_format($shippingCost, 2) : '0.00'; ?></span>
                </div>
                <div class="summary-item">
                    <span>Grand Total:</span> <span class="grand-total">$<?php echo isset($grandTotal) ? number_format($grandTotal, 2) : number_format($orderTotal, 2); ?></span>
                </div>
            </div>

            <button type="submit" class="btn-proceed">Proceed to Payment</button>
        </form>
    </div>
    <script>
document.getElementById('region').addEventListener('change', updateShippingCost);
document.getElementById('shipping-type').addEventListener('change', updateShippingCost);

function updateShippingCost() {
    var regionId = document.getElementById('region').value;
    var shippingType = document.getElementById('shipping-type').value;
    var subtotal = <?php echo json_encode($orderTotal); ?>; // Get subtotal from PHP

    // If no valid region or shipping type selected, reset shipping and show subtotal only
    if (!regionId || !shippingType) {
        document.querySelector('.summary-item span.shipping').innerHTML = '$0.00';
        document.querySelector('.summary-item span.grand-total').innerHTML = 
            '$' + parseFloat(subtotal).toFixed(2); // Display subtotal as grand total
        return; // Stop further execution
    }

    // Send AJAX request to fetch shipping cost if valid selections are made
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'calculate_shipping.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Update shipping cost and grand total in the summary
            var response = JSON.parse(xhr.responseText);
            document.querySelector('.summary-item span.shipping').innerHTML = 
                '$' + parseFloat(response.shippingCost).toFixed(2);
            document.querySelector('.summary-item span.grand-total').innerHTML = 
                '$' + (parseFloat(subtotal) + parseFloat(response.shippingCost)).toFixed(2);
        }
    };
    xhr.send('region=' + regionId + '&shipping_type=' + shippingType);
}

    </script>
</body>
</html>
