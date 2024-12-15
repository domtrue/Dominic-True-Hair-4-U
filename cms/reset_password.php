<?php
// Include the database connection
include 'setup.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $newPassword = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

    // Validate the new password (example: minimum 8 characters)
    if (strlen($newPassword) < 8) {
        echo 'Password must be at least 8 characters long.';
        exit;
    }

    // Validate token
    $stmt = $conn->prepare('SELECT id FROM accounts WHERE reset_token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Token is valid, update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE accounts SET password = ?, reset_token = NULL WHERE reset_token = ?');
        $stmt->bind_param('ss', $hashedPassword, $token);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo 'Password has been reset successfully!';
        } else {
            echo 'Failed to reset the password. Please try again.';
        }
    } else {
        echo 'Invalid or expired token.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Your Password</h1>
    <form method="post" action="">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']); ?>">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</body>
</html>
