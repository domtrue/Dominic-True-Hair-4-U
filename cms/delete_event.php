<?php
include 'setup.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventId = $_POST['eventId'];

    // Ensure event ID is provided
    if (empty($eventId)) {
        echo 'error';
        exit;
    }

    // Prepare the SQL query to delete the event
    $sql = "DELETE FROM events WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $eventId);

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
