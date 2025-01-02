<?php
session_start();

// Include the database connection
include 'setup.php';

// Check if the user is logged in
if (!isset($_SESSION['user_details'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Get the appointment request ID from the URL
$appointment_id = $_GET['id'];

// Fetch the appointment details
$query = "SELECT ar.first_name, ar.last_name, s.service_name, ar.appointment_date, ar.appointment_time, ar.request_date 
          FROM appointment_requests ar
          JOIN services s ON ar.service_type = s.id
          WHERE ar.id = $appointment_id"; // Ensure proper sanitization in production

$result = $conn->query($query);
$appointment = $result->fetch_assoc();

// If no appointment found, redirect back to appointment requests
if (!$appointment) {
    header('Location: appointments.php');
    exit;
}

// Store appointment details in the session
$_SESSION['first_name'] = $appointment['first_name'];
$_SESSION['last_name'] = $appointment['last_name'];
$_SESSION['service_name'] = $appointment['service_name'];
$_SESSION['appointment_date'] = $appointment['appointment_date'];
$_SESSION['appointment_time'] = $appointment['appointment_time'];
$_SESSION['request_date'] = $appointment['request_date'];

// Fetch the user's email from the accounts table based on first and last name
$emailQuery = "SELECT email FROM accounts WHERE firstname = '{$appointment['first_name']}' AND lastname = '{$appointment['last_name']}'";
$emailResult = $conn->query($emailQuery);
$email = $emailResult->fetch_assoc()['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respond to Appointment Request</title>
  
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="content">
        <h1>Respond to Appointment Request</h1>
        <h2>Appointment Details:</h2>
        <ul>
            <li><strong>First Name:</strong> <?php echo htmlspecialchars($appointment['first_name']); ?></li>
            <li><strong>Last Name:</strong> <?php echo htmlspecialchars($appointment['last_name']); ?></li>
            <li><strong>Requested Service:</strong> <?php echo htmlspecialchars($appointment['service_name']); ?></li>
            <li><strong>Proposed Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></li>
            <li><strong>Proposed Time:</strong> <?php echo htmlspecialchars($appointment['appointment_time']); ?></li>
            <li><strong>Date Requested:</strong> <?php echo htmlspecialchars($appointment['request_date']); ?></li>
        </ul>

        <h2>Email Form</h2>   
        
        <div class="form-group">
            <label for="to">To:</label>
            <input type="email" name="to" id="to" value="<?php echo $email; ?>" readonly required>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" value="RE: <?php echo $appointment['service_name']; ?> request" required>

            <label for="body">Body:</label>
            <textarea name="body" id="body" placeholder="Your message" required></textarea>

            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
