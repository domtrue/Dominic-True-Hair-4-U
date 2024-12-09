<?php
session_start();
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use \Stripe\Stripe;
use \Stripe\PaymentIntent;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access environment variables
$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];

// Retrieve the payment method ID from the request
$paymentMethodId = json_decode(file_get_contents('php://input'))->payment_method_id;

// Decrypt the encrypted data
$encryptionKey = $_ENV['ENCRYPTION_KEY'];
$iv = $_ENV['ENCRYPTION_IV'];

function decryptData($data, $key, $iv) {
    $cipher = openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return $cipher;
}

$decryptedNameOnCard = decryptData($_POST['name_on_card'], $encryptionKey, $iv);
$decryptedCountryRegion = decryptData($_POST['country_region'], $encryptionKey, $iv);
$decryptedZipCode = decryptData($_POST['zip_code'], $encryptionKey, $iv);

// Set your secret key. Remember to switch to your live secret key in production!
Stripe::setApiKey($stripeSecretKey);

// Create a PaymentIntent with the amount and payment method
try {
    $paymentIntent = PaymentIntent::create([
        'payment_method' => $paymentMethodId,
        'confirm' => true,
        'return_url' => 'https://example.com/success.php',
    ]);

    echo json_encode(['success' => true]);
} catch (\Stripe\Exception\CardException $e) {
    echo json_encode(['error' => $e->getError()->message]);
} catch (\Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
