<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer classes
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

include 'setup.php';

// Get form data
$appointment_id = $_POST['appointment_id'];
$to = $_POST['to'];  // The recipient's email address
$subject = $_POST['subject'];  // Subject of the email
$body = $_POST['body'];  // Body of the email

// Add the email footer with company logo placeholder
$footer = "\n\nKind regards,\nMelissa True\nSenior Stylist\nHair 4 U Bulls\n";
$footer .= '<img src="cid:company_logo" alt="Hair 4 U Bulls Logo" style="max-width: 200px;">';  // Inline logo image

// Create the email content
$email_body = nl2br($body . $footer);

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Set the SMTP server (example: smtp.gmail.com)
    $mail->SMTPAuth = true;
    $mail->Username = 'domtrue.dt@gmail.com';  // SMTP username
    $mail->Password = 'eagx szua bnbr abwf';  // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('domtrue.dt@gmail.com', 'Hair 4 U Bulls');  // Sender's email
    $mail->addAddress($to);  // Add the recipient's email address

    // Attach the company logo
    $mail->addEmbeddedImage('../img/logo-email.png', 'company_logo');  // Make sure to update with the correct path to the logo image

    // Content settings
    $mail->isHTML(true);  // Set the email format to HTML
    $mail->Subject = $subject;  // Subject of the email
    $mail->Body = $email_body;  // The content of the email

    // Send the email
    $mail->send();

    // Redirect after sending the email successfully
    header("Location: response_success.php");
    exit;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
