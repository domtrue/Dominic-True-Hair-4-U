<?php
   // Include the database configuration file
   include 'setup.php';

   // Check if the form has been submitted
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     // Get form data
     $title1 = $_POST['title1'];
     $text1 = $_POST['text1'];
     $image1 = $_POST['image1'];
     $title2 = $_POST['title2'];
     $text2 = $_POST['text2'];
     $image2 = $_POST['image2'];
     $title3 = $_POST['title3'];
     $text3 = $_POST['text3'];
     $image3 = $_POST['image3'];

     // Insert form data into the database
     $sql = "INSERT INTO pages (title1, text1, image1, title2, text2, image2, title3, text3, image3)
             VALUES ('$title1', '$text1', '$image1', '$title2', '$text2', '$image2', '$title3', '$text3', '$image3')";

     if ($conn->query($sql) === TRUE) {
       echo "New page created successfully";
     } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
     }
   }
   ?>

   <!DOCTYPE html>
   <html>
   <head>
     <title>Add Page</title>
   </head>
   <body>
     <h1>Add New Page</h1>
     <form method="post" action="add_page.php">
       <h3>Section 1</h3>
       <input type="text" name="title1" placeholder="Title 1"><br>
       <textarea name="text1" placeholder="Text 1"></textarea><br>
       <input type="text" name="image1" placeholder="Image URL 1"><br>
       <h3>Section 2</h3>
       <input type="text" name="title2" placeholder="Title 2"><br>
       <textarea name="text2" placeholder="Text 2"></textarea><br>
       <input type="text" name="image2" placeholder="Image URL 2"><br>
       <h3>Section 3</h3>
       <input type="text" name="title3" placeholder="Title 3"><br>
       <textarea name="text3" placeholder="Text 3"></textarea><br>
       <input type="text" name="image3" placeholder="Image URL 3"><br>
       <button type="submit">Add Page</button>
     </form>
   </body>
   </html>
