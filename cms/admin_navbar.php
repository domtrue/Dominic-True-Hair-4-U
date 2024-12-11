<?php
include 'setup.php';
// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['account_id'])) {
    $accountId = $_SESSION['user_details']['account_id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $firstName = "User";
    $accountId = null;
}
// Initialize $profilePic variable
$profilePic = 'path/to/default_image.jpg'; // Set a default profile picture
// Only attempt to fetch the profile image if $accountId is set
if ($accountId) {
    // Fetch the admin's profile image from the database
    $query = "SELECT image_path FROM admin_images WHERE account_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$accountId]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    // Update the profile picture if an image path is found
    if ($image && !empty($image['image_path'])) {
        $profilePic = $image['image_path'];
    }
}

$logoPath = 'path/to/default_logo.jpg'; // Default logo image
$logoStmt = $pdo->prepare("SELECT logo_path FROM business_logo WHERE id = :id");
$logoStmt->execute(['id' => 2]);
$logoResult = $logoStmt->fetch(PDO::FETCH_ASSOC);

if ($logoResult && !empty($logoResult['logo_path'])) {
    $logoPath = $logoResult['logo_path'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Navbar</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<nav class="navtop">
    <div class="logo">
        <img src="<?= $logoPath ?>" alt="Business Logo">
    </div>
    <div class="links">
        <a href="orders.php">Orders</a>
        <a href="appointments.php">Appointments</a>
        <a href="products.php">Inventory Management</a>
        <a href="user_accounts.php">User Account Management</a>
    </div>
    <div class="profile">
        <img src="<?= $profilePic ?>" alt="Profile Picture">
        <span><?= $firstName ?></span>
    </div>
</nav>
</body>
</html>