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
    <style>
        /* Estilos para a seção de aderência */
        .aderencia-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem 0;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .aderencia-title {
            color: #fff;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .aderencia-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .aderencia-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .stat-card {
            background: rgba(255,255,255,0.05);
            padding: 1.2rem;
            border-radius: 8px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: #28a745;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #f1f1f1;
            font-size: 1rem;
            font-weight: 500;
        }

        .aderencia-display {
            text-align: center;
            padding: 2rem;
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .aderencia-valor {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 12px;
            display: inline-block;
            min-width: 150px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .aderencia-excelente { 
            background: linear-gradient(135deg, #28a745, #20c997); 
            color: #fff; 
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .aderencia-boa { 
            background: linear-gradient(135deg, #ffc107, #fd7e14); 
            color: #000; 
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
        }

        .aderencia-regular { 
            background: linear-gradient(135deg, #fd7e14, #e55353); 
            color: #fff; 
            box-shadow: 0 8px 25px rgba(253, 126, 20, 0.4);
        }

        .aderencia-ruim { 
            background: linear-gradient(135deg, #dc3545, #c82333); 
            color: #fff; 
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        }

        .aderencia-status {
            color: #f1f1f1;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .aderencia-descricao {
            color: #ccc;
            font-size: 1rem;
            line-height: 1.5;
        }

        .aderencia-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
            font-size: 1.3rem;
            border-radius: 8px;
            overflow: hidden;
            background: rgba(255,255,255,0.05);
        }

        .aderencia-table th {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
        }

        .aderencia-table td {
            padding: 1rem;
            text-align: center;
            color: #f1f1f1;
            background: rgba(255,255,255,0.03);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .aderencia-table .total-row {
            background: rgba(40, 167, 69, 0.2);
            font-weight: bold;
        }

        .detalhes-nc {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .detalhes-nc h4 {
            color: #dc3545;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .nc-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            color: #f1f1f1;
            border-bottom: 1px solid rgba(220, 53, 69, 0.2);
        }

        .nc-item:last-child {
            border-bottom: none;
        }

        .nc-badge {
            background: rgba(220, 53, 69, 0.8);
            color: #fff;
            padding: 0.2rem 0.8rem;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .formula-explicacao {
            background: rgba(255,255,255,0.03);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            border-left: 4px solid #28a745;
        }

        .formula-explicacao small {
            color: #ccc;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        @media (max-width: 1024px) {
            .aderencia-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .aderencia-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .aderencia-valor {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .aderencia-stats {
                grid-template-columns: 1fr;
            }
            
            .aderencia-valor {
                font-size: 2.5rem;
            }
        }

        /* Animação de entrada */
        .aderencia-section {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: fadeInUp 0.6s ease-out;
            animation-delay: calc(var(--delay) * 0.1s);
        }
    </style>
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

    // Buscar dados do checklist para calcular aderência (específico desta auditoria)
    $sql = "SELECT resultado, classificacao_nc FROM checklist WHERE id_auditoria = ? AND resultado IS NOT NULL AND resultado != ''";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_auditoria);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total_perguntas = 0;
    $total_aplicavel = 0; 
    $total_conformes = 0;
    $total_nc = 0; 
    $total_na = 0; 
    
    $nc_por_tipo = [
        'Menor' => 0,
        'Maior' => 0,
        'Crítica' => 0,
        'Observação' => 0
    ];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_perguntas++;
            
            if ($row['resultado'] == 'N/A') {
                $total_na++;
            } else {
                $total_aplicavel++;
                if ($row['resultado'] == 'OK') {
                    $total_conformes++;
                } elseif ($row['resultado'] == 'NC') {
                    $total_nc++;
        
                    if (isset($row['classificacao_nc']) && array_key_exists($row['classificacao_nc'], $nc_por_tipo)) {
                        $nc_por_tipo[$row['classificacao_nc']]++;
                    }
                }
            }
        }
    }


    $percentual_aderencia = ($total_aplicavel > 0) ? round(($total_conformes / $total_aplicavel) * 100, 1) : 0;
   
    $classe_aderencia = 'aderencia-ruim';
    $status_aderencia = 'Aderência Baixa';
    $descricao_aderencia = 'Resultado abaixo de 70%, requer ações corretivas urgentes.';

    if ($percentual_aderencia >= 90) {
        $classe_aderencia = 'aderencia-excelente';
        $status_aderencia = 'Excelente Aderência';
        $descricao_aderencia = 'Aderência acima de 90%, indicando alta conformidade com os critérios avaliados.';
    } elseif ($percentual_aderencia >= 80) {
        $classe_aderencia = 'aderencia-boa';
        $status_aderencia = 'Boa Aderência';
        $descricao_aderencia = 'Resultado entre 80-89%, com algumas oportunidades de melhoria.';
    } elseif ($percentual_aderencia >= 70) {
        $classe_aderencia = 'aderencia-regular';
        $status_aderencia = 'Aderência Regular';
        $descricao_aderencia = 'Resultado entre 70-79%, necessita atenção e ações corretivas.';
    }
?>
    <main class="auditoria-detalhes">
        <h1 class="auditoria-titulo">Auditoria <?= $dados_auditoria['titulo_projeto'] ?></h1>

        <div class="auditoria-info-acoes">
            <div class="auditoria-info">
                <h3>Dados da auditoria</h3>
                <p><strong>Responsável:</strong> <?= $dados_auditoria['responsavel'] ?></p>
                <p><strong>Data de realização:</strong> <?= $dados_auditoria['data_realizacao'] ?></p>
                <p><strong>Objetivo:</strong> <?= $dados_auditoria['objetivo'] ?></p>
            </div>

            <div class="auditoria-acoes">
                <a href="checklist.php?id_auditoria=<?= $id_auditoria ?>" class="signup-btn" style="text-decoration: none; display: inline-block;">
                    <i class="fa-solid fa-square-plus" style="color: #ffffff;"></i> Criar Checklist
                </a>
                <a href="realizar_auditoria.php?id_auditoria=<?= $id_auditoria ?>" class="signup-btn" style="text-decoration: none; display: inline-block;">
                    <i class="fa-solid fa-list-check" style="color: #ffffff;"></i> Realizar Auditoria
                </a>
                <a href="" class="signup-btn-pink" style="text-decoration: none; display: inline-block;">
                    <i class="fa-solid fa-circle-info" style="color: #ffffff;"></i> Gerenciar Não Conformidade
                </a>

            </div>
        </div>

        <?php if ($total_perguntas > 0): ?>
        <!-- Seção de Aderência -->
        <div class="aderencia-section">
            <h2 class="aderencia-title">
                <i class="fas fa-chart-pie"></i> Cálculo de Aderência 
            </h2>
            
            <div class="aderencia-grid">
                <div>
                    <!-- Estatísticas -->
                    <div class="aderencia-stats">
                        <div class="stat-card" style="--delay: 1">
                            <span class="stat-number" data-value="<?= $total_perguntas ?>"><?= $total_perguntas ?></span>
                            <div class="stat-label">Total de Itens</div>
                        </div>
                        <div class="stat-card" style="--delay: 2">
                            <span class="stat-number" data-value="<?= $total_aplicavel ?>"><?= $total_aplicavel ?></span>
                            <div class="stat-label">Itens Aplicáveis</div>
                        </div>
                        <div class="stat-card" style="--delay: 3">
                            <span class="stat-number" data-value="<?= $total_conformes ?>"><?= $total_conformes ?></span>
                            <div class="stat-label">Conformes (OK)</div>
                        </div>
                        <div class="stat-card" style="--delay: 4">
                            <span class="stat-number" data-value="<?= $total_nc ?>"><?= $total_nc ?></span>
                            <div class="stat-label">Não Conformes</div>
                        </div>
                    </div>

                    <!-- Tabela resumo -->
                    <table class="aderencia-table">
                        <thead>
                            <tr>
                                <th>Total de Perguntas</th>
                                <th>Sim</th>
                                <th>Não Aplicável</th>
                                <th>Aderência</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="total-row">
                                <td><?= $total_aplicavel ?></td>
                                <td><?= $total_conformes ?></td>
                                <td><?= $total_na ?></td>
                                <td><strong><?= $percentual_aderencia ?>%</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Display principal da aderência -->
                <div class="aderencia-display">
                    <div class="aderencia-valor <?= $classe_aderencia ?>" data-percentage="<?= $percentual_aderencia ?>">
                        <?= $percentual_aderencia ?>%
                    </div>
                    <div class="aderencia-status"><?= $status_aderencia ?></div>
                    <div class="aderencia-descricao"><?= $descricao_aderencia ?></div>
                </div>
            </div>

         
            <?php if ($total_nc > 0): ?>
            
            <?php endif; ?>

            <div class="formula-explicacao">
                <small>
                    <strong>Fórmula de Cálculo:</strong> (Itens Conformes ÷ Itens Aplicáveis) × 100<br>
                    <strong>Observação:</strong> Itens marcados como "N/A" são excluídos da base de cálculo por não serem aplicáveis ao contexto auditado.
                </small>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animar números das estatísticas
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(element => {
                const finalValue = parseInt(element.dataset.value);
                element.textContent = '0';
                
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 30) || 1;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        element.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        element.textContent = currentValue;
                    }
                }, 50);
            });

            // Animar percentual de aderência
            const aderenciaElement = document.querySelector('.aderencia-valor');
            if (aderenciaElement) {
                const finalPercentage = parseFloat(aderenciaElement.dataset.percentage);
                aderenciaElement.textContent = '0%';
                
                let currentPercentage = 0;
                const increment = finalPercentage / 30;
                const timer = setInterval(() => {
                    currentPercentage += increment;
                    if (currentPercentage >= finalPercentage) {
                        aderenciaElement.textContent = finalPercentage + '%';
                        clearInterval(timer);
                    } else {
                        aderenciaElement.textContent = Math.round(currentPercentage * 10) / 10 + '%';
                    }
                }, 50);
            }
        });
    </script>

<?php include "../includes/footer.php";?>
</body>

</html>