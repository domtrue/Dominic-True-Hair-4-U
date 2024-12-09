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
        // Order processing and database insertion
        $user_id = $_SESSION['user_id'];
        $grandTotal = $_SESSION['order_total'];

        // Assuming $conn is your database connection
        include 'setup.php';

        $conn->begin_transaction();
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Completed')");
        $stmt->bind_param('id', $user_id, $grandTotal);

        if (!$stmt->execute()) {
            $conn->rollback();
            throw new Exception('Order insertion failed: ' . $stmt->error);
        }

        $order_id = $stmt->insert_id;
        $_SESSION['order_id'] = $order_id;

        // Insert payment details
        $stmt = $conn->prepare("INSERT INTO payment_details (order_id, card_name, zip_code, country, payment_method_id, status) VALUES (?, ?, ?, ?, ?, 'Success')");
        $stmt->bind_param('issss', $order_id, $nameOnCard, $zipCode, $countryRegion, $paymentMethodId);

        if (!$stmt->execute()) {
            $conn->rollback();
            throw new Exception('Payment details insertion failed: ' . $stmt->error);
        }

        // Insert order items
        foreach ($_SESSION['cart_items'] as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('iiidi', $order_id, $item['product_id'], $item['quantity'], $item['price'], $item['quantity'] * $item['price']);
            if (!$stmt->execute()) {
                $conn->rollback();
                throw new Exception('Order item insertion failed: ' . $stmt->error);
            }
        }

        $conn->commit();

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
?>
