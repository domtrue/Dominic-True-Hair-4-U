var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

// Automatic slideshow
var slideInterval;

function startSlideShow() {
  slideInterval = setInterval(function() {
    plusSlides(1); // Advance to the next slide
  }, 10000); // Change image every 10 seconds (10000 milliseconds)
}

function stopSlideShow() {
  clearInterval(slideInterval);
}

// Show slides function
function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  
  // Wrap around if reaching end or beginning of slides
  if (n > slides.length) { slideIndex = 1 }
  if (n < 1) { slideIndex = slides.length }

  // Hide all slides and deactivate all dots
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  // Display the current slide and activate its corresponding dot
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
}

// Start the slideshow when the page loads
document.addEventListener("DOMContentLoaded", function() {
  startSlideShow();
});

// Stop the slideshow when the user interacts with controls
document.querySelectorAll(".prev, .next, .dot").forEach(function(element) {
  element.addEventListener("click", function() {
    stopSlideShow();
  });
});

// Resume slideshow when user stops interacting with controls
document.addEventListener("mouseleave", function() {
  startSlideShow();
});

// Keyboard navigation with arrow keys
document.addEventListener("keydown", function(event) {
  if (event.key === "ArrowLeft") {
    plusSlides(-1);
    stopSlideShow();
  } else if (event.key === "ArrowRight") {
    plusSlides(1);
    stopSlideShow();
  }
});
