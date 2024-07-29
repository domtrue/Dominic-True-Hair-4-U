<?php
// Include your database connection file here
// Example: include 'db_connection.php';

// Retrieve form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$service_type = $_POST['service_type'];
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];

// Combine date and time into a datetime format
$appointment_datetime = $appointment_date . ' ' . $appointment_time;

// Insert into database
// Example assuming you have a PDO connection established
try {
    $pdo = new PDO('mysql:host=localhost;dbname=hair4u', 'sec_user', 'greenChair153');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('INSERT INTO appointments (customer_first_name, customer_last_name, service_type, appointment_datetime) VALUES (?, ?, ?, ?)');
    $stmt->execute([$first_name, $last_name, $service_type, $appointment_datetime]);

    echo 'Appointment booked successfully!';
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}
?>
