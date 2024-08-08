<?php
// Include the database configuration file
include 'setup.php';

// Fetch all pages from the database
$sql = "SELECT * FROM pages";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
</head>
<body>
  <h1>Admin Panel</h1>
  <a href="add_page.php">Add New Page</a>
  <h2>Pages</h2>
  <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
      <li>
        <h3><?php echo $row['title1']; ?></h3>
        <a href="edit_page.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a href="delete_page.php?id=<?php echo $row['id']; ?>">Delete</a>
      </li>
    <?php endwhile; ?>
  </ul>
</body>
</html>
