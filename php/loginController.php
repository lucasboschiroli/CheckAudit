<?php
session_start();
include "./functions.php"; 
include_once "../config/conexao.php"; 

if(isset($_POST['email']) && isset($_POST['password'])){
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];

    $user = searchUserEmail($conn, $email_login);

    if($user) {

        if(isset($user['password']) && password_verify($password_login, $user['password'])){
            $_SESSION['user_login'] = $user;
            header('Location: ../pages/home.php');
            exit();
        } else {

            header('Location: ../pages/login.php?error=incorrect_password');
            exit();
        }
    } else {

        header('Location: ../pages/login.php?error=user_not_found');
        exit();
    }
} else {

    header('Location: ../pages/login.php?error=missing_data');
    exit();
}
?>