<?php 
include 'setup.php';
session_start();

// Ensure user is logged in before accessing session data
if (isset($_SESSION['user_details'])) {
    $first_name = $_SESSION['user_details']['firstname'];
    $last_name = $_SESSION['user_details']['lastname'];
} else {
    $first_name = '';
    $last_name = '';
}

// Fetch closed days from the database
$query = "SELECT closed_date FROM closed_days";
$closedDaysResult = $conn->query($query);

$closedDays = [];
if ($closedDaysResult && $closedDaysResult->num_rows > 0) {
    while ($row = $closedDaysResult->fetch_assoc()) {
        $closedDays[] = $row['closed_date'];
    }
}

// Fetch available services
$serviceQuery = "SELECT id, service_name FROM services";
$serviceResult = $conn->query($serviceQuery);
$services = [];
if ($serviceResult && $serviceResult->num_rows > 0) {
    while ($service = $serviceResult->fetch_assoc()) {
        $services[] = $service;
    }
}

global $closedDays;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U - Request Appointment</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/request_appointment.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <div class="appointment-form">
            <h1>Request an Appointment</h1>

            <form action="process_appointment.php" method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>

                <label for="service_type">Select Service:</label>
                <select id="service_type" name="service_type" required>
                    <option value="">-- Select a Service --</option>
                    <?php
                    foreach ($services as $service) {
                        echo '<option value="' . htmlspecialchars($service["id"]) . '">' . htmlspecialchars($service["service_name"]) . '</option>';
                    }
                    ?>
                </select>

                <label for="appointment_date">Appointment Date:</label>
                <input type="text" id="appointment_date" name="appointment_date" required>

                <label for="appointment_time">Appointment Time:</label>
                <input type="time" id="appointment_time" name="appointment_time" required>

                <input type="submit" value="Request Appointment">
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const appointmentDateInput = document.getElementById("appointment_date");
            const appointmentTimeSelect = document.getElementById("appointment_time");
            const serviceSelect = document.getElementById("service_type");

            const closedDates = <?php echo json_encode($closedDays); ?>;

            flatpickr(appointmentDateInput, {
                dateFormat: "Y-m-d",
                disable: [
                    function(date) {
                        const day = date.getDay();
                        return !(day === 4 || day === 5 || day === 6); 
                    },
                    ...closedDates.map(day => new Date(day).toISOString().split('T')[0])
                ],
                inline: true,
                locale: {
                    firstDayOfWeek: 1
                }
            });

            serviceSelect.addEventListener("change", function() {
                updateAvailableTimes();
            });
            appointmentDateInput.addEventListener("change", function() {
                updateAvailableTimes();
            });

            function updateAvailableTimes() {
                const serviceId = serviceSelect.value;
                const appointmentDate = appointmentDateInput.value;

                if (serviceId && appointmentDate) {
                    fetchAvailableTimes(serviceId, appointmentDate);
                }
            }

            function fetchAvailableTimes(serviceId, appointmentDate) {
                fetch('get_available_times.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ serviceId: serviceId, appointmentDate: appointmentDate })
                })
                .then(response => response.json())
                .then(data => {
                    appointmentTimeSelect.innerHTML = '<option value="">-- Select an Available Time --</option>';
                    data.times.forEach(function(time) {
                        const option = document.createElement("option");
                        option.value = time;
                        option.textContent = time;
                        appointmentTimeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching available times:', error));
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const timeInput = document.getElementById("appointment_time");
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            timeInput.value = `${hours}:${minutes}`;
        });
    </script>
</body>
</html>
