<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/shop.css">
</head>
<body>
<?php include 'header.php'; ?> 
<!-- Include the header file, which likely contains navigation, CSS links, and other page elements -->

<div class="product-grid">
    <?php
    include 'setup.php'; 
    // Include the setup file to connect to the database

    // SQL query to retrieve all products including their id, name, image, and price
    $sql = "SELECT id, name, image, price FROM products";
    $result = $conn->query($sql); 
    // Execute the SQL query and store the result set

    // Check if any products were returned by the query
    if ($result->num_rows > 0) {
        // Loop through each product in the result set
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">'; 
            // Create a new div for each product

            echo '<div class="gallery">'; 
            // Create a div to display the product image and other details

            // Create a clickable link for the product image that opens the full-sized image in a new tab
            echo '<a target="_blank" href="img/' . htmlspecialchars($row["image"]) . '">'; 

            // Display the product image and ensure special characters are escaped to prevent XSS attacks
            echo '<img src="img/' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">'; 
            echo '</a>'; // Close the anchor tag

            // Display the product name inside a div with a description class
            echo '<div class="desc">' . htmlspecialchars($row["name"]) . '</div>'; 

            // Display the product price formatted to two decimal places
            echo '<div class="price">$' . number_format($row["price"], 2) . '</div>'; 

            // Create an "Add to Cart" button with a link passing the product id and quantity as GET parameters
            echo '<a href="add_to_cart.php?id=' . $row["id"] . '&quantity=1" class="button">Add to Cart</a>'; 

            echo '</div>'; // Close the gallery div
            echo '</div>'; // Close the product div
        }
    } else {
        // Display a message if no products are found in the database
        echo '<p>No products found</p>';
    }

    $conn->close(); 
    // Close the database connection to free resources
    ?>
</div>


<?php include 'footer.php';?>
<script src="script.js"></script>
<script src="slideshow.js"></script>
</body>
</html>
