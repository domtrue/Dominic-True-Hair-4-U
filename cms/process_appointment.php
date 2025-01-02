<?php
include 'setup.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $service_type = intval($_POST['service_type']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    // Prepare the SQL query
    $query = "INSERT INTO appointment_requests (first_name, last_name, service_type, appointment_date, appointment_time)
              VALUES ('$first_name', '$last_name', $service_type, '$appointment_date', '$appointment_time')";

    // Execute the query
    if ($conn->query($query) === TRUE) {
        // Redirect to success page after form submission
        header("Location: appointment_success.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
