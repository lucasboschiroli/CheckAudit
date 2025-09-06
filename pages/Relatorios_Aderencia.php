<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Aderência por Área - CheckAudit</title>
    <style>
        .aderencia-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        .aderencia-title {
            color: #fff;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .resumo-geral {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }

        .resumo-geral h3 {
            color: #fff;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .estatisticas {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            background: rgba(255,255,255,0.05);
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
            display: block;
        }

        .stat-label {
            color: #f1f1f1;
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        .aderencia-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 2rem;
            font-size: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.4);
        }

        .aderencia-table th, .aderencia-table td {
            padding: 1.2rem;
            text-align: center;
        }

        .aderencia-table thead {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .aderencia-table thead th {
            color: #fff;
            font-weight: 600;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .aderencia-table tbody tr {
            background: rgba(255,255,255,0.05);
            transition: background 0.3s ease;
        }

        .aderencia-table tbody tr:nth-child(even) {
            background: rgba(255,255,255,0.08);
        }

        .aderencia-table tbody tr:hover {
            background: rgba(255,255,255,0.15);
        }

        .aderencia-table td {
            color: #f1f1f1;
            font-size: 1.4rem;
        }

        .aderencia-total {
            background: #28a745 !important;
            color: #fff !important;
            font-weight: bold;
        }

        .aderencia-valor {
            font-weight: bold;
            font-size: 1.6rem;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            margin: 0 auto;
            display: inline-block;
            min-width: 80px;
        }

        .aderencia-excelente { background: #28a745; color: #fff; }
        .aderencia-boa { background: #ffc107; color: #000; }
        .aderencia-regular { background: #fd7e14; color: #fff; }
        .aderencia-ruim { background: #dc3545; color: #fff; }

        .btn-voltar {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.4rem;
            cursor: pointer;
            margin-top: 2rem;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-voltar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .detalhes-nc {
            background: rgba(255,255,255,0.05);
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .detalhes-nc h4 {
            color: #fff;
            margin-bottom: 1rem;
        }

        .nc-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nc-item:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .estatisticas {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .aderencia-table {
                font-size: 1.2rem;
            }
            
            .aderencia-table th, .aderencia-table td {
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="aderencia-container">
        <h2 class="aderencia-title">Aderência por Área</h2>

        <?php
        require_once "../config/conexao.php";
        
        // Buscar dados do checklist
        $sql = "SELECT resultado, classificacao_nc FROM checklist WHERE resultado IS NOT NULL AND resultado != ''";
        $result = $conn->query($sql);
        
        $total_perguntas = 0;
        $total_aplicavel = 0; // Total excluindo N/A
        $total_conformes = 0; // OK
        $total_nc = 0; // NC
        $total_na = 0; // N/A
        
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
                        // Contar por tipo de NC
                        if (isset($row['classificacao_nc']) && array_key_exists($row['classificacao_nc'], $nc_por_tipo)) {
                            $nc_por_tipo[$row['classificacao_nc']]++;
                        }
                    }
                }
            }
        }

        // Calcular percentual de aderência
        $percentual_aderencia = ($total_aplicavel > 0) ? round(($total_conformes / $total_aplicavel) * 100, 1) : 0;
        
        // Definir classe da aderência
        $classe_aderencia = 'aderencia-ruim';
        if ($percentual_aderencia >= 90) $classe_aderencia = 'aderencia-excelente';
        elseif ($percentual_aderencia >= 80) $classe_aderencia = 'aderencia-boa';
        elseif ($percentual_aderencia >= 70) $classe_aderencia = 'aderencia-regular';
        ?>

        <!-- Resumo Geral -->
        <div class="resumo-geral">
            <h3>Resumo Geral da Auditoria</h3>
            <div class="estatisticas">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_perguntas; ?></span>
                    <div class="stat-label">Total de Itens</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_aplicavel; ?></span>
                    <div class="stat-label">Itens Aplicáveis</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_conformes; ?></span>
                    <div class="stat-label">Conformes (OK)</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $total_nc; ?></span>
                    <div class="stat-label">Não Conformes</div>
                </div>
            </div>
        </div>

        <!-- Tabela de Aderência -->
        <table class="aderencia-table">
            <thead>
                <tr>
                    <th colspan="4">Aderência por Área</th>
                </tr>
                <tr>
                    <th>Total de Perguntas</th>
                    <th>Sim</th>
                    <th>Não Aplicável</th>
                    <th>Aderência</th>
                </tr>
            </thead>
            <tbody>
                <tr class="aderencia-total">
                    <td><?php echo $total_aplicavel; ?></td>
                    <td><?php echo $total_conformes; ?></td>
                    <td><?php echo $total_na; ?></td>
                    <td>
                        <div class="aderencia-valor <?php echo $classe_aderencia; ?>">
                            <?php echo $percentual_aderencia; ?>%
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Detalhes das Não Conformidades -->
        <?php if ($total_nc > 0): ?>
        <div class="detalhes-nc">
            <h4>Detalhamento das Não Conformidades</h4>
            <?php foreach ($nc_por_tipo as $tipo => $quantidade): ?>
                <?php if ($quantidade > 0): ?>
                <div class="nc-item">
                    <span><?php echo $tipo; ?>:</span>
                    <span><?php echo $quantidade; ?> item(s)</span>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Interpretação do Resultado -->
        <div class="resumo-geral">
            <h3>Interpretação do Resultado</h3>
            <p style="color: #f1f1f1; font-size: 1.3rem;">
                <?php
                if ($percentual_aderencia >= 90) {
                    echo "🎉 <strong>Excelente!</strong> A aderência está acima de 90%, indicando alta conformidade com os critérios avaliados.";
                } elseif ($percentual_aderencia >= 80) {
                    echo "✅ <strong>Boa aderência!</strong> Resultado entre 80-89%, com algumas oportunidades de melhoria.";
                } elseif ($percentual_aderencia >= 70) {
                    echo "⚠️ <strong>Aderência regular.</strong> Resultado entre 70-79%, necessita atenção e ações corretivas.";
                } else {
                    echo "❌ <strong>Aderência baixa.</strong> Resultado abaixo de 70%, requer ações corretivas urgentes.";
                }
                ?>
            </p>
            <p style="color: #ccc; margin-top: 1rem;">
                <small>
                    * O cálculo considera apenas itens aplicáveis. 
                    Itens marcados como "N/A" são excluídos da base de cálculo.
                    <br>
                    Fórmula: (Itens Conformes ÷ Itens Aplicáveis) × 100
                </small>
            </p>
        </div>

        <div style="text-align: center;">
            <a href="checklist.php" class="btn-voltar">← Voltar ao Checklist</a>
        </div>
    </div>

    <script>
        // Adicionar animação aos números
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(element => {
                const finalValue = parseInt(element.textContent);
                element.textContent = '0';
                
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 50);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        element.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        element.textContent = currentValue;
                    }
                }, 30);
            });

            // Animar o percentual de aderência
            const aderenciaElement = document.querySelector('.aderencia-valor');
            if (aderenciaElement) {
                const finalPercentage = parseFloat(aderenciaElement.textContent);
                aderenciaElement.textContent = '0%';
                
                let currentPercentage = 0;
                const increment = finalPercentage / 50;
                const timer = setInterval(() => {
                    currentPercentage += increment;
                    if (currentPercentage >= finalPercentage) {
                        aderenciaElement.textContent = finalPercentage + '%';
                        clearInterval(timer);
                    } else {
                        aderenciaElement.textContent = Math.round(currentPercentage * 10) / 10 + '%';
                    }
                }, 30);
            }
        });
    </script>
</body>
</html>