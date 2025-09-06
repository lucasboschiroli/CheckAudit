
<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="" type="image/jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="stylesheet" href="../assets/css/auditoria.css">
    <title>Auditoria - CheckAudit</title>
</head>

<body>
<?php
include "../includes/header.php";
include "../php/functions.php";
include "../config/conexao.php";
?>
 <?php include "../includes/header.php";?>
<?php
    $id_auditoria = $_GET['id_auditoria'];
    $dados_auditoria = buscarAudutoriasIdAuditoria($conn,$id_auditoria);
?>
    <main class="auditoria-detalhes">
        <h1 class="auditoria-titulo">Auditoria <?= $dados_auditoria['titulo_projeto'] ?></h1>

        <div class="auditoria-info">
            <h3>Dados da auditoria</h3>
            <p><strong>Responsável:</strong> <?= $dados_auditoria['responsavel'] ?></p>
            <p><strong>Data de realização:</strong> <?= $dados_auditoria['data_realizacao'] ?></p>
            <p><strong>Objetivo:</strong> <?= $dados_auditoria['objetivo'] ?></p>
        </div>

        <div class="auditoria-acoes">
            <button class="signup-btn"> <i class="fa-solid fa-square-plus" style="color: #ffffff;"></i> Criar Checklist</button>
            <button class="signup-btn"> <i class="fa-solid fa-list-check" style="color: #ffffff;"></i> Realizar Auditoria</button>
        </div>
    </main>

<?php include "../includes/footer.php";?>
</body>

</html>
