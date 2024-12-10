<?php
session_start();
header("Content-Type: application/json");

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit();
}

$_SESSION['card_name'] = $data['card_name'] ?? null;
$_SESSION['country_region'] = $data['country_region'] ?? null;
$_SESSION['zip_code'] = $data['zip_code'] ?? null;

echo json_encode(['success' => true]);
?>