let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");

    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }

    // Hide all slides and remove active class from all dots
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    // Show the current slide and add active class to corresponding dot
    slides[slideIndex - 1].style.display = "block";
    slides[slideIndex - 1].classList.add('active');
    dots[slideIndex - 1].className += " active";
}

// Automatic slideshow (if needed)
setInterval(function() {
    plusSlides(1);
}, 5000); // Change slide every 5 seconds
