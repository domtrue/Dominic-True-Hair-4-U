<?php
// Include your database connection setup
include 'setup.php';

// Get the raw POST data
$inputData = file_get_contents('php://input');
$data = json_decode($inputData, true); // Decode JSON into an associative array

// Check if the required data exists and sanitize it
if (!isset($data['serviceId']) || !isset($data['appointmentDate'])) {
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

$serviceId = $data['serviceId'];
$appointmentDate = $data['appointmentDate'];

// Function to get available times (you can modify this based on your available time logic)
function getAvailableTimes($date, $serviceId, $conn) {
    // Predefined available times for each service (example)
    $serviceTimes = [
        '1' => ['09:00', '10:00', '11:00', '14:00', '15:00'], // Service 1 available times
        '2' => ['09:30', '10:30', '12:00', '14:30', '15:30'], // Service 2 available times
        // Add more service ID => available times mapping as needed
    ];

    // Fetch already booked times for the selected service on the chosen date
    $query = "
        SELECT time_slot FROM appointments
        WHERE service_type = ? 
        AND appointment_date = ? 
        AND status = 'booked'"; // Adjust the status condition as needed
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
        return array_values($availableTimes); // Return available times after filtering
    }

    return [];
}

// Get available times for the provided service and date
$availableTimes = getAvailableTimes($appointmentDate, $serviceId, $conn);

// Return the available times as a JSON response
echo json_encode(['times' => $availableTimes]);
?>
