<?php
session_start();
require 'vendor/autoload.php';
include 'setup.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the environment variables
$stripePublishableKey = $_ENV['STRIPE_PUBLISHABLE_KEY'];

if (!isset($_SESSION['grand_total'])) {
    // Redirect to checkout if grand total isn't set
    header('Location: checkout.php');
    exit();
}

// Get grand total and shipping cost from session
$grandTotal = $_SESSION['grand_total'];
$shippingCost = $_SESSION['shipping_cost'];

// Assuming `$_SESSION['order_id']` and `$_SESSION['user_id']` are set during checkout
$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user_id'];
$payment_method_id = $_POST['payment_method_id'];
$card_name = $_POST['card_name'];
$zip_code = $_POST['zip'];
$country = $_POST['country'];

// Insert payment details into the payment_details table
$sql = "INSERT INTO payment_details (order_id, card_name, zip_code, country, payment_method_id, status)
        VALUES (?, ?, ?, ?, ?, 'Success')";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('isssss', $order_id, $card_name, $zip_code, $country, $payment_method_id);

if ($stmt->execute()) {
    // Payment details successfully inserted
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $stmt->error]);
}

// Insert order into the orders table
$total_amount = $_SESSION['grand_total'];
$sql = "INSERT INTO orders (user_id, total_amount, status)
        VALUES (?, ?, 'Pending')";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('id', $user_id, $total_amount);

if ($stmt->execute()) {
    // Order successfully inserted, get the order_id
    $order_id = $stmt->insert_id;

    // Store the order_id in session for use in next steps
    $_SESSION['order_id'] = $order_id;

    // Insert each item into order_items
    $cart_items = $_SESSION['cart_items'];
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $subtotal = $quantity * $price;

        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('iiidi', $order_id, $product_id, $quantity, $price, $subtotal);

        if (!$stmt->execute()) {
            echo json_encode(['error' => $stmt->error]);
            exit();
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $stmt->error]);
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #card-element {
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 4px;
            background: #fff;
        }
        
        .pay-button {
            background-color: #6c5ce7;
            color: white;
            border: none;
            padding: 15px;
            font-size: 1rem;
            border-radius: 8px;
            width: 100%;
        }
        .pay-button:hover {
            background-color: #5a4bdb;
        }
        .form-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment</h2>
        <p>Grand Total: <strong>NZ$ <?php echo number_format($grandTotal, 2); ?></strong></p>
        <form id="payment-form">
            <div class="form-section">
                <label for="name-on-card" class="form-label">Name on Card</label>
                <input type="text" id="name-on-card" class="form-control" placeholder="Name on card" required>
            </div>
            <div class="form-section">
                <label for="card-element" class="form-label">Card Details</label>
                <div id="card-element" class="form-control"></div>
                <div id="card-errors" role="alert" style="color: red; margin-top: 10px;"></div>
            </div>
            <div class="form-section">
                <label for="country" class="form-label">Country/Region</label>
                <select id="country" class="form-select" required>
                    <option value="NZ">New Zealand</option>
                    <!-- Add more countries as needed -->
                </select>
            </div>
            <div class="form-section">
                <label for="zip" class="form-label">ZIP Code</label>
                <input type="text" id="zip" class="form-control" placeholder="ZIP Code" required>
            </div>
            <button class="pay-button" id="submit-button">Pay Now</button>
        </form>
    </div>

    <script>
        // Initialize Stripe
        const stripe = Stripe('<?php echo $stripePublishableKey; ?>');
        const elements = stripe.elements();

        // Create a card element
        const card = elements.create('card', {
            style: {
                base: {
                    color: "#32325d",
                    fontFamily: 'Arial, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#aab7c4"
                    }
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            }
        });

        // Mount the card element to the div
        card.mount('#card-element');

        // Handle form submission
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Create PaymentMethod
            const {paymentMethod, error} = await stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: {
                    name: document.getElementById('name-on-card').value,
                    address: {
                        postal_code: document.getElementById('zip').value,
                        country: document.getElementById('country').value,
                    }
                }
            });

            if (error) {
                // Display error in #card-errors
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // Send payment method ID to server
                const response = await fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({payment_method_id: paymentMethod.id})
                });

                const result = await response.json();

                if (result.error) {
                    // Display error from server
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error;
                } else if (result.success) {
                    // Redirect to success page
                    window.location.href = 'success.php';
                } else if (result.requires_action) {
                    // Handle 3D Secure
                    await stripe.handleCardAction(result.payment_intent_client_secret)
                        .then(async (result) => {
                            if (result.error) {
                                document.getElementById('card-errors').textContent = result.error.message;
                            } else {
                                // Retry payment confirmation
                                const retryResponse = await fetch('process_payment.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({payment_intent_id: result.paymentIntent.id})
                                });

                                const retryResult = await retryResponse.json();

                                if (retryResult.success) {
                                    window.location.href = 'success.php';
                                } else {
                                    document.getElementById('card-errors').textContent = retryResult.error;
                                }
                            }
                        });
                }
            }
        });
    </script>
</body>
</html>
