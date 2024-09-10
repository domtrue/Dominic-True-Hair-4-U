<?php
// Include Stripe PHP library
require_once('lib/stripe-php/init.php'); // Adjust the path as needed

// Set your Stripe API key
\Stripe\Stripe::setApiKey('your_stripe_secret_key'); // Replace with your Stripe secret key

// Retrieve POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$paymentMethodId = $data['payment_method_id'];
$amount = $data['amount'];

// Create a payment intent
try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method' => $paymentMethodId,
        'confirm' => true,
    ]);

    echo json_encode(['success' => true]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle error
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
