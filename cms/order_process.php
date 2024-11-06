<?php
// Include necessary files and Stripe PHP library
require 'vendor/autoload.php'; // Make sure the Stripe library is installed
\Stripe\Stripe::setApiKey('your_secret_key');

// Retrieve the raw body to verify Stripe’s signature
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = 'your_webhook_secret';

try {
    // Verify that the event was sent by Stripe
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

    if ($event->type == 'payment_intent.succeeded') {
        $paymentIntent = $event->data->object;

        // Extract customer and order details from paymentIntent
        $customer_id = $paymentIntent->metadata->customer_id; // Set this in payment.php
        $total_price = $paymentIntent->amount_received / 100; // Convert cents to dollars
        $cart_items = json_decode($paymentIntent->metadata->cart_items, true);

        // Begin order recording transaction
        $pdo->beginTransaction();

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, order_date, total_price, status) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$customer_id, $total_price, 'confirmed']);
        $order_id = $pdo->lastInsertId();

        // Insert each item into order_items table
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['unit_price']]);
        }

        // Commit the transaction
        $pdo->commit();

        echo "Order successfully recorded.";
    }
} catch (Exception $e) {
    http_response_code(400);
    echo "Webhook error: " . $e->getMessage();
}
?>
