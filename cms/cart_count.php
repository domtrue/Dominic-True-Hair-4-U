<?php
session_start();
$total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;

header('Content-Type: application/json');
echo json_encode(['totalItems' => $total_items]);
?>
