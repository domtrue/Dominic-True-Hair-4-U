
<?php
// Include the database configuration file
include 'setup.php';
// Fetch the page details if the ID is set
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM pages WHERE id=$id";
  $result = $conn->query($sql);
  $page = $result->fetch_assoc();
}
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $id = $_POST['id'];
  $title1 = $_POST['title1'];
  $text1 = $_POST['text1'];
  $image1 = $_POST['image1'];
  $title2 = $_POST['title2'];
  $text2 = $_POST['text2'];
  $image2 = $_POST['image2'];
  $title3 = $_POST['title3'];
  $text3 = $_POST['text3'];
  $image3 = $_POST['image3'];
  // Update the page in the database
  $sql = "UPDATE pages SET title1='$title1', text1='$text1', image1='$image1',
          title2='$title2', text2='$text2', image2='$image2', title3='$title3', text3='$text3', image3='$image3' WHERE id=$id";
  if ($conn->query($sql) === TRUE) {
    echo "Page updated successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Page</title>
</head>
<body>
  <h1>Edit Page</h1>
  <form method="post" action="edit_page.php">
    <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
    <h3>Section 1</h3>
    <input type="text" name="title1" value="<?php echo $page['title1']; ?>"><br>
    <textarea name="text1"><?php echo $page['text1']; ?></textarea><br>
    <input type="text" name="image1" value="<?php echo $page['image1']; ?>"><br>
    <h3>Section 2</h3>
    <input type="text" name="title2" value="<?php echo $page['title2']; ?>"><br>
    <textarea name="text2"><?php echo $page['text2']; ?></textarea><br>
    <input type="text" name="image2" value="<?php echo $page['image2']; ?>"><br>
    <h3>Section 3</h3>
    <input type="text" name="title3" value="<?php echo $page['title3']; ?>"><br>
    <textarea name="text3"><?php echo $page['text3']; ?></textarea><br>
    <input type="text" name="image3" value="<?php echo $page['image3']; ?>"><br>
    <button type="submit">Update Page</button>
  </form>
</body>
</html>

