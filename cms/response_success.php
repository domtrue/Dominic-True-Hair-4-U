<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response Sent</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="content">
        <h1>Your response was sent successfully</h1>
        <a href="appointments.php" class="btn">Return to appointment requests</a>
    </div>
</body>
</html>
