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
                    <p><strong>Descrição:</strong> <?= htmlspecialchars($nc['observacoes']) ?></p>
                    <p><strong>Responsável:</strong> <?= htmlspecialchars($nc['responsavel']) ?></p>
                    <p><strong>Data de solicitação 1ª:</strong> <?= htmlspecialchars($nc['created_at']) ?></p>
                    <p><strong>Prazo para resolução:</strong> <?= htmlspecialchars($nc['prazo_resolucao']) ?></p>
                    <p><strong>Solução adotada:</strong> <?= htmlspecialchars($nc['acao_corretiva']) ?></p>
                    <p><strong>Situação:</strong> <?= htmlspecialchars($nc['situacao_nc']) ?></p>
                    <p><strong>Escalonamentos:</strong> <?= isset($nc['escalonamento']) ? $nc['escalonamento'] : 0 ?></p>

                    <div class="card-nc-botoes" >
                        <a href="ncs.php?id_auditoria=<?= $id_auditoria ?>" class="signup-btn-pink" style="text-decoration: none; display: inline-block;">
                            <i class="fa-solid fa-signal" style="color: #ffffff;"></i> Escalonar NC
                        </a>
                        <a href="ncs.php?id_auditoria=<?= $id_auditoria ?>" class="signup-btn-pink" style="text-decoration: none; display: inline-block;">
                            <i class="fa-solid fa-paper-plane" style="color: #ffffff;"></i> Comunicar NC
                        </a>
                    </div>

                    <div>
                        <?php if ($nc['situacao_nc'] !== 'resolvida'): ?>
                            <a href="../php/nc-concluida.php?id_nc=<?= $nc['id'] ?>&id_auditoria=<?= $id_auditoria ?>"
                               class="signup-btn"
                               style="text-decoration: none; display: inline-block;">
                                Marcar NC como resolvida
                                <i class="fa-solid fa-check" style="color: #ffffff;"></i>
                            </a>
                        <?php endif; ?>
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
