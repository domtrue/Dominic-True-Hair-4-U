<?php
include 'setup.php';
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Fetch POST data
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = trim($_POST['price']);
        $image_path = trim($_POST['image_path']);

        // Validate input
        if (empty($name) || empty($description) || empty($price) || empty($image_path)) {
            throw new Exception('All fields are required.');
        }

        // Prepare SQL statement to insert product
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price, image) VALUES (:name, :description, :price, :image)');
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image_path
        ]);

        // Redirect to manage products page after successful insertion
        header('Location: products.php');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="content">
    <h1>Add Product</h1>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="add_product.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="image_path">Image Path:</label>
        <input type="text" id="image_path" name="image_path" placeholder="e.g., products/de_lorenzo/instant/rejuven8_shampoo_375ml.png" required>

        <button type="submit">Add Product</button>
    </form>
</div>
</body>
</html>
