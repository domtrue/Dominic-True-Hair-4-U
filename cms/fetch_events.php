<?php
// fetch_events.php

// Include database connection
require 'setup.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch events from the database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch_assoc()) {
    // Ensure the date and time are in the correct format (YYYY-MM-DDTHH:mm:ss)
    $start = $row['start_date'] . 'T' . $row['start_time'];  // Combine start date and time
    $end = $row['end_date'] . 'T' . $row['end_time'];      // Combine end date and time

    $events[] = [
        'title' => $row['title'],
        'start' => $start,
        'end' => $end
    ];    
}

// Send the events as JSON
echo json_encode($events);

$conn->close();
?>
