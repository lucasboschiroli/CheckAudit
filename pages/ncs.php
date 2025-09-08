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
    <link rel="stylesheet" href="../assets/css/ncs.css">
    <title>NCs - CheckAudit</title>

</head>

<body>
<?php
    include "../includes/header.php";
    include "../php/functions.php";
    include "../config/conexao.php";
    $id_auditoria = $_GET['id_auditoria'];
    $ncs = buscarNaoConformidadesIdChecklist($conn, $id_auditoria);
?>
<?php include "../includes/header.php";?>
    <main>
    <div class="cards-container">
        <?php
        $ncs = buscarNaoConformidadesIdChecklist($conn, $id_auditoria);

        if(!empty($ncs)){
            foreach($ncs as $nc){
                ?>
                <div class="card-nc">
                    <h3>Não-Conformidade #<?= $nc['id'] ?></h3>
                    <p><strong>Descrição:</strong> <?= htmlspecialchars($nc['pergunta']) ?></p>
                    <p><strong>Responsável:</strong> <?= htmlspecialchars($nc['responsavel']) ?></p>
                    <p><strong>Prazo para resolução:</strong> <?= htmlspecialchars($nc['resultado']) ?></p>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($nc['classificacao_nc']) ?></p>
                    <p><strong>Solução adotada:</strong> <?= htmlspecialchars($nc['acao_corretiva']) ?></p>
                    <p><strong>Situação:</strong> <?= htmlspecialchars($nc['situacao_nc']) ?></p>

                    <!-- Novo campo de escalonamento -->
                    <p><strong>Escalonamentos:</strong> <?= isset($nc['escalonamentos']) ? $nc['escalonamentos'] : 0 ?></p>

                    <!-- Botões -->
                    <div class="card-nc-botoes">
                        <button class="botao-verde">Comunicar NC</button>
                        <button class="botao-verde">Escalonar NC</button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p style='color:#ccc; text-align:center;'>Nenhuma não-conformidade encontrada para esta auditoria.</p>";
        }
        ?>
    </div>
</main>


<?php include "../includes/footer.php";?>
</body>

</html>
