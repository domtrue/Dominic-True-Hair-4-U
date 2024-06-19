<?php
// Include the database connection setup file
include 'setup.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    // SQL query to insert form data into the contacts table
    $sql = "INSERT INTO contacts (firstname, lastname, email, message, created_at)
        VALUES ('$firstname', '$lastname', '$email', '$message', NOW())";
    // Execute the query and check if it was successful
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . " " . $conn->error;
    }
    // Close the database connection
    $conn->close();
}
?>
