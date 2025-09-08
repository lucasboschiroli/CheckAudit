<?php
session_start();

include "./functions.php";
include_once "../config/conexao.php";

$id_nc = $_GET['id_nc'];
$id_auditoria = $_GET['id_auditoria'];

marcarNCComoResolvida($conn, $id_nc);

Header("Location:../pages/ncs.php?id_auditoria=$id_auditoria");

?>