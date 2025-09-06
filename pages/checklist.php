<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Checklist - CheckAudit</title>
    <style>
        .checklist-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 2rem;
            font-size: 1.4rem; /* Reduzido para acomodar mais colunas */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.4);
        }
        .checklist-table th, .checklist-table td {
            padding: 1rem; /* Reduzido para economizar espaço */
            text-align: left;
        }
        .checklist-table thead {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .checklist-table thead th {
            color: #fff;
            font-weight: 600;
            font-size: 1.4rem; /* Reduzido */
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
            padding: 0.8rem; /* Reduzido */
            border-radius: 8px;
            border: none;
            font-size: 1.3rem; /* Reduzido */
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
            padding: 0.8rem 1.2rem; /* Reduzido */
            border-radius: 8px;
            cursor: pointer;
            border: none;
            font-weight: 600;
            font-size: 1.3rem; /* Reduzido */
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

        /* Formulário de nova pergunta completa */
        .form-completo {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .form-completo h3 {
            color: #fff;
            margin-bottom: 1.5rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 2fr 1fr 2fr 1fr; /* Atualizado para 7 colunas */
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .form-row > div {
            display: flex;
            flex-direction: column;
            min-height: 110px; /* Altura mínima padronizada para todos os grupos */
        }
        .form-row label {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: block;
            font-size: 1.4rem; /* Tamanho padronizado */
            min-height: 2.8rem; /* Altura mínima para padronizar */
            line-height: 1.4; /* Espaçamento entre linhas */
        }
        .form-row input,
        .form-row select,
        .form-row textarea {
            padding: 1rem;
            border-radius: 8px;
            border: none;
            font-size: 1.5rem;
            background: rgba(255,255,255,0.15);
            color: #fff;
            flex: 1; /* Faz o campo ocupar o espaço restante */
        }
        .form-row textarea {
            resize: vertical;
            min-height: 60px; /* Altura mínima para textareas */
        }
        .form-actions {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        /* Responsividade para telas menores */
        @media (max-width: 1200px) {
            .checklist-table {
                font-size: 1.2rem;
            }
            .checklist-table th, .checklist-table td {
                padding: 0.8rem;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container" style="max-width: 1400px;"> 
        <h2 class="signup-title">Checklist da Auditoria</h2>

        <form action="../php/checklist_action.php" method="POST" style="margin-bottom:2rem;">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="pergunta_simples">Adicionar Pergunta</label>
                <input type="text" id="pergunta_simples" name="pergunta" placeholder="Digite a pergunta">
            </div>
            <button type="submit" class="signup-btn">Adicionar Pergunta</button>
        </form>
                
                
       
        <form action="../php/checklist_action.php" method="POST" id="formSalvarTudo">
            <input type="hidden" name="action" value="update_all">
            
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
                    require_once "../config/conexao.php";
                    $sql = "SELECT * FROM checklist ORDER BY id ASC";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        $num = 1;
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$num}</td>
                                    <td>{$row['pergunta']}</td>
                                    <td>
                                        <select name='resultado[{$row['id']}]'>
                                            <option value='N/A' ".($row['resultado']=='N/A'?'selected':'').">N/A</option>
                                            <option value='OK' ".($row['resultado']=='OK'?'selected':'').">Sim</option>
                                            <option value='NC' ".($row['resultado']=='NC'?'selected':'').">NC</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type='text' name='responsavel[{$row['id']}]' value='{$row['responsavel']}' placeholder='Nome do responsável'>
                                    </td>
                                    <td>
                                        <textarea name='observacoes[{$row['id']}]' rows='1' placeholder='Observações'>{$row['observacoes']}</textarea>
                                    </td>
                                    <td>
                                        <select name='classificacao_nc[{$row['id']}]'>
                                            <option value=''>Selecione</option>
                                            <option value='Menor' ".(isset($row['classificacao_nc']) && $row['classificacao_nc']=='Menor'?'selected':'').">Simples</option>
                                            <option value='Maior' ".(isset($row['classificacao_nc']) && $row['classificacao_nc']=='Maior'?'selected':'').">Media</option>
                                            <option value='Crítica' ".(isset($row['classificacao_nc']) && $row['classificacao_nc']=='Crítica'?'selected':'').">Complexa</option>
                                           
                                        </select>
                                    </td>
                                    <td>
                                        <textarea name='acao_corretiva[{$row['id']}]' rows='1' placeholder='Ação corretiva'>".(isset($row['acao_corretiva']) ? $row['acao_corretiva'] : '')."</textarea>
                                    </td>
                                    <td>
                                        <select name='situacao_nc[{$row['id']}]'>
                                            <option value='Pendente' ".(isset($row['situacao_nc']) && $row['situacao_nc']=='Pendente'?'selected':'').">Aberta</option>
                                            <option value='Em Andamento' ".(isset($row['situacao_nc']) && $row['situacao_nc']=='Em Andamento'?'selected':'').">Em Análise</option>
                                            <option value='Concluída' ".(isset($row['situacao_nc']) && $row['situacao_nc']=='Concluída'?'selected':'').">Realizada</option>
                                        
                                        </select>
                                    </td>
                                    <td>
                                        <button type='button' class='btn-acao btn-delete' onclick='confirmarExclusao({$row['id']})'>Excluir</button>
                                    </td>
                                  </tr>";
                            $num++;
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align:center; color:#ccc;'>Nenhuma pergunta cadastrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div class="form-actions" style="margin-top: 2rem;">
                <button type="submit" class="btn-acao btn-save-all">Salvar Todas as Alterações</button>
            </div>
        </form>
    </div>


    <form id="formExcluir" action="../php/checklist_action.php" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="idExcluir">
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
    </script>
</body>
</html>