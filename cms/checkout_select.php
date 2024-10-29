<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Checkout Option</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>How would you like to checkout?</h1>
        <div class="checkout-options">
            <button onclick="window.location.href='login.php'">Login / Signup</button>
            <button onclick="window.location.href='guest_checkout.php'">Checkout as Guest</button>
        </div>
    </div>
</body>
</html>
