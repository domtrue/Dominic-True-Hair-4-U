<?php
session_start();

// Include the database connection
include 'setup.php';

// Check if the user is logged in
if (!isset($_SESSION['user_details'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Fetch all appointment requests
$query = "SELECT * FROM appointment_requests";
$result = $conn->query($query);

// Fetch services from the services table
$serviceQuery = "SELECT * FROM services";
$serviceResult = $conn->query($serviceQuery);
$services = [];
while ($service = $serviceResult->fetch_assoc()) {
    $services[$service['id']] = $service['service_name'];
}

// Check if the query was successful
if (!$result) {
    echo "Error: " . $conn->error;
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Requests</title>
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php include 'admin_navbar.php'; ?>
</head>
<body>

<div class="content">
    <h1 class="appointments-heading"> <?php echo $_SESSION['user_details']['firstname']; ?>, these are your appointment requests</h1>

    <table class="appointments-table">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Requested Service</th>
            <th>Proposed Date</th>
            <th>Proposed Time</th>
            <th>Date Requested</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
       <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($services[$row['service_type']]); ?></td>
                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                <td>
                    <a href="respond_to_request.php?id=<?php echo $row['id']; ?>" class="btn">Respond to request</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    </table>
</div>

</body>
</html>
