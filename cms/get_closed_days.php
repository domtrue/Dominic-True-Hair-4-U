<?php
include 'setup.php';

$query = "SELECT closed_date FROM closed_days";
$result = $conn->query($query);

$closedDays = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $closedDays[] = $row['closed_date'];
    }
}

header('Content-Type: application/json');
echo json_encode($closedDays);
?>
