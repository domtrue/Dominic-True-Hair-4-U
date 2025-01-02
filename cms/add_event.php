<?php
require 'setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $start = $_POST['start'] ?? null; // Full datetime for start
    $end = $_POST['end'] ?? null;     // Full datetime for end

    if ($title && $start && $end) {
        $stmt = $conn->prepare("INSERT INTO events (title, start_date, end_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        
        // Split datetime into date and time
        $start_date = explode(' ', $start)[0];
        $start_time = explode(' ', $start)[1];
        $end_date = explode(' ', $end)[0];
        $end_time = explode(' ', $end)[1];

        $stmt->bind_param("sssss", $title, $start_date, $end_date, $start_time, $end_time);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Event added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add event']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
}

$conn->close();
?>
