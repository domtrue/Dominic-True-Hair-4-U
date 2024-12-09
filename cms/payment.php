<?php
session_start();
require 'vendor/autoload.php';
include 'setup.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$stripePublishableKey = $_ENV['STRIPE_PUBLISHABLE_KEY'];

// Ensure session variables exist
if (!isset($_SESSION['grand_total'], $_SESSION['shipping_cost'], $_SESSION['user_id'])) {
    header('Location: checkout.php');
    exit();
}

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

$payment_method_id = $data['payment_method_id'] ?? null;
$card_name = $data['card_name'] ?? null;
$zip_code = $data['zip'] ?? null;
$country = $data['country'] ?? null;


$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user_id'];
$grandTotal = $_SESSION['grand_total'];
$cart_items = $_SESSION['cart_items'] ?? [];
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
