<?php
session_start(); // Start the session

// Ensure there is a cart to checkout
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php'); // Redirect to cart if empty
    exit();
}

// Retrieve the order subtotal from the session
if (!isset($_SESSION['order_total'])) {
    header('Location: cart.php'); // Redirect to cart if order total is not set
    exit();
}

$orderTotal = $_SESSION['order_total'];

// Placeholder values for the shipping options
$shippingOptions = [
    'Standard Shipping' => 5.00,
    'Express Shipping' => 10.00,
    'Overnight Shipping' => 20.00
];

// Default shipping cost (could be updated based on user selection)
$shippingCost = array_values($shippingOptions)[0]; // Default to the first shipping option
$grandTotal = $orderTotal + $shippingCost;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file -->
    <style>
       /* Import a classic serif font for headings */
@import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap');

body {
    background: #f4f4f4;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 800px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 2rem;
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('path-to-your-pattern-image.png') repeat;
    opacity: 0.1; /* Adjust the opacity for subtlety */
    z-index: 0; /* Ensure background is behind form fields */
}

.logo {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.logo img {
    max-width: 150px; /* Adjust size as needed */
}

h1, h2 {
    text-align: center;
    color: #000; /* Black color for text */
    font-family: 'Merriweather', serif; /* Classic serif font */
    margin: 0;
    position: relative;
    z-index: 1;
}

h2 {
    font-size: 1.5rem;
    margin-top: 1rem;
}

.form-section, .summary-section {
    margin-bottom: 2rem;
}

.form-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.form-group .form-control {
    flex: 1;
    min-width: calc(50% - 1rem); /* Adjust for gap */
    z-index: 2;
}

.form-group .form-control.full-width {
    flex: 1;
    min-width: 100%;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #000; /* Black color for labels */
}

.form-group input, .form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem; /* Ensure readable font size */
    position: relative;
    z-index: 2;
}

.summary-section {
    border-top: 1px solid #ddd;
    padding-top: 1rem;
}

.order-summary {
    display: flex;
    flex-direction: column;
}

.summary-item {
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
}

.summary-item span {
    font-weight: bold;
}

.btn-proceed {
    background-color: #4a148c; /* Rich purple color */
    color: #fff;
    padding: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    text-align: center;
    margin-top: 1rem;
    display: block;
    position: relative;
    z-index: 1;
}

.btn-proceed:hover {
    background-color: #6a1b9a; /* Slightly lighter purple for hover */
}

.guest-checkout {
    margin-top: 1rem;
    text-align: center;
}

.guest-checkout label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

.guest-checkout input {
    margin-right: 0.5rem;
}

    </style>
</head>
<body>
    <div class="container">

        <h1>CHECKOUT</h1>
        <h2>PERSONAL DETAILS</h2>
        <form action="payment.php" method="post">
            <div class="form-section">
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
                        <input type="text" id="email" name="email" required>
                    </div>
                    <div class="form-control">
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" required>
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
                            <!-- Example regions; replace with actual options -->
                            <option value="">Select Region</option>
                            <option value="region1">Region 1</option>
                            <option value="region2">Region 2</option>
                            <option value="region3">Region 3</option>
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
                    <label for="shipping-method">Select Shipping Method:</label>
                    <select id="shipping-method" name="shipping-method" required class="full-width">
                        <option value="">Select Shipping Method</option>
                        <?php foreach ($shippingOptions as $method => $cost): ?>
                            <option value="<?php echo htmlspecialchars($cost); ?>"><?php echo htmlspecialchars($method); ?></option>
                        <?php endforeach; ?>
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
                        <span>Shipping:</span> $<?php echo number_format($shippingCost, 2); ?>
                    </div>
                    <div class="summary-item">
                        <span>Grand Total:</span> $<?php echo number_format($grandTotal, 2); ?>
                    </div>
                </div>
                <button type="submit" class="btn-proceed">Proceed to Payment</button>
            </div>
        </form>
    </div>

    <script>
        function toggleGuestCheckout() {
            var guestCheckout = document.getElementById('guest-checkout');
            if (guestCheckout.style.display === 'none') {
                guestCheckout.style.display = 'block';
            } else {
                guestCheckout.style.display = 'none';
            }
        }
    </script>
</body>
</html>
