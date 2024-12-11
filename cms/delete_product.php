<?php
include 'setup.php';
session_start();

// Check if the product ID is set in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = $_GET['id'];

    try {
        // Prepare SQL statement to delete the product
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to manage products page after deletion
        header('Location: products.php');
        exit;

    } catch (Exception $e) {
        // Error handling
        $error = $e->getMessage();
    }
} else {
    // Redirect to manage products if no valid product ID is given
    header('Location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
<div class="content">
    <h1>Delete Product</h1>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
