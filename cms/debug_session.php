<?php
session_start();
header('Content-Type: text/plain');

// Display all session variables
print_r($_SESSION);
?>