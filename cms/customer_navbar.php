<?php
include 'setup.php';

// Check if user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['account_id'])) {
    $accountId = $_SESSION['user_details']['account_id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
    $lastName = htmlspecialchars($_SESSION['user_details']['lastname'], ENT_QUOTES); // Add lastname extraction
} else {
    $firstName = "User";
    $lastName = "";
    $accountId = null;
}

// Generate initials
if (isset($firstName[0]) && isset($lastName[0])) {
    $initials = strtoupper($firstName[0] . $lastName[0]);
} else {
    $initials = "U"; // Default to "U" if no valid initials
}

// Fetch logo path
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
    <title>Customer Navbar</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/customer.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navtop">
        <div class="logo">
            <a href="customer.php">
                <img src="<?= $logoPath ?>" alt="Business Logo">
            </a>
        </div>
        <div class="links">
            <a href="orders.php">My Orders</a>
            <a href="appointments.php">My Appointments</a>
            <a href="edit_account.php">My Account</a>
        </div>
        <div class="profile" id="profileMenu">
            <div class="profile-circle"><?= $initials ?></div>
            <span><?= $firstName ?></span>
            <!-- Dropdown -->
            <div class="profile-dropdown" id="profileDropdown">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <script>
        // Toggle dropdown visibility on click
        const profileMenu = document.getElementById('profileMenu');
        const profileDropdown = document.getElementById('profileDropdown');

        profileMenu.addEventListener('click', (event) => {
            // Prevent closing dropdown when clicking inside it
            event.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking anywhere else
        window.addEventListener('click', () => {
            profileDropdown.classList.remove('show');
        });

        // Prevent closing dropdown when clicking inside it
        profileDropdown.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    </script>
</body>
</html>

