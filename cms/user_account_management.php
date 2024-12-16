<?php
include 'setup.php';
session_start();

if (isset($_SESSION['user_details']['role']) && $_SESSION['user_details']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

include 'admin_navbar.php';

// Fetch all customers
$sql = "SELECT id, firstname, lastname, email FROM accounts WHERE role = 'customer' ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customer Accounts</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<div class="content">
<header class="content-header">
    <h1>Manage User Accounts</h1>
    </header>
    <table class="dashboard-table">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
