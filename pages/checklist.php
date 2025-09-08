<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Checklist - CheckAudit</title>
    <style>
        .checklist-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 2rem;
            font-size: 1.4rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.4);
        }
        .checklist-table th, .checklist-table td {
            padding: 1rem;
            text-align: left;
        }
        .checklist-table thead {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .checklist-table thead th {
            color: #fff;
            font-weight: 600;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }
        .checklist-table tbody tr {
            background: rgba(255,255,255,0.05);
            transition: background 0.3s ease;
        }
        .checklist-table tbody tr:nth-child(even) {
            background: rgba(255,255,255,0.08);
        }
        .checklist-table tbody tr:hover {
            background: rgba(255,255,255,0.15);
        }
        .checklist-table td {
            color: #f1f1f1;
            vertical-align: middle;
        }
        .checklist-table td input,
        .checklist-table td textarea,
        .checklist-table td select {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            border: none;
            font-size: 1.3rem;
            background: rgba(255,255,255,0.15);
            color: #ebe5e5ff;
            transition: 0.3s ease;
        }
        .checklist-table td input:focus,
        .checklist-table td textarea:focus,
        .checklist-table td select:focus {
            background: rgba(255,255,255,0.25);
            outline: 2px solid #ee4abd;
        }
        .btn-acao {
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            font-weight: 600;
            font-size: 1.3rem;
            margin: 0.2rem;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }
        .btn-acao:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }
        .btn-edit { background: #07ffc9ff; color: #000; }
        .btn-delete { background: #35dc80ff; color: #fff; }
        .btn-save-all { background: #ff6b6b; color: #fff; padding: 1.5rem 3rem; font-size: 1.8rem; }
        .btn-voltar { 
            background: linear-gradient(135deg, #6c757d, #495057); 
            color: #fff; 
            text-decoration: none; 
            padding: 1rem 2rem; 
            border-radius: 8px;
            margin-right: 1rem;
            display: inline-block;
            font-size: 1.4rem;
        }

        .auditoria-info {
            background: rgba(255,255,255,0.1);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .auditoria-info h3 {
            color: #28a745;
            margin-bottom: 1rem;
            font-size: 1.6rem;
        }
        
        .auditoria-info p {
            color: #f1f1f1;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 1200px) {
            .checklist-table {
                font-size: 1.2rem;
            }
            .checklist-table th, .checklist-table td {
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <?php
    require_once "../config/conexao.php";
    include "../php/functions.php";
    
   
    if (!isset($_GET['id_auditoria']) || empty($_GET['id_auditoria'])) {
        die("ID da auditoria não foi especificado!");
    }
    
    $id_auditoria = (int)$_GET['id_auditoria'];
    
   
    $dados_auditoria = buscarAudutoriasIdAuditoria($conn, $id_auditoria);
    if (!$dados_auditoria) {
        die("Auditoria não encontrada!");
    }
    ?>
    
    <div class="signup-container" style="max-width: 1400px;"> 
  
        <div class="auditoria-info">
            <h3>Checklist da Auditoria: <?= htmlspecialchars($dados_auditoria['titulo_projeto']) ?></h3>
        

        <h2 class="signup-title">Checklist da Auditoria</h2>

        <form action="../php/checklist_action.php" method="POST" style="margin-bottom:2rem;">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="id_auditoria" value="<?= $id_auditoria ?>">
            <div class="form-group">
                <label for="pergunta_simples">Adicionar Nova Pergunta</label>
                <input type="text" id="pergunta_simples" name="pergunta" placeholder="Digite a pergunta" required>
            </div>
            <button type="submit" class="signup-btn">Adicionar Pergunta</button>
        </form>
     
       <form action="../php/checklist_action.php" method="POST" id="formSalvarTudo">
            <input type="hidden" name="action" value="update_all">
            <input type="hidden" name="id_auditoria" value="<?= $id_auditoria ?>">

            <table class="checklist-table">
                <thead>
                    <tr>
                        <th style="width: 3%;">Nº</th>
                        <th style="width: 20%;">Descrição</th>
                        <th style="width: 7%;">Resultado</th>
                        <th style="width: 12%;">Responsável</th>
                        <th style="width: 16%;">Observações</th>
                        <th style="width: 11%;">Classificação da NC</th>
                        <th style="width: 17%;">Ação Corretiva Indicada</th>
                        <th style="width: 10%;">Situação da NC</th>
                        <th style="width: 8%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM checklist WHERE id_auditoria = ? ORDER BY id ASC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $id_auditoria);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result && $result->num_rows > 0) {
                        $num = 1;
                        while($row = $result->fetch_assoc()) {
                            // Traduzindo valores dos selects para texto legível
                            $classificacao = '';
                            switch($row['classificacao_nc']){
                                case 'Menor': $classificacao = 'Simples'; break;
                                case 'Maior': $classificacao = 'Média'; break;
                                case 'Crítica': $classificacao = 'Complexa'; break;
                                default: $classificacao = '';
                            }

                            $situacao = '';
                            switch($row['situacao_nc']){
                                case 'pendente': $situacao = 'Aberta'; break;
                                case 'em andamento': $situacao = 'Em Análise'; break;
                                case 'resolvida': $situacao = 'Realizada'; break;
                                default: $situacao = '';
                            }

                            echo "<tr>
                <td>{$num}</td>
                <td>" . htmlspecialchars($row['pergunta']) . "</td>
                <td></td>
                <td>" . htmlspecialchars($row['responsavel']) . "</td>
                <td>" . htmlspecialchars($row['observacoes']) . "</td>
                <td>{$classificacao}</td>
                <td>" . htmlspecialchars($row['acao_corretiva']) . "</td>
                <td>{$situacao}</td>
                <td>
                    <button type='button' class='btn-acao btn-delete' onclick='confirmarExclusao({$row['id']})'>Excluir</button>
                </td>
              </tr>";
                            $num++;
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align:center; color:#ccc;'>Nenhuma pergunta cadastrada para esta auditoria.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="form-actions" style="margin-top: 2rem;">
                <a href="auditoria.php?id_auditoria=<?= $id_auditoria ?>" class="btn-voltar">← Voltar para Auditoria</a>
            </div>
        </form>
    </div>

    <form id="formExcluir" action="../php/checklist_action.php" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="idExcluir">
        <input type="hidden" name="id_auditoria" value="<?= $id_auditoria ?>">
    </form>

    <script>
      
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

      
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja excluir este item?")) {
                document.getElementById('idExcluir').value = id;
                document.getElementById('formExcluir').submit();
            }
        }

        <?php if (isset($_SESSION['mensagem'])): ?>
            alert("<?= $_SESSION['mensagem'] ?>");
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
    </script>
</body>
</html>