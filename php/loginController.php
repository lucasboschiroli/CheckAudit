<?php
session_start();
include "./functions.php"; 
include_once "../config/conexao.php"; 

if(isset($_POST['email']) && isset($_POST['password'])){
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];

    $user = searchUserEmail($conn, $email_login);

    if($user) {
        if(password_verify($password_login, $user['password'])){
            echo "Olá " . $user['username'];
            $_SESSION['user_login'] = $user;
            Header('Location: ../pages/home.php');
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
} else {
    echo "Erro ao passar dados POST";
}


?>