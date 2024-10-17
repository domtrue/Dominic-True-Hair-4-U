<?php
include 'setup.php'; 
// Include the 'setup.php' file to establish a connection with the database

if (isset($_POST['region']) && isset($_POST['shipping_type'])) {
    // Check if both 'region' and 'shipping_type' POST parameters are set (form submission data)

    $regionId = $_POST['region']; 
    // Get the selected region ID from the POST request

    $shippingType = $_POST['shipping_type']; 
    // Get the selected shipping type (e.g., 'urban' or 'rural') from the POST request

    // SQL query to retrieve the shipping cost based on the selected region and shipping type
    $sql = "SELECT price FROM shipping_rates WHERE region_id = ? AND shipping_type = ?";
    
    $stmt = $conn->prepare($sql); 
    // Prepare the SQL statement to prevent SQL injection

    $stmt->bind_param("is", $regionId, $shippingType); 
    // Bind the region ID (integer) and shipping type (string) parameters to the SQL query

    $stmt->execute(); 
    // Execute the query

    $stmt->bind_result($shippingCost); 
    // Bind the result (shipping cost) to the $shippingCost variable

    $stmt->fetch(); 
    // Fetch the result of the query

    $stmt->close(); 
    // Close the statement to free resources

    session_start(); 
    // Start the session to access session variables

    $orderTotal = $_SESSION['order_total']; 
    // Retrieve the order total stored in the session (set earlier in the checkout process)

    $grandTotal = $orderTotal + $shippingCost; 
    // Calculate the grand total by adding the shipping cost to the order total

    // Return the shipping cost and grand total as a JSON response
    echo json_encode([
        'shippingCost' => $shippingCost, 
        // Include the shipping cost in the response

        'grandTotal' => $grandTotal 
        // Include the grand total in the response
    ]);
}
?>

