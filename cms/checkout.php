<?php
session_start(); // Start the session
ob_start(); // Buffer output to avoid header issues

// Database connection details
include 'setup.php';

// Ensure there is a cart to checkout
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: shop.php'); // Redirect to cart if empty
    exit();
}

// Retrieve the order subtotal from the session
if (!isset($_SESSION['order_total'])) {
    header('Location: shop.php'); // Redirect to cart if order total is not set
    exit();
}

$orderTotal = $_SESSION['order_total'];

// Ensure the user is logged in
if (!isset($_SESSION['loggedin'])) {
    echo "You are not logged in.";
    exit();
}

// Retrieve user details from the database
if ($stmt = $conn->prepare('SELECT first_name, last_name, email, phone, ad_1 FROM accounts WHERE id = ?')) {
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($first_name, $last_name, $email, $phone, $ad_1);
        $stmt->fetch();
    }
    $stmt->close();
}

// Fetch regions from the database
$sql = "SELECT region_id, region_name FROM regions";
$result = $conn->query($sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['region'], $_POST['shipping-type'])) {
        $regionId = $_POST['region'];
        $shippingType = $_POST['shipping-type'];

        // Fetch the shipping cost from the database
        $sql = "SELECT price FROM shipping_rates WHERE region_id = ? AND shipping_type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $regionId, $shippingType);
        $stmt->execute();
        $stmt->bind_result($shippingCost);
        $stmt->fetch();
        $stmt->close();

        // Calculate the grand total
        $grandTotal = $orderTotal + $shippingCost;

        // Store the grand total in the session
        $_SESSION['grand_total'] = $grandTotal;

        // Redirect to payment.php
        header('Location: payment.php');
        exit();
    } else {
        // Handle missing form fields (region or shipping type)
        echo "Please select both a region and a shipping type.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
    <div class="container">

        <h1>CHECKOUT</h1>
        <h2>PERSONAL DETAILS</h2>
        <form action="" method="post">
            <div class="form-section">
                <div class="form-group">
                <div class="form-control">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required value="<?php echo $first_name;?>">
                    </div>
                    <div class="form-control">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required value="<?php echo $last_name;?>">
                    </div>
                    <div class="form-control">
                        <label for="email">Email Address:</label>
                        <input type="text" id="email" name="email" required value="<?php echo $email;?>">
                    </div>
                    <div class="form-control">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" required value="<?php echo $phone;?>">
                    </div>
                    <h2>SHIPPING INFORMATION</h2>
                    <div class="form-control full-width">
                        <label for="address">Street Address:</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    <div class="form-control full-width">
                        <label for="apartment">Apartment, Suite, Unit:</label>
                        <input type="text" id="apartment" name="apartment">
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
                    <div class="form-control full-width">
                        <label for="postal_code">Postal Code:</label>
                        <input type="text" id="postal_code" name="postal_code" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>SHIPPING METHOD</h2>
                <div class="form-group">
                <label for="shipping-type">Shipping Type:</label>
                <select id="shipping-type" name="shipping-type" required class="full-width">
                <option value="">Select Shipping Type</option>
                    <option value="urban">Urban Delivery</option>
                    <option value="rural">Rural Delivery</option>
                </select>
            </div>

            </div>
            <div class="summary-section">
    <div class="order-summary">
        <h2>ORDER SUMMARY</h2>
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
    </div>
    <div>
                <button type="submit" class="btn-proceed">Proceed to Payment</button>
            </div>
        </form>
    </div>

    <script>
document.getElementById('region').addEventListener('change', updateShippingCost);
document.getElementById('shipping-type').addEventListener('change', updateShippingCost);

function updateShippingCost() {
    var regionId = document.getElementById('region').value;
    var shippingType = document.getElementById('shipping-type').value;

    if (regionId && shippingType) {
        // Send AJAX request to fetch shipping cost
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'calculate_shipping.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Update shipping cost and grand total in the summary
                var response = JSON.parse(xhr.responseText);
                document.querySelector('.summary-item span.shipping').innerHTML = '$' + parseFloat(response.shippingCost).toFixed(2);
                document.querySelector('.summary-item span.grand-total').innerHTML = '$' + parseFloat(response.grandTotal).toFixed(2);
            }
        };
        xhr.send('region=' + regionId + '&shipping_type=' + shippingType);
    }
}
    </script>
</body>
</html>
