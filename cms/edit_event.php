<?php
include 'setup.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventId = $_POST['id'];
    $eventTitle = $_POST['title'];
    $startDateTime = $_POST['start']; // Format: 'YYYY-MM-DD HH:MM'
    $endDateTime = $_POST['end']; // Format: 'YYYY-MM-DD HH:MM'

    // Ensure data is valid (optional checks)
    if (empty($eventId) || empty($eventTitle) || empty($startDateTime) || empty($endDateTime)) {
        echo 'error';
        exit;
    }

    // Prepare the SQL query to update the event
    $sql = "UPDATE events 
            SET title = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ?
            WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Convert the date-time strings into separate date and time
        $startParts = explode(' ', $startDateTime);
        $endParts = explode(' ', $endDateTime);

        $startDate = $startParts[0]; // 'YYYY-MM-DD'
        $startTime = $startParts[1]; // 'HH:MM'
        $endDate = $endParts[0]; // 'YYYY-MM-DD'
        $endTime = $endParts[1]; // 'HH:MM'

        $stmt->bind_param("sssssi", $eventTitle, $startDate, $endDate, $startTime, $endTime, $eventId);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }

        $stmt->close();
    } else {
        echo 'error';
    }

    $conn->close();
}
?>
