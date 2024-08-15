<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
// Database connection details
include 'setup.php';

// Retrieve additional account information
$stmt = $conn->prepare('SELECT password, email FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body class="loggedin">
    <nav class="navtop">
        <div>
            <h1>Website Title</h1>
            <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
      <div class="content">
        <h2>Profile Page</h2>
        <div>
            <p>Your account details are below:</p>
            <table>
                <tr>
                    <td>Username:</td>
                    <td><?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><?= htmlspecialchars($password, ENT_QUOTES) ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?= htmlspecialchars($email, ENT_QUOTES) ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>