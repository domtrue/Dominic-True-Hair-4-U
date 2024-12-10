<?php
session_start();
require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use Dotenv\Dotenv;
use Stripe\Stripe;
use Stripe\PaymentIntent;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];
$encryptionKey = $_ENV['ENCRYPTION_KEY'];
$iv = $_ENV['ENCRYPTION_IV'];

// Set Stripe API key
Stripe::setApiKey($stripeSecretKey);

// Helper function for decryption
function decryptData($data, $key, $iv) {
    return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv) ?: null;
}

header("Content-Type: application/json");

// Read and decode JSON payload
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit();
}

// Extract and validate data
$paymentMethodId = $data['payment_method_id'] ?? null;
$nameOnCard = decryptData($data['name_on_card'] ?? '', $encryptionKey, $iv);
$countryRegion = decryptData($data['country_region'] ?? '', $encryptionKey, $iv);
$zipCode = decryptData($data['zip_code'] ?? '', $encryptionKey, $iv);

if (!$paymentMethodId || !$nameOnCard || !$countryRegion || !$zipCode) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid payment details']);
    exit();
}

try {
    // Create a PaymentIntent
    $paymentIntent = PaymentIntent::create([
        'payment_method' => $paymentMethodId,
        'amount' => $_SESSION['order_total'], // Replace with actual amount
        'currency' => 'nzd',
        'confirmation_method' => 'manual',
        'confirm' => true,
    ]);

    if ($paymentIntent->status === 'succeeded') {


        echo json_encode(['success' => true, 'order_id' => $order_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Payment confirmation pending']);
    }
} catch (\Stripe\Exception\CardException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getError()->message]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

if ($paymentIntent->status === 'succeeded') {
    $_SESSION['payment_status'] = 'Success';
    header("Location: order_process.php");
    exit();
}

?>