var slideIndex = 0;
var slides = document.getElementsByClassName("slide");
var totalSlides = slides.length;
var slideWidth = slides[0].clientWidth; // Assuming all slides have the same width
var slideInterval;

// Start slideshow when page loads
document.addEventListener("DOMContentLoaded", function() {
    showSlides(); // Show initial slide
    startSlideShow(); // Start automatic slideshow
});

// Next/previous controls with sliding animation
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n - 1); // Adjust index to 0-based
}

// Show slides function with sliding animation
function showSlides() {
    var i;

    if (slideIndex >= totalSlides) { slideIndex = 0; }    
    if (slideIndex < 0) { slideIndex = totalSlides - 1; }

    for (i = 0; i < totalSlides; i++) {
        slides[i].style.display = "none";  
    }

    slides[slideIndex].style.display = "block";
}

// Automatic slideshow
function startSlideShow() {
    slideInterval = setInterval(function() {
        plusSlides(1); // Advance to the next slide
    }, 3000); // Change image every 3 seconds (3000 milliseconds)
}

// Pause slideshow on user interaction
document.querySelectorAll('.prev, .next, .dot').forEach(function(elem) {
    elem.addEventListener('click', function() {
        clearInterval(slideInterval);
        setTimeout(startSlideShow, 3000); // Restart after 3 seconds of inactivity
    });
});

// Swipe left/right for mobile devices
var touchstartX = 0;
var touchendX = 0;

document.addEventListener('touchstart', function(event) {
    touchstartX = event.changedTouches[0].screenX;
}, false);

document.addEventListener('touchend', function(event) {
    touchendX = event.changedTouches[0].screenX;
    handleGesture();
}, false);

function handleGesture() {
    if (touchendX < touchstartX) {
        plusSlides(1); // Swipe left
    } else if (touchendX > touchstartX) {
        plusSlides(-1); // Swipe right
    }
}
