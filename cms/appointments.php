<?php
include 'setup.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['account_id'])) {
    $accountId = $_SESSION['user_details']['account_id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $firstName = "User";
    $accountId = null;
}

// Fetch upcoming appointments
$upcomingAppointmentsQuery = "SELECT * FROM appointments WHERE appointment_datetime >= NOW() ORDER BY appointment_datetime ASC";
$upcomingAppointmentsResult = $conn->query($upcomingAppointmentsQuery);

// Fetch past appointments
$pastAppointmentsQuery = "SELECT * FROM appointments WHERE appointment_datetime < NOW() ORDER BY appointment_datetime DESC";
$pastAppointmentsResult = $conn->query($pastAppointmentsQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointments</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include 'admin_navbar.php'; ?>

<div class="content">
<h1 class="appointments-heading">Appointments</h1>
    
    <!-- Upcoming Appointments -->
    <h2>Upcoming Appointments</h2>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Service Type</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($upcomingAppointmentsResult->num_rows > 0): ?>
                <?php while ($row = $upcomingAppointmentsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_first_name'] . ' ' . $row['customer_last_name'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row['service_type'], ENT_QUOTES); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row['appointment_datetime'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No upcoming appointments.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Past Appointments -->
    <h2>Past Appointments</h2>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Service Type</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($pastAppointmentsResult->num_rows > 0): ?>
                <?php while ($row = $pastAppointmentsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_first_name'] . ' ' . $row['customer_last_name'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row['service_type'], ENT_QUOTES); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row['appointment_datetime'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No past appointments.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
