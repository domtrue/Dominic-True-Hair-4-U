<?php
// Database connection parameters
include 'setup.php';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if verification code and user ID are provided
if (isset($_GET['code']) && isset($_GET['user_id'])) {
    $code = $_GET['code'];
    $user_id = $_GET['user_id'];

    // Prepare and execute the query to find the verification code
    $stmt = $pdo->prepare("SELECT * FROM verification_codes WHERE code = :code AND user_id = :user_id AND expires_at > NOW()");
    $stmt->execute(['code' => $code, 'user_id' => $user_id]);
    $verification = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($verification) {
        // Verification successful, update user verification status
        $updateStmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = :user_id");
        $updateStmt->execute(['user_id' => $user_id]);

        // Optionally, delete the verification code after successful verification
        $deleteStmt = $pdo->prepare("DELETE FROM verification_codes WHERE code = :code AND user_id = :user_id");
        $deleteStmt->execute(['code' => $code, 'user_id' => $user_id]);

        echo "Verification successful! Your account has been verified.";
    } else {
        echo "Invalid or expired verification code.";
    }
} else {
    echo "Missing verification code or user ID.";
}
?>
