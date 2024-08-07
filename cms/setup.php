<?php
// Database connection details
$servername = "localhost";
$username = "sec_user";
$password = "greenChair153";
$dbname = "hair4u";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>