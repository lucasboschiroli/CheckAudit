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
            background: linear-gradient(135deg, #ee4abd, #d136a0);
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
        .btn-delete { background: #ff6b6b; color: #fff; }
        .btn-save-all { background: #35dc80ff; color: #fff; padding: 1.5rem 3rem; font-size: 1.8rem; }
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
        <h3 style="color: #EE4ABD">Checklist da Auditoria: <?= htmlspecialchars($dados_auditoria['titulo_projeto']) ?></h3>


        <h2 class="signup-title">Realizar Auditoria</h2>

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
                        // Situação da NC sempre aberta
                        $situacao = "Aberta";

                        // Prazo de resolução (1 a 30 dias)
                        $prazo_select = "<select name='prazo_resolucao[{$row['id']}]'>";
                        for ($i = 1; $i <= 30; $i++) {
                            $selected = ($row['prazo_resolucao'] == $i) ? "selected" : "";
                            $prazo_select .= "<option value='{$i}' {$selected}>{$i} dias</option>";
                        }
                        $prazo_select .= "</select>";

                        echo "<tr>
                <td>{$num}</td>
                <td>" . htmlspecialchars($row['pergunta']) . "</td>
                <td>
                    <select name='resultado[{$row['id']}]'>
                        <option value='N/A' ".($row['resultado']=='N/A'?'selected':'').">N/A</option>
                        <option value='OK' ".($row['resultado']=='OK'?'selected':'').">Sim</option>
                        <option value='NC' ".($row['resultado']=='NC'?'selected':'').">NC</option>
                    </select>
                </td>
                <td>
                    <input type='text' name='responsavel[{$row['id']}]' value='" . htmlspecialchars($row['responsavel']) . "' placeholder='Nome do responsável'>
                </td>
                <td>
                    <textarea name='observacoes[{$row['id']}]' rows='1' placeholder='Observações'>" . htmlspecialchars($row['observacoes']) . "</textarea>
                </td>
                <td>
                    <select name='classificacao_nc[{$row['id']}]'>
                        <option value=''>Selecione</option>
                        <option value='Baixa-Simples'" . (($row['classificacao_nc'] ?? '')=='Baixa-Simples' ? ' selected' : '') . ">Baixa-Simples</option>
                        <option value='Baixa-Média'" . (($row['classificacao_nc'] ?? '')=='Baixa-Média' ? ' selected' : '') . ">Baixa-Média</option>
                        <option value='Baixa-Complexa'" . (($row['classificacao_nc'] ?? '')=='Baixa-Complexa' ? ' selected' : '') . ">Baixa-Complexa</option>
                        <option value='Média-Simples'" . (($row['classificacao_nc'] ?? '')=='Média-Simples' ? ' selected' : '') . ">Média-Simples</option>
                        <option value='Média-Média'" . (($row['classificacao_nc'] ?? '')=='Média-Média' ? ' selected' : '') . ">Média-Média</option>
                        <option value='Média-Complexa'" . (($row['classificacao_nc'] ?? '')=='Média-Complexa' ? ' selected' : '') . ">Média-Complexa</option>
                        <option value='Alta-Simples'" . (($row['classificacao_nc'] ?? '')=='Alta-Simples' ? ' selected' : '') . ">Alta-Simples</option>
                        <option value='Alta-Média'" . (($row['classificacao_nc'] ?? '')=='Alta-Média' ? ' selected' : '') . ">Alta-Média</option>
                        <option value='Alta-Complexa'" . (($row['classificacao_nc'] ?? '')=='Alta-Complexa' ? ' selected' : '') . ">Alta-Complexa</option>
                        <option value='Urgente-Simples'" . (($row['classificacao_nc'] ?? '')=='Urgente-Simples' ? ' selected' : '') . ">Urgente-Simples</option>
                        <option value='Urgente-Média'" . (($row['classificacao_nc'] ?? '')=='Urgente-Média' ? ' selected' : '') . ">Urgente-Média</option>
                        <option value='Urgente-Complexa'" . (($row['classificacao_nc'] ?? '')=='Urgente-Complexa' ? ' selected' : '') . ">Urgente-Complexa</option>
                    </select>
                </td>

                <td>
                    <textarea name='acao_corretiva[{$row['id']}]' rows='1' placeholder='Ação corretiva'>" . (isset($row['acao_corretiva']) ? htmlspecialchars($row['acao_corretiva']) : '') . "</textarea>
                </td>
                <td>{$prazo_select}</td>
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
                <button type="submit" class="btn-acao btn-save-all">Salvar Todas as Alterações</button>
            </div>
        </form>
    </div>

    <form id="formExcluir" action="../php/checklist_action.php" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="idExcluir">
        <input type="hidden" name="id_auditoria" value="<?= $id_auditoria ?>">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Deseja excluir este item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ee4abd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar',
                background: 'rgba(40,40,40,0.95)',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('idExcluir').value = id;
                    document.getElementById('formExcluir').submit();
                }
            });
        }

        <?php if (isset($_SESSION['mensagem'])): ?>
        Swal.fire({
            title: 'Aviso',
            text: "<?= $_SESSION['mensagem'] ?>",
            icon: 'info',
            confirmButtonColor: '#ee4abd',
            background: 'rgba(40,40,40,0.95)',
            color: '#fff'
        });
        <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>
    </script>

</body>
</html>