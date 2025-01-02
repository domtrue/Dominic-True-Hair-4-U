<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Success</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/appointment_success.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <div class="appointment-success-message">
            <h1>Your appointment request was received successfully.</h1>
            <p>Melissa will reach out shortly.</p>
            <a href="index.php" class="back-home-button">Back to Homepage</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

