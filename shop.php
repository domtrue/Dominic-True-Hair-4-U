<!DOCTYPE html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U</title>
    <link rel="stylesheet" href="style.css">
    <style>
/* Ensure all items in a row stretch to the same height */

.content {
  display: flex;
  flex-wrap: wrap; /* Allow items to wrap to the next row if necessary */
  justify-content: space-around; /* Adjust alignment as needed */
  align-items: flex-start; /* Align items at the top */
}

.responsive {
  width: 25%; /* Adjust width as needed for four items per row */
  padding: 10px; /* Adjust padding as needed */
  box-sizing: border-box; /* Ensure padding is included in width calculation */
}

 @media only screen and (max-width: 875px) {
  .responsive {
    width: 49.99999%;
    margin: 6px 0;
  }
}

@media only screen and (max-width: 500px) {
  .responsive {
    width: 100%;
  }
} 


/* Gallery style */
.gallery {
  border: 1px solid #ccc; /* Thin border around each gallery item */
  padding: 15px; /* Padding inside each gallery item */
  text-align: center; /* Center align text */
  height: 100%; /* Ensure each gallery item takes full height */
}

/* Image style */
.gallery img {
  width: 100%; /* Make image responsive */
  height: auto; /* Maintain aspect ratio */
}

/* Description style */
.desc {
  margin-top: 10px; /* Margin at the top of each description */
  overflow: hidden; /* Hide any overflow beyond the specified height */
  text-overflow: ellipsis; /* Show ellipsis (...) for overflowing text */
  display: -webkit-box; /* For iOS and Safari */
  -webkit-line-clamp: 3; /* Limit number of text lines to 3 */
  -webkit-box-orient: vertical; /* Vertical layout */
}


  </style>
</head>
<body>
<?php include 'header.php';?>
    <div class="content">
    <div class="responsive">
    <div class="gallery">
    <a target="_blank" href="img\Rejuven8Shampoo375mL.jpg">
      <img src="img\Rejuven8Shampoo375mL.jpg" alt="Rejuven8Shampoo">
    </a>
    <div class="desc">DE LORENZO INSTANT REJUVEN8 SHAMPOO 375ML</div>
  </div>
</div>

<div class="responsive">
  <div class="gallery">
    <a target="_blank" href="img\Rejuven8Conditioner375mL.jpg">
      <img src="img\Rejuven8Conditioner375mL.jpg" alt="Rejuven8Conditioner">
    </a>
    <div class="desc">DE LORENZO INSTANT REJUVEN8 CONDITIONER 375ML</div>
  </div>
</div>

<div class="responsive">
  <div class="gallery">
    <a target="_blank" href="img\Rejuven8TheEnds120mL.jpg">
      <img src="img\Rejuven8TheEnds120mL.jpg" alt="Rejuven8TheEnds">
    </a>
    <div class="desc">DE LORENZO INSTANT REJUVEN8 THE ENDS 120ML</div>
  </div>
</div>

<div class="responsive">
  <div class="gallery">
    <a target="_blank" href="img\Rejuven8Treatment150g.jpg">
      <img src="img\Rejuven8Treatment150g.jpg" alt="Rejuven8Treatment">
    </a>
    <div class="desc">DE LORENZO INSTANT REJUVEN8 TREATMENT 150G</div>
  </div>
</div>

<div class="clearfix"></div>

    </div>

    <style>
        .content {
            background: linear-gradient(135deg, #8e2de2, #4a00e0);
            height: calc(100vh - 60px); 
        }

    </style>

<?php include 'footer.php';?>
    <script src="script.js"></script>
</body>
</html>