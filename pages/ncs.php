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
    <style>
        /* Fundo escuro do modal (overlay) */
        .modal {
            display: none; /* inicia escondido */
            position: fixed;
            z-index: 1000;
            inset: 0; /* top/right/bottom/left: 0 */
            background: rgba(0, 0, 0, 0.6); /* fundo escuro semi-transparente */
            backdrop-filter: blur(5px); /* leve blur */
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        /* Caixa do modal */
        .modal-content {
            background: rgba(255, 255, 255, 0.08); /* translúcido */
            backdrop-filter: blur(12px); /* vidro fosco */
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            color: #fff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            animation: slideDown 0.4s ease;
        }

        /* Título dentro do modal */
        .modal-content h2 {
            font-size: 2.4rem;
            color: #ee4abd;
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        /* Inputs do modal no mesmo estilo do site */
        .modal-content .form-group input {
            width: 100%;
            padding: 1.4rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            font-size: 1.6rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .modal-content .form-group input:focus {
            border-color: #ee4abd;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .modal-content .form-group label {
            color: #ccc;
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            display: block;
        }

        /* Botão rosa já no padrão */
        .modal-content .signup-btn-pink {
            width: 100%;
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }

        .fechar {
            float: right;
            font-size: 22px;
            cursor: pointer;
            color: #333;
        }



    </style>

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
                $escalonamentos = buscarEscalonamentosPorNC($conn, $nc['id']);
                ?>
                <div class="card-nc">
                    <h3>Não-Conformidade #<?= $nc['id'] ?></h3>
                    <p><strong>Descrição:</strong> <?= htmlspecialchars($nc['observacoes']) ?></p>
                    <p><strong>Responsável:</strong> <?= htmlspecialchars($nc['responsavel']) ?></p>
                    <p><strong>Data de solicitação 1ª:</strong> <?= htmlspecialchars($nc['created_at']) ?></p>
                    <?php if (!empty($escalonamentos)) : ?>
                        <?php if (isset($escalonamentos[0])): ?>
                            <p><strong>Data de solicitação 2ª:</strong> <?= htmlspecialchars($escalonamentos[0]['data_escalonamento']) ?></p>
                        <?php endif; ?>
                        <?php if (isset($escalonamentos[1])): ?>
                            <p><strong>Data de solicitação 3ª:</strong> <?= htmlspecialchars($escalonamentos[1]['data_escalonamento']) ?></p>
                        <?php endif; ?>
                        <?php if (isset($escalonamentos[2])): ?>
                            <p><strong>Data de solicitação 4ª:</strong> <?= htmlspecialchars($escalonamentos[2]['data_escalonamento']) ?></p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <p><strong>Prazo para resolução:</strong> <?= htmlspecialchars($nc['prazo_resolucao']) ?></p>
                    <p><strong>Solução adotada:</strong> <?= htmlspecialchars($nc['acao_corretiva']) ?></p>
                    <p><strong>Situação:</strong> <?= htmlspecialchars($nc['situacao_nc']) ?></p>
                    <p><strong>Escalonamentos:</strong> <?= isset($nc['escalonamento']) ? $nc['escalonamento'] : 0 ?></p>

                    <div class="card-nc-botoes" >
                        <a href="#"
                           class="signup-btn-pink"
                           onclick="abrirModal(<?= $nc['id'] ?>)">
                            <i class="fa-solid fa-signal"></i> Escalonar NC
                        </a>

                        <a href="../php/email_nc.php?id_auditoria=<?=$id_auditoria?>" class="signup-btn-pink" style="text-decoration: none; display: inline-block;">
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
        <!-- Modal Escalonamento -->
        <div id="modal-escalonamento" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="fechar" onclick="fecharModal()">&times;</span>
                <h2>Escalonar NC</h2>

                <form action="../php/comunicar_superior.php" method="POST" class="form">
                    <input type="hidden" name="id_nc" id="id_nc_modal">

                    <div class="form-group">
                        <label for="responsavel_imediato">Nome do superior imediato</label>
                        <input type="text" name="responsavel_imediato" id="responsavel_imediato"
                               class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="email_responsavel_imediato">E-mail do superior imediato</label>
                        <input type="email" name="email_responsavel_imediato" id="email_responsavel_imediato"
                               class="form-input" required>
                    </div>

                    <button type="submit" class="signup-btn-pink">
                        <i class="fa-solid fa-paper-plane" style="color: #ffffff;"></i> Comunicar superior
                    </button>
                </form>

            </div>
        </div>


    </main>


<?php include "../includes/footer.php";?>
</body>

</html>
<script>
    function abrirModal(id_nc) {
        document.getElementById("modal-escalonamento").style.display = "flex";
        document.getElementById("id_nc_modal").value = id_nc;
    }
    function fecharModal() {
        document.getElementById("modal-escalonamento").style.display = "none";
    }
</script>

