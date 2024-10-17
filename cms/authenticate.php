<?php
session_start();
// Database connection details
include 'setup.php';
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Check if data from the login form was submitted
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

// Check if redirect parameter is set
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';

// Prepare SQL statement to prevent SQL injection
if ($stmt = $conn->prepare('SELECT id, password, first_name, last_name, email, phone FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    // Check if the account exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $first_name, $last_name, $email, $phone);
        $stmt->fetch();
        // Verify password
        if (password_verify($_POST['password'], $password)) {
            // Success! Start the session
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;

            if ($_POST['username'] !== "admin") {
                // Store user details in session for later use
                $_SESSION['user_details'] = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                ];
                header('Location: checkout.php');
            } else {
                header('Location: admin.php');
            }
            exit();
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {
        echo 'Incorrect username and/or password!';
    }
    $stmt->close();
}
?>



