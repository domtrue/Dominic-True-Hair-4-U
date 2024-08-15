<?php
session_start();
// Database connection details
include 'setup.php';

 // Check if data from the login form was submitted
 if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}


// Prepare SQL statement to prevent SQL injection
if ($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    // Check if the account exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Verify password
        if (password_verify($_POST['password'], $password)) {
            // Success! Start the session
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: admin.php');
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {
        echo 'Incorrect username and/or password!';
    }
    $stmt->close();
}
?>



