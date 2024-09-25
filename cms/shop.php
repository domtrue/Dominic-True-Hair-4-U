<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure all items in a row stretch to the same height */
        .content {
            display: flex;
            flex-wrap: wrap; /* Allow items to wrap to the next row if necessary */
            justify-content: space-around; /* Adjust alignment as needed */
            align-items: flex-start; /* Align items at the top */
        }

        .responsive {
            width: 25%; /* Adjust width as needed for four items per row */
            padding: 10px; /* Adjust padding as needed */
            box-sizing: border-box; /* Ensure padding is included in width calculation */
        }

        @media only screen and (max-width: 875px) {
            .responsive {
                width: 49.99999%;
                margin: 6px 0;
            }
        }

        @media only screen and (max-width: 500px) {
            .responsive {
                width: 100%;
            }
        }

        /* Remove border and padding from gallery */
        .gallery {
            text-align: center; /* Center align text */
            padding: 0; /* Remove padding */
        }

        /* Image style */
        .gallery img {
            width: 100%; /* Make image responsive */
            height: auto; /* Maintain aspect ratio */
        }

        /* Description style */
        .desc {
            margin-top: 10px; /* Margin at the top of each description */
            font-size: 16px; /* Adjust font size as needed */
            color: #333; /* Text color */
        }

        /* Price style */
        .price {
            margin-top: 5px; /* Margin above price */
            font-size: 18px; /* Font size for price */
            color: #000; /* Text color for price */
            font-weight: bold; /* Make price bold */
        }

        /* Button style */
        .button {
            margin-top: 10px; /* Margin above button */
            padding: 10px 20px; /* Padding inside button */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            text-decoration: none; /* Remove underline from text */
            display: inline-block; /* Inline-block for spacing */
        }

        .button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        .content {
            background: linear-gradient(135deg, #8e2de2, #4a00e0);
            height: calc(100vh - 60px); 
        }
    </style>
</head>
<body>
<?php include 'header.php';?>

<div class="content">
<?php
    // Database connection details
    include 'setup.php';


    // SQL query to get all products including the id
    $sql = "SELECT id, name, image, price FROM products";
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Output data for each row
        while ($row = $result->fetch_assoc()) {
            echo '<div class="responsive">';
            echo '<div class="gallery">';
            echo '<a target="_blank" href="img/' . htmlspecialchars($row["image"]) . '">';
            echo '<img src="img/' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
            echo '</a>';
            echo '<div class="desc">' . htmlspecialchars($row["name"]) . '</div>';
            echo '<div class="price">$' . number_format($row["price"], 2) . '</div>';
            echo '<a href="add_to_cart.php?id=' . $row["id"] . '&quantity=1" class="button">Add to Cart</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found</p>';
    }

    // Close connection
    $conn->close();
    ?>
</div>

<?php include 'footer.php';?>
<script src="script.js"></script>
<script src="slideshow.js"></script>
</body>
</html>
