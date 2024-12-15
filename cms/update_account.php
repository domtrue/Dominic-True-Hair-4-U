<?php
include 'setup.php';
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['user_details']['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepare SQL statement to update the account
    $stmt = $conn->prepare('UPDATE accounts SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE id = ?');
    $stmt->bind_param('ssssi', $firstname, $lastname, $email, $phone, $id);

    if ($stmt->execute()) {
        // Update session details
        $_SESSION['user_details']['firstname'] = $firstname;
        $_SESSION['user_details']['lastname'] = $lastname;
        $_SESSION['user_details']['email'] = $email;
        $_SESSION['user_details']['phone'] = $phone;

        echo 'Account updated successfully!';
    } else {
        echo 'Failed to update account. Please try again.';
    }

    $stmt->close();
} else {
    echo 'Invalid request method.';
}
?>
