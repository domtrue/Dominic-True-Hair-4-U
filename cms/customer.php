<?php
include 'setup.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['user_id'])) {
    $accountId = $_SESSION['user_details']['user_id'];
    $first_name = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $first_name = "User";
    $accountId = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/customer.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include 'customer_navbar.php'; ?>
    <div class="content">
        <h2>Welcome, <?php echo $firstName; ?>!</h2>
        </div>
    </div>
</body>
</html>
