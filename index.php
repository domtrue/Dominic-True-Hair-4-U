<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDPGothic&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">

    <style>
        /* General styles */
        .content {
            background: linear-gradient(135deg, #8e2de2, #4a00e0);
            height: auto; /* Adjusted to allow content to dictate height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        @keyframes fadeInFromLeft {
            0% {
                opacity: 0;
                transform: translateX(-50px); /* Start from left */
            }
            100% {
                opacity: 1;
                transform: translateX(0); /* End at normal position */
            }
        }

        /* Heading styles */
        .heading {
            font-family: "BIZ UDPGothic", sans-serif;
            font-weight: 400;
            font-style: normal;
            color: #fff; /* White color for visibility */
            font-size: 2.3em; /* Adjust size as needed */
            margin: 0; /* Adjusted margin to remove extra space */
            padding: 0; /* Ensure no padding affects spacing */
            animation: fadeInFromLeft 1s ease-out; /* Add animation */
        }

        /* Blurb section */
        .blurb {
            max-width: 1000px; /* Match carousel width */
            width: 90%; /* Make sure it scales down */
            padding: 10px; /* Reduced padding to bring text closer */
            color: #fff; /* Ensure text is visible */
            background: transparent; /* Make background transparent */
            font-family: "Tajawal", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 1.2em; /* Adjust font size as needed */
            line-height: 1.5; /* Adjust line height for readability */
            margin: 0; /* Remove margin to ensure no extra spacing */
            animation: fadeInFromLeft 1s ease-out; /* Add animation */
        }

        /* Define the animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Slideshow container styling */
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100vh; /* Make height of the container equal to the viewport height */
            overflow: hidden;
            margin: 0 auto 60px; /* Adjust margin as needed */
        }

        /* Individual slides */
        .mySlides {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            opacity: 0; /* Start as hidden */
        }

        /* Ensure images within slides are responsive */
        .mySlides img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Fit images within container without cropping */
        }

        /* Apply fade-in animation when slide becomes active */
        .mySlides.active {
            display: block;
            opacity: 1; /* Ensure opacity is set to 1 */
            animation: fadeIn 1s ease-out;
        }

        /* Prev & Next buttons */
        .prev, .next {
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10; /* Ensure buttons are on top */
        }

        .prev {
            left: 0;
            border-radius: 3px 0 0 3px; /* Adjust border radius */
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px; /* Adjust border radius */
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Dots */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .dot.active, .dot:hover {
            background-color: #717171;
        }

        /* Responsive adjustments */
        @media only screen and (max-width: 600px) {
            .slideshow-container {
                max-width: 100%;
                max-height: 50vh; /* Adjust height for smaller screens */
                aspect-ratio: auto; /* Let the height adjust automatically */
            }
            
            .prev, .next {
                padding: 12px; /* Smaller buttons on smaller screens */
                font-size: 16px;
            }
        }

    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <!-- Heading -->
        <div class="heading">Welcome to Hair 4 U</div>

        <!-- Blurb section -->
        <div class="blurb">
            <?php
            // Database connection
            $servername = "localhost";
            $username = "sec_user";
            $password = "greenChair153";
            $dbname = "hair4u";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch blurb text from the database
            $sql = "SELECT text_description FROM homepage_text ORDER BY id ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<p>' . htmlspecialchars($row['text_description']) . '</p>';
                }
            } else {
                echo "<p>No blurb available</p>";
            }
            $conn->close();
            ?>
        </div>

        <!-- Slideshow container -->
        <div class="slideshow-container">
            <?php
            // Database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch images from the database
            $sql = "SELECT * FROM homepage_images";
            $result = $conn->query($sql);

            $numSlides = $result->num_rows; // Save the number of slides

            if ($numSlides > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="mySlides">
                            <img src="' . htmlspecialchars($row['image_url']) . '" alt="Slide ' . $i . '" style="width:100%">
                          </div>';
                    $i++;
                }
            } else {
                echo "No images found";
            }
            $conn->close();
            ?>

            <!-- Prev & Next buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <!-- Dots -->
        <div style="text-align:center">
            <?php
            // Display dots for each slide
            for ($j = 1; $j <= $numSlides; $j++) {
                echo '<span class="dot" onclick="currentSlide(' . $j . ')"></span>';
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
    <script src="slideshow.js"></script>
</body>
</html>
