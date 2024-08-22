<?php
session_start();

// Check if payment method is selected
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['payment_method'])) {
    header('Location: payment.php'); // Redirect if not posted
    exit();
}

// Save payment method to session
$_SESSION['payment_method'] = $_POST['payment_method'];

// Process order (e.g., save to database, etc.)
$order_number = rand(1000, 9999); // Generate a dummy order number

// Send confirmation email (simplified version)
$to = $_SESSION['shipping']['email'];
$subject = "Order Confirmation";
$message = "Thank you for your order. Your order number is $order_number.";
$headers = "From: no-reply@example.com";

mail($to, $subject, $message, $headers);

// Clear cart and shipping information
unset($_SESSION['cart']);
unset($_SESSION['shipping']);
unset($_SESSION['payment_method']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order! Your order number is <strong><?php echo htmlspecialchars($order_number); ?></strong>.</p>
        <p>An invoice will be sent to your email address.</p>
        <a href="index.php">Return to Home</a>
    </div>
</body>
</html>
