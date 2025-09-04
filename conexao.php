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

if ($conn === null || $conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>