<?php
session_start(); // Start the session to track the cart session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/shop.css">
</head>
<style>
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
<body>
<?php include 'header.php'; ?> 
<div class="product-grid">
    <?php
    include 'setup.php'; 

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
            echo '<button class="add-to-cart" data-id="' . $row["id"] . '" data-name="' . htmlspecialchars($row["name"]) . '" data-price="' . $row["price"] . '" data-image="img/' . htmlspecialchars($row["image"]) . '">Add to Cart</button>'; 
            echo '</div>'; 
            echo '</div>'; 
        }
    } else {
        echo '<p>No products found</p>';
    }

    $conn->close(); 
    ?>
</div>

<!-- Modal Structure -->
<div id="cartModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <img id="modalImage" src="" alt="Product Thumbnail">
        <h2 id="modalName"></h2>
        <p id="modalPrice"></p>
        <p>Added to cart!</p>
    </div>
</div>

<?php include 'footer.php';?>

<script src="js/script.js"></script>
<script src="js/slideshow.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const cartButtons = document.querySelectorAll(".add-to-cart");

    cartButtons.forEach(button => {
        button.addEventListener("click", function () {
            const productId = this.dataset.id;
            const productName = this.dataset.name;
            const productPrice = this.dataset.price;
            const productImage = this.dataset.image;

            // Send AJAX request to add to cart
            fetch(`add_to_cart.php?id=${productId}&quantity=1&type=product`, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.totalItems !== undefined) {
                        // Update the modal
                        document.getElementById("modalImage").src = productImage;
                        document.getElementById("modalName").innerText = productName;
                        document.getElementById("modalPrice").innerText = `$${parseFloat(productPrice).toFixed(2)}`;

                        // Show the modal
                        const modal = document.getElementById("cartModal");
                        modal.style.display = "block";

                        // Update the cart count in the navbar
                        const cartCount = document.getElementById("cart-count");
                        if (cartCount) {
                            cartCount.innerText = data.totalItems;
                        }
                    } else {
                        alert('There was an issue adding the item to the cart.');
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                });
        });
    });

    // Modal close button
    const closeModal = document.querySelector(".modal .close");
    closeModal.addEventListener("click", function () {
        const modal = document.getElementById("cartModal");
        modal.style.display = "none";
    });
});

</script>
</body>
</html>
