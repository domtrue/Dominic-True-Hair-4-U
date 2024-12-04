<?php
session_start();
require 'vendor/autoload.php';

if (!isset($_SESSION['grand_total'])) {
    header('Location: checkout.php');
    exit();
}

$grandTotal = $_SESSION['grand_total']; // Assume this is in dollars
$grandTotalCents = intval(round($grandTotal * 100)); // Convert to cents

\Stripe\Stripe::setApiKey('sk_test_your_secret_key'); // Use your Stripe secret key

try {
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