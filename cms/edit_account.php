<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

// Include the database connection
include 'setup.php';

// Function to send reset password email
function sendPasswordResetEmail($email, $resetToken) {
    $resetLink = 'http://localhost/hair4u/cms/reset_password.php?token=' . urlencode($resetToken);
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'domtrue.dt@gmail.com'; // Your email
        $mail->Password   = 'eagx szua bnbr abwf';  // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('domtrue.dt@gmail.com', 'Hair 4 U');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = '<p>Click the following link to reset your password: <a href="' . $resetLink . '">' . $resetLink . '</a></p>';
        $mail->AltBody = 'Click the following link to reset your password: ' . $resetLink;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error: {$mail->ErrorInfo}";
    }
}

// Check if reset password is requested
if (isset($_POST['reset_password'])) {
    $email = $_POST['email']; // Get email from form

    // Verify email exists in the database
    $stmt = $conn->prepare('SELECT id FROM accounts WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate reset token and expiration
        $resetToken = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database with reset token and expiration
        $stmt = $conn->prepare('UPDATE accounts SET reset_token = ?, reset_token_expiry = ? WHERE email = ?');
        $stmt->bind_param('sss', $resetToken, $expires, $email);
        if ($stmt->execute()) {
            // Send the reset email
            $result = sendPasswordResetEmail($email, $resetToken);

            if ($result === true) {
                echo 'Password reset link sent! Please check your email.';
            } else {
                echo $result; // Display error if email fails
            }
        } else {
            echo 'Failed to update reset token.';
        }
    } else {
        echo 'Email not found!';
    }
}

// Add a simple form for admin or end-user
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
</head>
<body>
    <h1>Edit Account</h1>
    <form method="post" action="">
        <label for="email">Enter your email for password reset:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit" name="reset_password">Send Reset Link</button>
    </form>
</body>
</html>
