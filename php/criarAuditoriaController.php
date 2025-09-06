<?php
include_once "../config/conexao.php";
include "../php/functions.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $titulo = $_POST['titulo'];
    $objetivo = $_POST['objetivo'];
    $responsavel = $_POST['responsavel'];
    $data = $_POST['data-realizacao'];
    $id_usuario = $_SESSION['user_login']['id'];

    inserirAuditoria($conn, $titulo, $responsavel, $data, $objetivo, $id_usuario);
    Header("Location: ../pages/home.php");
     
} else {
    echo "Erro ao receber os dados via POST";
}
?>