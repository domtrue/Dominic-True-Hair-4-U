<?php
include 'setup.php'; // Ensure this file contains your database connection details
session_start(); // Start the session

// Ensure grand total is available
if (!isset($_SESSION['grand_total'])) {
    header('Location: checkout.php'); // Redirect if grand total is missing
    exit();
}

$grandTotal = $_SESSION['grand_total'];

// Fetch payment provider details
$sql = "SELECT name, image FROM payment_providers";
$result = $conn->query($sql);

// Placeholder for payment success logic
$paymentSuccess = false; // Default to false

// Example: Check payment status from the gateway
// This is a placeholder for actual integration logic
if (isset($_POST['payment_status']) && $_POST['payment_status'] === 'success') {
    $paymentSuccess = true; // Set to true if the payment was successful
}

if ($paymentSuccess) {
    // Redirect to order_process.php
    header("Location: order_process.php");
    exit();
} else {
    // Handle unsuccessful payment
    echo "<p>Payment was not successful. Please try again.</p>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
       body {
            background: #f5f7f9;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: 90%;
            max-width: 1200px;
            background: #ffffff;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 2rem;
            gap: 2rem;
        }

        .cart-summary, .payment-methods {
            flex: 1;
        }

        .cart-summary {
            border-right: 1px solid #ddd;
            padding-right: 2rem;
        }

        .cart-summary h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item span {
            font-size: 1rem;
            color: #666;
        }

        .total {
            font-weight: 700;
            font-size: 1.25rem;
            margin-top: 1rem;
            border-top: 2px solid #f1f1f1;
            padding-top: 1rem;
        }

        .payment-methods h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 8px;
            background: #f9f9f9;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer; /* Ensures the cursor changes to pointer */
            position: relative; /* Ensures z-index works correctly */
            z-index: 10; /* Sets a higher stacking context */
        }

        .payment-method:hover {
            background: #f1f1f1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .payment-method img {
            width: 40px;
            margin-right: 1rem;
        }

        .payment-method p {
            margin: 0;
            font-size: 0.875rem;
            color: #666;
        }

        .form-section {
            margin-top: 2rem;
        }

        .card-inputs {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card-details {
            display: flex;
            gap: 1rem;
        }

        .card-details .field {
            flex: 1;
        }

        .card-details .field input {
            width: 100%;
        }

        .card-inputs .field {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .field label {
            font-size: 0.875rem;
            color: #555;
        }

        .field input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.875rem;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-proceed {
            background-color: #007bff;
            color: #fff;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            text-align: center;
            margin-top: 1rem;
            display: block;
            transition: background-color 0.3s ease;
        }

        .btn-proceed:hover {
            background-color: #0056b3;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .cart-summary {
                border-right: none;
                padding-right: 0;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 480px) {
            .payment-method img {
                width: 30px;
            }

            .btn-proceed {
                font-size: 0.875rem;
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cart-summary">
            <h2>Cart Summary</h2>
            <div class="summary-item total">
                <span>Grand Total</span>
                <span>$<?php echo number_format($grandTotal, 2); ?></span>
            </div>
        </div>
        <div class="payment-methods">
            <h2>Select Payment Method</h2>
            <?php
            if ($result->num_rows > 0) {
                // Loop through each payment provider and display its logo and name
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="payment-method" id="' . strtolower($row['name']) . '-method">';
                    echo '<img src="img/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<p>' . htmlspecialchars($row['name']) . '</p>';
                    echo '</div>';
                }
            }
            $conn->close(); // Close the database connection
            ?>
          
            <!-- Add additional payment methods here -->
            <form id="payment-form" style="display: none;">
                <input type="hidden" id="grand-total-cents" value="<?php echo $grandTotalCents; ?>">
                <div id="payment-request-button"></div>
                <div class="card-inputs">
                    <div class="field">
                        <label for="card-name">Cardholder Name</label>
                        <input type="text" id="card-name" placeholder="Name on Card">
                    </div>
                    <div class="field card-details">
                        <div class="field">
                            <label for="card-number">Card Number</label>
                            <div id="card-element"></div>
                        </div>
                    </div>
                </div>
                <button type="submit" id="submit-button" class="btn-proceed">Pay $<?php echo number_format($grandTotal, 2); ?></button>
            </form>
        </div>
    </div>

    <script>
    const stripe = Stripe('your_stripe_publishable_key'); // Replace with your Stripe publishable key
    const elements = stripe.elements();
    const card = elements.create('card');
    
    const cardMethodButton = document.getElementById('card-method');
    const paymentForm = document.getElementById('payment-form');
    
    cardMethodButton.addEventListener('click', () => {
        if (paymentForm.style.display === 'none' || paymentForm.style.display === '') {
            paymentForm.style.display = 'block';
            card.mount('#card-element');
            document.getElementById('payment-request-button').style.display = 'none';
        } else {
            paymentForm.style.display = 'none';
            card.unmount('#card-element'); // Clean up the card element when hiding the form
        }
    });

    document.getElementById('paypal-method').addEventListener('click', () => {
        // Redirect to PayPal checkout page or open the PayPal modal
        window.location.href = 'https://www.paypal.com/checkout';
    });

    document.getElementById('payment-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const grandTotalCents = parseInt(document.getElementById('grand-total-cents').value);

        const {paymentMethod, error} = await stripe.createPaymentMethod({
            type: 'card',
            card: card,
            billing_details: {
                name: document.getElementById('card-name').value,
            },
        });

        if (error) {
            console.log('Error:', error);
            alert('Payment failed, please try again.');
        } else {
            fetch('/charge.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                    amount: grandTotalCents // Use the grand total in cents
                }),
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    window.location.href = 'thankyou.php';
                } else {
                    alert('Payment failed, please try again.');
                }
            });
        }
    });
    </script>
</body>
</html>
