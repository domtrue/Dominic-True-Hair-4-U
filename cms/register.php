<?php
require 'vendor/autoload.php'; // For PHPMailer or any other libraries

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'setup.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

if (empty($email) && empty($phone)) {
    die('Email or phone required');
}

if (empty($password)) {
    die('Password required');
}

// Check if email or phone already exists
$sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email, $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die('Email or phone already registered');
}

// Encrypt the password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Insert the user into the database
$sql = "INSERT INTO users (email, phone, password_hash) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $email, $phone, $password_hash);
$stmt->execute();

// Send verification email
$last_id = $stmt->insert_id;
$verification_code = rand(100000, 999999);
$expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

$sql = "INSERT INTO verification_codes (user_id, code, expires_at) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $last_id, $verification_code, $expires_at);
$stmt->execute();

// Use PHPMailer to send the email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your_email@example.com';
    $mail->Password = 'your_password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Email Verification';
    $mail->Body    = 'Your verification code is ' . $verification_code;

    $mail->send();
    echo 'Verification code has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

$conn->close();
?>
