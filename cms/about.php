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
            opacity: 0; /* Initially hidden */
            transition: opacity 1.5s ease-in-out; /* Smooth fade-in effect */
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
            background: linear-gradient(135deg, #1c1c1c, #282828);
            color: #fff;
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
                <h1 id="about-heading"><?php echo $heading; ?></h1>
                <p id="about-body"><?php echo $body_text; ?></p>
            </div>

            <div class="profile-image">
                <img src="img/<?php echo $image_path; ?>" alt="Profile Image">
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script>
        // Typewriter Effect for Heading
const heading = document.getElementById('about-heading');
const headingText = heading.textContent; // Use textContent here instead of innerText
        
heading.textContent = ''; // Clear the heading initially
let i = 0;

function typeWriter() {
    if (i < headingText.length) {
        heading.textContent += headingText.charAt(i); // Add each character
        i++;
        setTimeout(typeWriter, 100); // Adjust typing speed here
    } else {
        // Trigger fade-in effect for the body text after the typewriter finishes
        const bodyText = document.getElementById('about-body');
        bodyText.style.opacity = 1; // Make the text visible with the fade-in effect
    }
}

typeWriter();

        
    </script>
</body>
</html>
