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
<?php include 'header.php';?>

<div class="product-grid">
    <?php
    include 'setup.php';

    // SQL query to get all products including the id
    $sql = "SELECT id, name, image, price FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
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

    $conn->close();
    ?>
</div>


<?php include 'footer.php';?>
<script src="script.js"></script>
<script src="slideshow.js"></script>
</body>
</html>
