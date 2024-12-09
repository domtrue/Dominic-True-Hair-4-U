<?php
session_start();
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Stripe\Stripe;
use Stripe\PaymentIntent;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access sensitive environment variables
$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];
$encryptionKey = $_ENV['ENCRYPTION_KEY'];
$iv = $_ENV['ENCRYPTION_IV'];

// Set your Stripe API key
Stripe::setApiKey($stripeSecretKey);

// Helper function for decryption
function decryptData($data, $key, $iv) {
    return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
}

// Retrieve and validate input
$requestPayload = json_decode(file_get_contents('php://input'), true);
$paymentMethodId = $requestPayload['payment_method_id'] ?? null;

if (!$paymentMethodId) {
    echo json_encode(['error' => 'Payment method ID is missing']);
    exit();
}

// Decrypt sensitive POST data
$decryptedNameOnCard = decryptData($_POST['name_on_card'] ?? '', $encryptionKey, $iv);
$decryptedCountryRegion = decryptData($_POST['country_region'] ?? '', $encryptionKey, $iv);
$decryptedZipCode = decryptData($_POST['zip_code'] ?? '', $encryptionKey, $iv);

// Validate decrypted data
if (!$decryptedNameOnCard || !$decryptedCountryRegion || !$decryptedZipCode) {
    echo json_encode(['error' => 'Invalid or missing payment details']);
    exit();
}

// Create and confirm a PaymentIntent
try {
    $paymentIntent = PaymentIntent::create([
        'payment_method' => $paymentMethodId,
        'amount' => $_SESSION['order_total'], // Replace with actual order amount
        'currency' => 'nzd', // Use appropriate currency
        'confirmation_method' => 'manual',
        'confirm' => true,
    ]);

    // If payment is successful, handle the order
    if ($paymentIntent->status === 'succeeded') {
        // Insert order
        $user_id = $_SESSION['user_id'];
        $grandTotal = $_SESSION['order_total'];

        $sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('id', $user_id, $grandTotal);
            if (!$stmt->execute()) {
                echo json_encode(['error' => 'Order insert error: ' . $stmt->error]);
                exit();
            }
            $order_id = $stmt->insert_id;
            $_SESSION['order_id'] = $order_id;

            // Insert order items
            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $subtotal = $quantity * $price;

                $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                        VALUES (?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('iiidi', $order_id, $product_id, $quantity, $price, $subtotal);
                    if (!$stmt->execute()) {
                        echo json_encode(['error' => 'Order items error: ' . $stmt->error]);
                        exit();
                    }
                } else {
                    echo json_encode(['error' => 'Database prepare error: ' . $conn->error]);
                    exit();
                }
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Order insert error: ' . $conn->error]);
            exit();
        }
    } else {
        echo json_encode(['error' => 'Payment confirmation pending']);
    }
} catch (\Stripe\Exception\CardException $e) {
    echo json_encode(['error' => $e->getError()->message]);
} catch (\Exception $e) {
    echo json_encode(['error' => 'Payment failed: ' . $e->getMessage()]);
}
?>
