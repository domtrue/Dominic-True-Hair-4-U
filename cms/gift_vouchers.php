<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Vouchers - Hair 4 U</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/shop.css">
    <style>
        /* Add a fade-in and scale animation */
        body {
            animation: fadeInScale 1.5s ease-in-out;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?> 

<div class="product-grid">
    <?php
    include 'setup.php'; // Include database connection

    // SQL query to retrieve all gift vouchers including their id, name, image, and price
    $sql = "SELECT id, name, image, price FROM gift_vouchers";
    $result = $conn->query($sql);

    // Check if any gift vouchers were returned
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            echo '<div class="gallery">';
            echo '<a target="_blank" href="img/' . htmlspecialchars($row["image"]) . '">';
            echo '<img src="img/' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
            echo '</a>';
            echo '<div class="desc">' . htmlspecialchars($row["name"]) . '</div>';
            echo '<div class="price">$' . number_format($row["price"], 2) . '</div>';
            echo '<a href="add_to_cart.php?id=' . $row["id"] . '&quantity=1&type=voucher" class="button">Add to Cart</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No gift vouchers found</p>';
    }

    $conn->close(); // Close the database connection
    ?>
</div>

<?php include 'footer.php';?>
<script src="js/script.js"></script>
<script src="js/slideshow.js"></script>
</body>
</html>
