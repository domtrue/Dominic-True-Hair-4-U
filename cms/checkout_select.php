<?php
session_start(); // Start the session
print_r($_SESSION['loggedin']);
// Ensure the user is logged in
if (isset($_SESSION['loggedin'])) {
    header('Location: checkout.php');
    exit();
}
die();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or sign up</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap');
    body {
    background: #f4f4f4; /* Light grey background for the entire page */
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 800px;
    background: #fff; /* White background for the box */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 2rem;
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1; /* Ensure container is above other elements */
}

.checkout-options {
    display: flex;
    flex-direction: column;
    gap: 1rem; /* Space between buttons */
}

button {
    background-color: #4a148c; /* Purple buttons */
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s, transform 0.2s;
    position: relative;
    z-index: 3; /* Ensure buttons are clickable */
}

button:hover {
    background-color: #6a1b9a; /* Darker shade for hover effect */
}

button:active {
    transform: scale(0.98); /* Slight scale effect for button press */
}

/* Optional: Focus state for accessibility */
button:focus {
    outline: 2px solid #4a148c;
    outline-offset: 2px;
}
</style>
</head>
<body>
    <div class="container">
        <h1>How would you like to proceed?</h1>
        <div class="checkout-options">
            <button onclick="window.location.href='login.php'">Login with an existing MyHair4U account</button>
            <button onclick="window.location.href='register.html'">Create a MyHair4U account</button>
        </div>
    </div>
</body>
</html>
