<?php session_start();?>
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
    <link rel="stylesheet" href="../assets/css/header.css">
    <title>Home - CheckAudit</title>
</head>

<body>
    <?php
    include "../includes/header.php";
    include "../php/functions.php";
    include "../config/conexao.php";
    ?>

    <main>

        <div id="form-criar-auditoria" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="esconderDiv('form-criar-auditoria')">&times;</span>
                <form action="../php/criarAuditoriaController.php" method="POST">
                    <div class="form-group">
                        <label for="titulo">Título do Projeto</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Informe o título do projeto" required>
                    </div>

                    <div class="form-group">
                        <label for="responsavel">Responsável pelo projeto</label>
                        <input type="text" id="responsavel" name="responsavel" placeholder="Informe o responsável" required>
                    </div>

                    <div class="form-group">
                        <label for="email_responsavel">E-mail do responsável</label>
                        <input type="email" id="email_responsavel" name="email_responsavel" placeholder="Informe o E-mail do responsável" required>
                    </div>

                    <div class="form-group">
                        <label for="data-realizacao">Data realização da auditoria</label>
                        <input type="date" id="data-realizacao" name="data-realizacao" required>
                    </div>

                    <div class="form-group">
                        <label for="objetivo">Objetivo da auditoria</label>
                        <input type="text" id="objetivo" name="objetivo" placeholder="Informe o objetivo" required>
                    </div>

                    <button class="signup-btn">
                        <i class="fa-solid fa-square-plus"></i> Criar
                    </button>
                </form>
            </div>
        </div>

        <div class="btn-criar-container">
            <button onclick="mostrarDiv('form-criar-auditoria')">
                <i class="fa-solid fa-square-plus" style="color: #ffffff;"></i>
                Criar auditoria
            </button>
        </div>

        <div class="auditorias-titulo">
            <h3>Auditorias</h3>
        </div>

        <div class="auditorias-container">
            <?php
            $auditorias = buscarAudutoriasIdUsuario($conn, $_SESSION['user_login']['id']);

            if (count($auditorias) > 0) {
                foreach ($auditorias as $row) {
                    echo '<a class="auditoria-card" href="auditoria.php?id_auditoria='.$row['id_auditoria'].'">';
                    echo "<h3>".$row['titulo_projeto']."</h3>";
                    echo "<p><strong>Responsável:</strong> ".$row['responsavel']."</p>";
                    echo "<p><strong>Data:</strong> ".$row['data_realizacao']."</p>";
                    echo "<p><strong>Objetivo:</strong> ".$row['objetivo']."</p>";
                    echo '</a>';
                }
            } else {
                echo "<div class='sem-auditoria'>";
                echo "<h3>Sem auditorias criadas no momento</h3>";
                echo "</div>";
            }
            ?>
        </div>
    </main>

    <?php include "../includes/footer.php";?>
    
</body>

</html>
<script src="../assets/js/divs.js"></script>