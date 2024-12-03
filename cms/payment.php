<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('your-secret-key');

// Example grand total (in cents)
$grandTotal = 1000; // Replace this with your dynamically calculated total

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $grandTotal,
        'currency' => 'nzd',
        'payment_method_types' => ['card'],
    ]);

    // Encode both clientSecret and grandTotal for use in the frontend
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
        'grandTotal' => $grandTotal
    ]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <link rel="stylesheet" href="css/payment.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <div class="payment-container">
        <h2>Secure Payment</h2>
        <div class="order-total">
            Grand Total: $<?php echo number_format($grandTotal / 100, 2); ?>
        </div>

        <form id="payment-form" class="stripe-form">
            <label for="card-holder-name">Cardholder Name</label>
            <input type="text" id="card-holder-name" placeholder="Name on Card" required>

            <div id="card-element"></div>
            <div id="card-errors" role="alert" style="color: red; margin-top: 10px;"></div>

            <button type="submit" class="submit-button">
                Pay $<?php echo number_format($grandTotal / 100, 2); ?>
            </button>
        </form>
    </div>

    <script>
    // Fetch the Payment Intent from the backend
    fetch('path-to-backend-script.php', {
        method: 'POST',
    })
    .then(response => response.json())
    .then(data => {
        const clientSecret = data.clientSecret;
        const grandTotal = (data.grandTotal / 100).toFixed(2); // Convert to dollars

        // Display the grand total
        document.querySelector('.grand-total').textContent = `Grand Total: $${grandTotal}`;

        // Set up Stripe payment
        const stripe = Stripe("your-publishable-key");
        const elements = stripe.elements();
        const card = elements.create("card");
        card.mount("#card-element");

        const form = document.getElementById("payment-form");
        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: document.getElementById("card-holder-name").value,
                    },
                },
            });

            if (error) {
                document.getElementById("card-errors").textContent = error.message;
            } else if (paymentIntent.status === "succeeded") {
                alert("Payment successful!");
            }
        });
    })
    .catch(error => {
        console.error("Error:", error);
    });
</script>

</body>
</html>
