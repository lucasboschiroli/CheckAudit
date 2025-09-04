<?php
session_start();

if (isset($_SESSION['user_login'])) {
    header("Location: pages/home.php");
    exit();
} else {
    header("Location: pages/login.html");
    exit();
}
