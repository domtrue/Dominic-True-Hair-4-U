<?php
include 'setup.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $service_type = $_POST['service_type'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];

        // Prepare SQL query to insert the appointment into the database
        $query = "INSERT INTO appointments (user_id, service_type, appointment_date, appointment_time, first_name, last_name)
                  VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("isssss", $_SESSION['user_id'], $service_type, $appointment_date, $appointment_time, $first_name, $last_name);

            // Execute the query
            if ($stmt->execute()) {
                // Redirect to a success page or show confirmation
                echo "Your appointment has been successfully booked!";
            } else {
                echo "Error booking appointment: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "You must be logged in to book an appointment.";
    }
} else {
    echo "Invalid request method.";
}
?>
