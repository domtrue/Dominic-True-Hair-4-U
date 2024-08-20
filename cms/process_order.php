<?php
session_start(); // Start the session

// Check if the cart exists and form is submitted
if (isset($_SESSION['cart']) && !empty($_SESSION['cart']) && isset($_POST['name']) && isset($_POST['address'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    
   // Database connection details
   include 'setup.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert order into database (this is a simplified example)
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $sql = "INSERT INTO orders (product_id, quantity, name, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $productId, $quantity, $name, $address);
        $stmt->execute();
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Redirect to a confirmation page
    header("Location: order_confirmation.php");
    exit();
} else {
    echo '<p>Your cart is empty or invalid data.</p>';
}
?>
