<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer classes
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

// Include the database connection setup file
include 'setup.php';

// Check if all required data is submitted
if (!isset($_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['password'], $_POST['email'], $_POST['phone'])) {
    exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty ($_POST['firstname']) || empty ($_POST ['lastname']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['phone'])) {
    // One or more values are empty.
    exit('Please complete the registration form');
}

// Validate email address
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Email is not valid!');
}

// Validate username
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

// Validate password length
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    exit('Password must be between 5 and 20 characters long!');
}

// We need to check if the account with that username exists.
if ($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

        // Insert the new account data into the database
    if ($stmt = $conn->prepare('INSERT INTO accounts (firstname, lastname, username, password, email, phone, activation_code) VALUES (?, ?, ?, ?, ?, ?, ?)')) {
        // Hash the password and generate activation code
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $uniqid = uniqid();
        $stmt->bind_param(
            'sssssss', 
            $_POST['firstname'], 
            $_POST['lastname'], 
            $_POST['username'], 
            $password, 
            $_POST['email'], 
            $_POST['phone'], 
            $uniqid
        );
        $stmt->execute();

            // Define activation link
            // Define activation link
            $activate_link = 'http://localhost/hair4u/cms/activate.php?email=' . urlencode($_POST['email']) . '&code=' . urlencode($uniqid);


            // Initialize PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'domtrue.dt@gmail.com';
                $mail->Password   = 'eagx szua bnbr abwf'; // Use app password if 2FA is enabled
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('domtrue.dt@gmail.com', 'Hair 4 U');
                $mail->addAddress($_POST['email']);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Activation Required';
                $mail->Body    = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
                $mail->AltBody = 'Please click the following link to activate your account: ' . $activate_link;

                $mail->send();
                echo 'Please check your email to activate your account!';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // Something is wrong with the SQL statement
            echo 'Could not prepare statement!';
        }
    }
 else {
    // Something is wrong with the SQL statement
    echo 'Could not prepare statement!';
}
?>
