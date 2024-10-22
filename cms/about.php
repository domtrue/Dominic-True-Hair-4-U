<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Container for content */
        .about-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Left section: Text */
        .about-text {
            flex: 1;
            margin-right: 20px;
        }

        .about-text h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #fff;
        }

        .about-text p {
            font-family: 'Roboto', sans-serif;
            font-size: 1.2rem;
            line-height: 1.6;
            color: #ddd;
        }

        /* Right section: Circular Profile Image */
        .profile-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


body {
    margin: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Ensures body takes the full viewport height */
}

.content {
    flex: 1; /* Fill the remaining space */
    display: flex;
    flex-direction: column; /* Stack heading and text vertically */
    justify-content: flex-start; /* Align everything towards the top */
    align-items: flex-start; /* Align to the left */
    padding: 10px; /* Optional padding adjustment */
    gap: 10px; /* Space between heading and text */
}

.text-container {
    width: 100%; /* Ensure it fills available space */
    margin-top: 0; /* Remove top margin */
}

h1, p {
    margin: 0; /* Eliminate default margins */
    padding: 0;
}
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <div class="about-container">
            <?php
            // Database connection
            include 'setup.php';

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to get about page info
            $sql = "SELECT heading, body_text, image_path FROM about_page WHERE id = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Fetch data
                $row = $result->fetch_assoc();
                $heading = $row['heading'];
                $body_text = $row['body_text'];
                $image_path = $row['image_path'];
            }

            $conn->close();
            ?>

            <div class="about-text">
                <h1><?php echo $heading; ?></h1>
                <p><?php echo $body_text; ?></p>
            </div>

            <div class="profile-image">
                <img src="img/<?php echo $image_path; ?>" alt="Profile Image">
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>
