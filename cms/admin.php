<?php
include 'setup.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['account_id'])) {
    $accountId = $_SESSION['user_details']['account_id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['first_name'], ENT_QUOTES);
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

// Fetch the business logo
$logoPath = 'path/to/default_logo.jpg'; // Default logo image
$logoStmt = $pdo->prepare("SELECT logo_path FROM business_logo ORDER BY id DESC LIMIT 1");
$logoStmt->execute();
$logoResult = $logoStmt->fetch(PDO::FETCH_ASSOC);
if ($logoResult && !empty($logoResult['logo_path'])) {
    $logoPath = $logoResult['logo_path'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
            <a href="products.php">Products</a>
            <a href="accounts.php">Accounts</a>
            <a href="invoices.php">Invoices</a>
        </div>
        <div class="search">
            <input type="text" placeholder="Search...">
            <i class="fas fa-search"></i>
        </div>
        <div class="profile">
            <span>Hello, <?= $firstName ?></span>
            <img src="<?= $profilePic ?>" alt="Profile Picture">
        </div>
    </nav>
    <div class="content">
    <h2>Welcome, <?= $firstName ?>!</h2>
    <div class="dashboard">
        <div class="card">
            <h3>Revenue</h3>
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="card">
            <h3>Site Visitors</h3>
            <canvas id="visitorsChart"></canvas>
        </div>
        <div class="card">
            <h3>Pending Orders</h3>
            <canvas id="ordersChart"></canvas>
        </div>
        <div class="card">
            <h3>Upcoming Appointments</h3>
            <canvas id="appointmentsChart"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr'],
            datasets: [{ label: 'Revenue', data: [1200, 1500, 800, 2000], backgroundColor: '#007bff' }]
        }
    });

    const visitorsCtx = document.getElementById('visitorsChart').getContext('2d');
    new Chart(visitorsCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{ label: 'Visitors', data: [500, 800, 750, 900], borderColor: '#4caf50' }]
        }
    });

    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Completed', 'Cancelled'],
            datasets: [{ data: [12, 30, 5], backgroundColor: ['#f39c12', '#2ecc71', '#e74c3c'] }]
        }
    });

    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Morning', 'Afternoon', 'Evening'],
            datasets: [{ data: [3, 5, 2], backgroundColor: ['#3498db', '#9b59b6', '#e67e22'] }]
        }
    });
</script>
</body>
</html>
