<?php
session_start();

// Check if shipping info is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['name'])) {
    header('Location: shipping.php'); // Redirect if not posted
    exit();
}

// Save shipping info to session
$_SESSION['shipping'] = [
    'name' => $_POST['name'],
    'address' => $_POST['address'],
    'email' => $_POST['email']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Payment Method</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="container">
        <h1>Select Payment Method</h1>
        <form action="process_order.php" method="post">
            <label for="payment-method">Payment Method:</label>
            <select id="payment-method" name="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <!-- Add more payment methods as needed -->
            </select>
            
            <input type="submit" value="Proceed to Payment">
        </form>
    </div>
</body>
</html>
