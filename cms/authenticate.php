<?php
session_start();
include 'setup.php';

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';

if ($stmt = $conn->prepare('SELECT id, password, first_name, last_name, email, phone FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $first_name, $last_name, $email, $phone);
        $stmt->fetch();

        if (password_verify($_POST['password'], $password)) {
            // Store the first name directly in session
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $first_name; // Store first name
            $_SESSION['id'] = $id;
            $_SESSION['user_details'] = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'account_id' => $id,
                'email' => $email,
                'phone' => $phone
            ];
            
            session_regenerate_id(true); // Regenerate session ID after setting variables

            // Debug after setting session variables
            echo '<pre>';
            print_r($_SESSION);
            echo '</pre>';

            if ($_POST['username'] !== "admin") {
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
