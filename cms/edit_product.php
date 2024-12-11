<?php
include 'setup.php';
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    try {
        // Prepare SQL query to fetch product details
        $sql = "SELECT * FROM products WHERE id = :productId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the product details
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception("Product not found");
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

// Below line should only execute if `$product` has valid data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $productId = $_POST['productId'];
        $productName = $_POST['productName'];
        $productDescription = $_POST['productDescription'];
        $productPrice = $_POST['productPrice'];
        $productImage = $_POST['productImage']; // Text field for image path

        // Prepare SQL query to update product
        $sql = "UPDATE products SET name = :productName, description = :productDescription, price = :productPrice, image = :productImage WHERE id = :productId";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productDescription', $productDescription);
        $stmt->bindParam(':productPrice', $productPrice);
        $stmt->bindParam(':productImage', $productImage);

        // Execute the query
        if ($stmt->execute()) {
            echo "Product updated successfully.";
        } else {
            throw new Exception("Error updating product.");
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="content">
    <h1>Edit Product</h1>
    <form method="post" action="edit_product.php">
    <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id']); ?>">
    
    <label for="productName">Product Name:</label>
    <input type="text" name="productName" value="<?php echo htmlspecialchars($product['name']); ?>" required>

    <label for="productDescription">Product Description:</label>
    <textarea name="productDescription" required><?php echo htmlspecialchars($product['description']); ?></textarea>

    <label for="productPrice">Product Price:</label>
    <input type="number" step="0.01" name="productPrice" value="<?php echo htmlspecialchars($product['price']); ?>" required>

    <label for="productImage">Product Image Path:</label>
    <input type="text" name="productImage" value="<?php echo htmlspecialchars($product['image']); ?>" required>

    <button type="submit">Update Product</button>
</form>

</div>
</body>
</html>
