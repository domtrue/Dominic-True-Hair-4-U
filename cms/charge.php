<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('your-secret-key');

// Get the payment amount dynamically
$grandTotal = 1000; // Replace with your calculated total in cents (e.g., $10.00 = 1000)

// Create the Payment Intent
try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $grandTotal,
        'currency' => 'nzd', // Use NZD for New Zealand dollars
        'payment_method_types' => ['card'],
    ]);

    // Pass the client secret to the frontend
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>