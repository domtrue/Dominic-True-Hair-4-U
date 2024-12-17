<?php
include 'setup.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_details'])) {
    header('Location: login.php');
    exit;
}

$user_details = $_SESSION['user_details'];
$role = $user_details['role'] ?? 'user'; // Default role is 'user'

// Include appropriate navbar based on role
if ($role === 'admin') {
    include 'admin_navbar.php';
} else {
    include 'customer_navbar.php';
}

// Fetch current user details from the database
$user_id = $user_details['account_id'];
$sql = "SELECT * FROM accounts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();

if (!$current_user) {
    echo "<p>Error: User details not found.</p>";
    exit;
}

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
    $stmt = $conn->prepare('SELECT id, role FROM accounts WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $role);
        $stmt->fetch();

        // Generate reset token and expiration
        $resetToken = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update database with reset token and expiration
        $stmt = $conn->prepare('UPDATE accounts SET reset_token = ?, reset_token_expiry = ? WHERE id = ?');
        $stmt->bind_param('ssi', $resetToken, $expires, $id);
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset_password'])) {
    $firstname = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
    $lastname = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);

    // Prepare the update statement
    $stmt = $conn->prepare('UPDATE accounts SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE id = ?');
    $stmt->bind_param('ssssi', $firstname, $lastname, $email, $phone, $user_details['account_id']);
    if ($stmt->execute()) {
        echo 'Account updated successfully!';
        // Update session data
        $_SESSION['user_details']['firstname'] = $firstname;
        $_SESSION['user_details']['lastname'] = $lastname;
        $_SESSION['user_details']['email'] = $email;
        $_SESSION['user_details']['phone'] = $phone;
    } else {
        echo 'Failed to update account.';
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="content">
        <?php if ($role === 'admin'): ?>
            <h1 class="page-title">Edit Accounts</h1>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->prepare('SELECT id, firstname, lastname, email FROM accounts ORDER BY id ASC');
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($row['firstname']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['lastname']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td><a class="action-link" href="edit_user.php?id=' . $row['id'] . '">Edit</a></td>';
                            echo '</tr>';
                        }

                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            
            <h1 class="page-heading">Edit Account Details</h2>
            <div class="form-container">
                <form method="post" action="">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" 
                           value="<?php echo htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES); ?>" required>

                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" 
                           value="<?php echo htmlspecialchars($_SESSION['user_details']['lastname'], ENT_QUOTES); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($_SESSION['user_details']['email'], ENT_QUOTES); ?>" required>

                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($_SESSION['user_details']['phone'], ENT_QUOTES); ?>" required>

                    <button type="submit">Update Account</button>
                </form>
                </div>
                <h1 class="page-heading">Update Your Account Information</h1>
    
            <div class="form-container">
                <form method="post" action="">
                    <label for="email">Enter your email for password reset:</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit" name="reset_password">Send Reset Link</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

