<?php
session_start();
require 'vendor/autoload.php';
include 'setup.php';

use Dotenv\Dotenv;
use Stripe\Stripe;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

// Ensure session variables exist
if (!isset($_SESSION['grand_total'], $_SESSION['user_id'])) {
    header('Location: checkout.php');
    exit();
}

// Order details
$grandTotal = $_SESSION['grand_total']; // Total amount in dollars
$order_id = $_SESSION['order_id'] ?? uniqid('order_'); // Create an order_id if not set
$user_id = $_SESSION['user_id'];

// Convert total to cents as Stripe works with the smallest currency unit
$amount = intval($grandTotal * 100);

// Create PaymentIntent
try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd', // Replace with your currency (e.g., 'nzd' for New Zealand Dollar)
        'metadata' => [
            'order_id' => $order_id,
            'user_id' => $user_id,
        ],
    ]);
    $clientSecret = $paymentIntent->client_secret;
} catch (\Exception $e) {
    die('Error creating PaymentIntent: ' . $e->getMessage());
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
        <h2 class="text-center">Secure Payment</h2>
        <form id="payment-form">
            <div class="form-section">
                <label for="card_name">Name on Card</label>
                <input type="text" id="card_name" class="form-control" required>
            </div>
            <div class="form-section">
                <label for="country">Country or Region</label>
                <select id="country" class="form-control" required>
                    <option value="NZ">New Zealand</option>
                    <!-- Add more countries as needed -->
                </select>
            </div>
            <div class="form-section">
                <label for="zip">ZIP Code</label>
                <input type="text" id="zip" class="form-control" required>
            </div>
            <div id="card-element" class="form-section"></div>
            <button id="submit" class="pay-button">Pay</button>
        </form>
        <p id="error-message" class="text-danger"></p>
    </div>

    <script>
        const stripe = Stripe('<?php echo $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
        const clientSecret = "<?php echo $clientSecret; ?>";

        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        document.getElementById('payment-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const cardName = document.getElementById('card_name').value;
            const country = document.getElementById('country').value;
            const zip = document.getElementById('zip').value;

            // Save the data in sessions
            const formData = {
                card_name: cardName,
                country_region: country,
                zip_code: zip,
            };

            const response = await fetch('save_session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData),
            });

            if (!response.ok) {
                document.getElementById('error-message').innerText = 'Failed to save session data.';
                return;
            }

            try {
                const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardName,
                            address: { country: country, postal_code: zip },
                        },
                    },
                });

                if (error) {
                    document.getElementById('error-message').innerText = error.message;
                    return;
                }

                if (paymentIntent.status === 'succeeded') {
                    alert('Payment successful!');
                    window.location.href = 'success.php';
                }
            } catch (err) {
                document.getElementById('error-message').innerText = 'An unexpected error occurred.';
            }
        });
    </script>
</body>
</html>
