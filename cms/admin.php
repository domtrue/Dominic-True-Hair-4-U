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
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $firstName = "User";
    $accountId = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>
    <div class="content">
    <h2>Welcome, <?= $firstName ?>!</h2>
    <div class="dashboard">
</div>
</body>
</html>
