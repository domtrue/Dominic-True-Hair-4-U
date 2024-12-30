<?php
include 'setup.php';
session_start();

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

function getAvailableTimes($date, $serviceId, $conn) {
    // Predefined available times for each service
    $serviceTimes = [
        '1' => ['09:00', '10:00', '11:00', '14:00', '15:00'], // Service 1 available times
        '2' => ['09:30', '10:30', '12:00', '14:30', '15:30'], // Service 2 available times
        // Add more service ID => available times mapping as needed
    ];

    // If the date is a closed date, return empty (no available times)
    if (in_array($date, $closedDays)) {
        return [];
    }

    // Fetch already booked times for the selected service on the chosen date
    $query = "
        SELECT time_slot FROM appointments
        WHERE service_type = ? 
        AND appointment_date = ? 
        AND status = 'booked'"; // Adjust this based on your status field
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $serviceId, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Get booked times
    $bookedTimes = [];
    while ($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row['time_slot'];
    }

    // Filter out the booked times from the predefined service times
    if (array_key_exists($serviceId, $serviceTimes)) {
        // Remove the booked times from the available times
        $availableTimes = array_diff($serviceTimes[$serviceId], $bookedTimes);
        return array_values($availableTimes); // Return the available times after filtering out the booked ones
    }

    return [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hair 4 U - Book Appointment</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
         /* Bold available dates (Thursday, Friday, Saturday) */
         .available-date {
            font-weight: bold;
            color: black;
        }

        /* Dim unavailable dates */
        .unavailable-date {
            color: gray;
            opacity: 0.5;
        }

        /* Flatpickr calendar styling */
        .flatpickr-calendar {
            font-size: 1.1rem;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 8px;
            width: 100%;
            max-width: 320px;
            margin-left: 115px;
            margin-bottom: 20px;
        }

        /* Form container */
        .appointment-form {
            max-width: 600px;
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .appointment-form h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .appointment-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .appointment-form input, .appointment-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .appointment-form input[type="submit"] {
            background-color: #4a148c;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .appointment-form input[type="submit"]:hover {
            background-color: #6a1b9a;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
        <div class="appointment-form">
            <h1>Book an Appointment</h1>

            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<form action="appointment_form.php" method="post">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>

                    <label for="service_type">Select Service:</label>
                    <select id="service_type" name="service_type" required>
                        <option value="">-- Select a Service --</option>';

                        foreach ($services as $service) {
                            echo '<option value="' . htmlspecialchars($service["id"]) . '"';
                            if (isset($_POST['service_type']) && $_POST['service_type'] == $service["id"]) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($service["service_name"]) . '</option>';
                        }

                echo '</select>

                    <label for="appointment_date">Appointment Date:</label>
                    <input type="text" id="appointment_date" name="appointment_date" required>

                    <label for="appointment_time">Appointment Time:</label>
                    <select id="appointment_time" name="appointment_time" required>
                        <option value="">-- Select an Available Time --</option>
                    </select>

                    <input type="submit" value="Book Appointment">
                </form>';
            } else {
                echo '<p class="login-message">You must be logged in to book an appointment.<br>
                <a href="login.php">Log in</a> or <a href="signup.php">Create an account</a></p>';
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const appointmentDateInput = document.getElementById("appointment_date");
            const appointmentTimeSelect = document.getElementById("appointment_time");
            const serviceSelect = document.getElementById("service_type");

            // Closed days from PHP (to be inserted dynamically in JS)
            const closedDates = <?php echo json_encode($closedDays); ?>;

            flatpickr(appointmentDateInput, {
                dateFormat: "Y-m-d",
                disable: [
                    function(date) {
                        const day = date.getDay();
                        // Disable all days except Thursday, Friday, Saturday
                        return !(day === 4 || day === 5 || day === 6); 
                    },
                    ...closedDates.map(day => new Date(day).toISOString().split('T')[0]) // Disable closed dates
                ],
                inline: true,
                locale: {
                    firstDayOfWeek: 1
                }
            });

            // Fetch available times based on selected service and date
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
    </script>
</body>
</html>
