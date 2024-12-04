<?php
session_start();
require 'vendor/autoload.php';

if (!isset($_SESSION['grand_total'])) {
    // Redirect to checkout if grand total isn't set
    header('Location: checkout.php');
    exit();
}

// Get grand total from session (in dollars)
$grandTotal = $_SESSION['grand_total'];

// Convert to cents (Stripe requires the smallest currency unit)
$grandTotalCents = intval(round($grandTotal * 100)); // Ensures integer

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('');

try {
    // Create a PaymentIntent with the correct amount in cents
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $grandTotalCents,
        'currency' => 'nzd',
        'payment_method_types' => ['card'],
    ]);

    $clientSecret = $paymentIntent->client_secret;
} catch (\Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <link rel="stylesheet" href="css/payment.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>Secure Payment</h2>
    <div>
        Grand Total: $<?php echo number_format($grandTotal, 2); ?>
    </div>
    <div id="payment-element"></div> <!-- Stripe accordion UI will mount here -->
    <button id="submit-button">Pay Now</button>

    <div id="payment-message" class="hidden"></div>

    <script>
        const stripe = Stripe('pk_test_your_publishable_key'); // Replace with your publishable key
        const clientSecret = "<?php echo $clientSecret; ?>";
    </script>
    <script src="../payment.js"></script> <!-- Include the custom JavaScript -->
</body>
</html>
