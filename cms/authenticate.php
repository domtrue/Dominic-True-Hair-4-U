<?php
session_start();
include 'setup.php';

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Ensure both username and password are provided
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

// Handle redirect (if any)
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';

if ($stmt = $conn->prepare('SELECT id, password, firstname, lastname, email, phone, role FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password, $first_name, $last_name, $email, $phone, $role);
        $stmt->fetch();

        // Verify the provided password
        if (password_verify($_POST['password'], $password)) {
            // Store user details in the session
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;

            // Set user details ensuring no empty values
            $_SESSION['user_details'] = [
                'firstname' => $first_name ?: '', // Use empty string if NULL or empty
                'lastname' => $last_name ?: '',
                'account_id' => $user_id,
                'email' => $email,
                'phone' => $phone ?: '',
            ];
            
            // Regenerate the session ID for security
            session_regenerate_id(true);

            // Debugging session variables (remove in production)
            echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';

            // Redirect based on role
            if ($role === "admin") {
                header('Location: admin.php');
            } else {
                header('Location: customer.php');
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
