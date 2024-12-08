<?php
session_start();
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the environment variables
$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];
$stripePublishableKey = $_ENV['STRIPE_PUBLISHABLE_KEY'];

// Set the Stripe API key
\Stripe\Stripe::setApiKey($stripeSecretKey);

if (!isset($_SESSION['grand_total'])) {
    // Redirect to checkout if grand total isn't set
    header('Location: checkout.php');
    exit();
}

// Get grand total from session (in dollars)
$grandTotal = $_SESSION['grand_total'];

// Get shipping cost from session (in dollars)
$shippingCost = $_SESSION['shipping_cost'];

// Convert to cents (Stripe requires the smallest currency unit)
$grandTotalCents = intval(round($grandTotal * 100)); // Ensures integer

try {
    // Create a Checkout Session
    $checkoutSession = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'nzd',
                'product_data' => [
                    'name' => 'Order #'.rand(1000, 9999),
                ],
                'unit_amount' => $grandTotalCents,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'https://example.com/success',
        'cancel_url' => 'https://example.com/cancel',
    ]);

    $clientSecret = $checkoutSession->client_secret;
} catch (\Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo 'An error occurred while processing your payment. Please try again.';

    exit();
}

// Fetch allowed countries for payment from Stripe
$allowed_countries = \Stripe\Checkout\Session::retrieve($checkoutSession->id)->payment_method_options->card->networks;

echo '<pre>' . print_r($allowed_countries, true) . '</pre>'; // Debug output

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
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
        }
        .left-half {
            background-color: #6c5ce7; /* Purple background */
            color: white;
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
        }
        .right-half {
            background-color: #f8f9fa; /* Light grey background */
            padding: 40px;
            height: 100vh;
            overflow-y: auto;
        }
        .back-button {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            margin-bottom: 20px;
            display: inline-block;
        }
        .product-list {
            list-style: none;
            padding: 0;
        }
        .product-list li {
            margin-bottom: 10px;
        }
        .product-list img {
            max-width: 100px;
            margin-right: 10px;
        }
        .pay-button {
            background-color: #6c5ce7;
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            font-size: 1.2rem;
            border-radius: 8px;
        }
        .pay-button:hover {
            background-color: #5a4bdb;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Half -->
            <div class="col-md-6 left-half">
                <a href="checkout.php" class="back-button">&larr; Back</a>
                <h2>Your Order</h2>
                <ul class="product-list">
                    <?php
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $product) {
                            echo "<li><img src='{$product['image']}' alt='{$product['name']} Thumbnail' /> {$product['name']} - Quantity: {$product['quantity']} - NZ$ " . number_format($product['price'] * $product['quantity'], 2) . "</li>";
                        }
                    }
                    ?>
                </ul>
                <p>Shipping: NZ$ <?php echo number_format($_SESSION['shipping_cost'], 2); ?></p>
                <p>Total GST: NZ$ <?php echo number_format($_SESSION['gst'], 2); ?></p>
                <h3>Grand Total: NZ$ <?php echo number_format($_SESSION['grand_total'], 2); ?></h3>
            </div>

            <!-- Right Half -->
            <div class="col-md-6 right-half">
                <h2>Pay with Card</h2>
                <form id="payment-form">
                    <div id="payment-element">
                        <input type="text" class="form-control mb-3" placeholder="1234 1234 1234 1234">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control mb-3" placeholder="MM/YY">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control mb-3" placeholder="CVC">
                            </div>
                        </div>
                    </div>
                    <h5>Country or Region</h5>
                    <select id="country-dropdown" class="form-select mb-3">
                        <?php
                        // Fetch allowed countries for payment from Stripe
                        $allowed_countries = \Stripe\Checkout\Session::retrieve($checkoutSession->id)->payment_method_options->card->networks;

                        if (!empty($allowed_countries)) {
                            foreach ($allowed_countries as $country) {
                                echo "<option value='{$country->id}'>{$country->name}</option>";
                            }
                        } else {
                            echo '<option value="">No countries available</option>';
                        }
                        ?>
                    </select>
                    <h5>ZIP</h5>
                    <input type="text" class="form-control mb-4" placeholder="ZIP Code">
                    <button class="pay-button">Pay</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        var stripe = Stripe('<?php echo $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#payment-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const {token, error} = await stripe.createToken(cardElement);
            if (error) {
                console.error(error.message);
            } else {
                // Token created successfully, handle server-side submission
                form.submit();
            }
        });
    </script>
</body>
</html>
