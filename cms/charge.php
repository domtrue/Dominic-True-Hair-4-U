<?php
session_start();
require 'vendor/autoload.php';

if (!isset($_SESSION['grand_total'])) {
    header('Location: checkout.php');
    exit();
}

$grandTotal = $_SESSION['grand_total']; // Assume this is in dollars
$grandTotalCents = intval(round($grandTotal * 100)); // Convert to cents

\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY')); // Retrieve the Stripe secret key from the environment variable

try {
    // Create a PaymentIntent with the specified amount in cents
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $grandTotalCents,
        'currency' => 'nzd',
        'payment_method_types' => ['card'],
        'payment_method_options' => [
            'card' => [
                'request_three_d_secure' => 'automatic',
            ],
        ],
    ]);

    $clientSecret = $paymentIntent->client_secret;
} catch (\Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
    exit();
}
?>
