<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "checkaudit";

$portas = [3306];
$conn = null;

foreach ($portas as $porta) {
    $conn = @new mysqli($host, $user, $pass, $db, $porta);
    if (!$conn->connect_error) {
        break;
    }
}

// Ensure connection is established or terminate
if ($conn === null || $conn->connect_error) {
    $error_message = $conn ? $conn->connect_error : "Failed to create connection object";
    error_log("Database connection failed: " . $error_message);
    die("Database connection failed. Please try again later.");
}

// Set charset for better security
$conn->set_charset("utf8");

?>