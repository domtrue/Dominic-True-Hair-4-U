<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database configuration
include 'setup.php';

// Determine the page ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 1; // Default to 1 if no ID is provided

// Fetch the page details from the database
$sql = "SELECT * FROM pages WHERE id=$id";
$result = $conn->query($sql);

// Initialize variables for page content
$title1 = $text1 = $text2 = ''; // Initialize all variables to empty strings

if ($result->num_rows > 0) {
    $page = $result->fetch_assoc();
    
    // Assign the fields to variables
    $title1 = $page['title1'];
    $text1 = $page['text1'];
    $text2 = $page['text2'];
    // Add other fields if needed

    // Prepare for slideshow
    $basePath = 'img/';

    // Fetch images for the slideshow
    $slideshowSql = "SELECT * FROM slideshow";
    $slideshowResult = $conn->query($slideshowSql);

    if (!$slideshowResult) {
        die("Query failed: " . $conn->error);
    }

    $numSlides = $slideshowResult->num_rows; // Save the number of slides

    // Close the database connection after all queries
    $conn->close();
} else {
    $title1 = "Page not found";
    $text1 = "";
    $text2 = "";
    $numSlides = 0; // No slides to show
    $slideshowResult = null; // No result to fetch
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"href="https://fonts.googleapis.com/css2?family=BIZ+UDPGothic&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" >


</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <!-- Heading -->
        <div class="heading">
            <?php echo $title1; ?>
        </div>

        <!-- Blurb section -->
        <div class="blurb">
            <?php
            // Display the paragraphs
            echo '<p>' . $text1 . '</p>';
            echo '<p>' . $text2 . '</p>';
            ?>
        </div>

        <!-- Slideshow container -->
        <?php if ($numSlides > 0) { ?>
            <div class="slideshow-container">
                <?php
                if ($numSlides > 0) {
                    $i = 1;
                    while ($row = $slideshowResult->fetch_assoc()) {
                        echo '<div class="mySlides">
                                <img src="' . $basePath . htmlspecialchars($row['image_url']) . '" alt="Slide ' . $i . '" style="width:100%">
                              </div>';
                        $i++;
                    }
                } else {
                    echo "No images found";
                }
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
        <?php } ?>

    </div>

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
    <script src="js/slideshow.js"></script>
</body>
</html>
