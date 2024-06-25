<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles can be added here if needed */
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <!-- Slideshow container -->
        <div class="slideshow-container">
            <!-- Slides -->
            <div class="slide">
                <img src="img/slideshow-1.jpg" alt="Slide 1" style="width:100%">
            </div>
            <div class="slide">
                <img src="img/slideshow-2.jpg" alt="Slide 2" style="width:100%">
            </div>
            <div class="slide">
                <img src="img/slideshow-3.jpg" alt="Slide 3" style="width:100%">
            </div>
            <div class="slide">
                <img src="img/slideshow-4.jpg" alt="Slide 4" style="width:100%">
            </div>

            <!-- Prev & Next buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <!-- Dots -->
        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <!-- Adjust as per your number of slides -->
        </div>
    </div>

    <style>
                .content {
    background: linear-gradient(135deg, #8e2de2, #4a00e0);
    height: calc(100vh - 60px); /* Adjust the height as per your header's height */
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    text-align: center;
}

/* Slideshow container */
.slideshow-container {
    position: relative;
    width: 100%;
    max-width: 1000px; /* Adjust as per your design */
    overflow: hidden;
    margin: auto;
}

/* Individual slides */
.mySlides {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    display: none;
}

/* Active slide */
.mySlides.active {
    display: block;
}

/* Prev & Next buttons */
.prev, .next {
    position: absolute;
    top: 50%;
    width: auto;
    margin-top: -22px;
    padding: 16px;
    color: white;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    background-color: rgba(0, 0, 0, 0.5);
}

.prev {
    left: 0;
}

.next {
    right: 0;
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

.active, .dot:hover {
    background-color: #717171;
}
      
        </style>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
    <script src="slideshow.js"></script>
</body>
</html>

