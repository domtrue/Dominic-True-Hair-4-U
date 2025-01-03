<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
<?php
session_start();
include 'header.php';
include 'setup.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch content
$sql = "SELECT content_type, content FROM hair_services ORDER BY id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="content">'; // Background class
    echo '<div class="info-box">';
    echo '<div class="text-content">';
    $list_started = false;

    while ($row = $result->fetch_assoc()) {
        if ($row['content_type'] == 'paragraph') {
            echo '<p>' . htmlspecialchars($row['content']) . '</p>';
        } elseif ($row['content_type'] == 'list_item') {
            if (!$list_started) {
                echo '<ul>';
                $list_started = true;
            }
            echo '<li>' . htmlspecialchars($row['content']) . '</li>';
        } elseif ($row['content_type'] == 'image') {
            echo '<div class="service-image">';
            echo '<img src="' . htmlspecialchars($row['content']) . '" alt="Service Image">';
            echo '</div>';
            echo '</div>'; // Close text-content div
        }
    }

    if ($list_started) {
        echo '</ul>';
    }
    echo '</div>'; // Close info-box div
    echo '</div>'; // Close background-box div
} else {
    echo '<div class="background-box"><p>No services available at the moment.</p></div>';
}

$conn->close();
include 'footer.php';
?>


    <script src="script.js"></script>
</body>
</html>
