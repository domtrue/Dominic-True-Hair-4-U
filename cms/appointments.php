<?php
include 'setup.php';
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Check user role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'customer'; // Default to 'customer'

// Check if the user details are set in the session
if (isset($_SESSION['user_details']) && isset($_SESSION['user_details']['account_id'])) {
    $accountId = $_SESSION['user_details']['account_id'];
    $firstName = htmlspecialchars($_SESSION['user_details']['firstname'], ENT_QUOTES);
} else {
    $firstName = "User";
    $accountId = null;
}

// Fetch appointments based on role
if ($role === 'admin') {
    // Admin: Fetch all appointments with customer details
    $upcomingAppointmentsQuery = "
        SELECT appointments.*, CONCAT(accounts.firstname, ' ', accounts.lastname) AS customer_name 
        FROM appointments 
        JOIN accounts ON appointments.id = accounts.id
        WHERE appointment_datetime >= NOW()
        ORDER BY appointment_datetime ASC";

    $pastAppointmentsQuery = "
        SELECT appointments.*, CONCAT(accounts.firstname, ' ', accounts.lastname) AS customer_name 
        FROM appointments 
        JOIN accounts ON appointments.id = accounts.id
        WHERE appointment_datetime < NOW()
        ORDER BY appointment_datetime DESC";
} else {
    // Customer: Fetch only their appointments
    $upcomingAppointmentsQuery = "
        SELECT * 
        FROM appointments 
        WHERE id = ? AND appointment_datetime >= NOW()
        ORDER BY appointment_datetime ASC";

    $pastAppointmentsQuery = "
        SELECT * 
        FROM appointments 
        WHERE id = ? AND appointment_datetime < NOW()
        ORDER BY appointment_datetime DESC";
}

$stmtUpcoming = $conn->prepare($upcomingAppointmentsQuery);
$stmtPast = $conn->prepare($pastAppointmentsQuery);

if ($role === 'customer') {
    $stmtUpcoming->bind_param('i', $accountId);
    $stmtPast->bind_param('i', $accountId);
}

$stmtUpcoming->execute();
$upcomingAppointmentsResult = $stmtUpcoming->get_result();

$stmtPast->execute();
$pastAppointmentsResult = $stmtPast->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php 
// Include navbar based on role
if ($role === 'admin') {
    include 'admin_navbar.php';
} else {
    include 'customer_navbar.php';
}
?>

<div class="content">
    <h1 class="appointments-heading">Welcome, <?php echo $firstName; ?>! Here are your Appointments</h1>

    <!-- Upcoming Appointments -->
    <h2>Upcoming Appointments</h2>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Type</th>
                <th>Date & Time</th>
                <?php if ($role === 'admin'): ?>
                <th>Customer Name</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($upcomingAppointmentsResult->num_rows > 0): ?>
                <?php while ($row = $upcomingAppointmentsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['service_type'], ENT_QUOTES); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row['appointment_datetime'])); ?></td>
                        <?php if ($role === 'admin'): ?>
                        <td><?php echo htmlspecialchars($row['customer_name'], ENT_QUOTES); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo $role === 'admin' ? 4 : 3; ?>">No upcoming appointments.</td>
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
                <th>Service Type</th>
                <th>Date & Time</th>
                <?php if ($role === 'admin'): ?>
                <th>Customer Name</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($pastAppointmentsResult->num_rows > 0): ?>
                <?php while ($row = $pastAppointmentsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['service_type'], ENT_QUOTES); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row['appointment_datetime'])); ?></td>
                        <?php if ($role === 'admin'): ?>
                        <td><?php echo htmlspecialchars($row['customer_name'], ENT_QUOTES); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo $role === 'admin' ? 4 : 3; ?>">No past appointments.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
