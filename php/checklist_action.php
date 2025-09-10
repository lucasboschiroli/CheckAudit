<?php
session_start();
require_once "../config/conexao.php";

// Ativar relatório de erros para debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id_auditoria = (int)($_POST['id_auditoria'] ?? 0);
    
 
    if ($id_auditoria <= 0) {
        $_SESSION['erro'] = "ID da auditoria é obrigatório!";
        header("Location: ../pages/checklist.php");
        exit;
    }

    try {
        switch ($action) {
            case 'create':
                criarPergunta($conn, $id_auditoria);
                break;
                
            case 'update_all':
                atualizarTodos($conn, $id_auditoria);
                break;
                
            case 'delete':
                excluirItem($conn, $id_auditoria);
                break;
                
            default:
                $_SESSION['erro'] = "Ação não reconhecida!";
                header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
                exit;
        }
    } catch (Exception $e) {
        error_log("Erro em checklist_action.php: " . $e->getMessage());
        $_SESSION['erro'] = "Erro interno: " . $e->getMessage();
        header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
        exit;
    }
} else {
    header("Location: ../pages/checklist.php");
    exit;
}

function criarPergunta($conn, $id_auditoria) {
    $pergunta = trim($_POST['pergunta'] ?? '');
    
    if (empty($pergunta)) {
        $_SESSION['erro'] = "A pergunta não pode estar vazia!";
        header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
        exit;
    }
    
    try {
      
        $check_audit_sql = "SELECT id_auditoria FROM auditoria WHERE id_auditoria = ?";
        $check_stmt = $conn->prepare($check_audit_sql);
        if (!$check_stmt) {
            throw new Exception("Erro ao preparar verificação da auditoria: " . $conn->error);
        }
        
        $check_stmt->bind_param("i", $id_auditoria);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows == 0) {
            $_SESSION['erro'] = "Auditoria não encontrada!";
            header("Location: ../pages/checklist.php");
            exit;
        }
        
        
        $sql = "INSERT INTO checklist (pergunta, id_auditoria, resultado) VALUES (?, ?, 'N/A')";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar inserção: " . $conn->error);
        }
        
        $stmt->bind_param("si", $pergunta, $id_auditoria);
        
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Pergunta adicionada com sucesso!";
        } else {
            throw new Exception("Erro ao executar inserção: " . $stmt->error);
        }
        
        $stmt->close();
        $check_stmt->close();
        
    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro ao adicionar pergunta: " . $e->getMessage();
    }
    
    header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
    exit;
}

function atualizarTodos($conn, $id_auditoria) {
    $resultado = $_POST['resultado'] ?? [];
    $responsavel = $_POST['responsavel'] ?? [];
    $observacoes = $_POST['observacoes'] ?? [];
    $classificacao_nc = $_POST['classificacao_nc'] ?? [];
    $acao_corretiva = $_POST['acao_corretiva'] ?? [];
    $situacao_nc = $_POST['situacao_nc'] ?? [];
    $prazo_resolucao = $_POST['prazo_resolucao'] ?? []; // NOVO CAMPO

    if (empty($resultado)) {
        $_SESSION['erro'] = "Nenhum item para atualizar!";
        header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
        exit;
    }

    try {
        $conn->begin_transaction();

        foreach ($resultado as $id => $valor) {
            $id = (int)$id;

            // Verifica se o item existe
            $check_sql = "SELECT id FROM checklist WHERE id = ? AND id_auditoria = ?";
            $check_stmt = $conn->prepare($check_sql);
            if (!$check_stmt) {
                throw new Exception("Erro ao preparar verificação: " . $conn->error);
            }

            $check_stmt->bind_param("ii", $id, $id_auditoria);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                $check_stmt->close();
                continue;
            }
            $check_stmt->close();

            // Captura os valores enviados
            $resp = isset($responsavel[$id]) ? trim($responsavel[$id]) : '';
            $obs = isset($observacoes[$id]) ? trim($observacoes[$id]) : '';
            $class_nc = isset($classificacao_nc[$id]) ? trim($classificacao_nc[$id]) : '';
            $acao_corr = isset($acao_corretiva[$id]) ? trim($acao_corretiva[$id]) : '';
            $sit_nc = isset($situacao_nc[$id]) ? trim($situacao_nc[$id]) : 'pendente';
            $prazo = isset($prazo_resolucao[$id]) ? (int)$prazo_resolucao[$id] : null; // CAPTURA PRAZO

            // Atualiza os dados
            $sql = "UPDATE checklist SET 
                        resultado = ?, 
                        responsavel = ?, 
                        observacoes = ?, 
                        classificacao_nc = ?, 
                        acao_corretiva = ?, 
                        situacao_nc = ?, 
                        prazo_resolucao = ? 
                    WHERE id = ? AND id_auditoria = ?";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Erro ao preparar atualização: " . $conn->error);
            }

            $stmt->bind_param(
                "ssssssiii",
                $valor,
                $resp,
                $obs,
                $class_nc,
                $acao_corr,
                $sit_nc,
                $prazo,
                $id,
                $id_auditoria
            );

            if (!$stmt->execute()) {
                throw new Exception("Erro ao executar atualização do item $id: " . $stmt->error);
            }

            $stmt->close();
        }

        $conn->commit();
        $_SESSION['mensagem'] = "Todas as alterações foram salvas com sucesso!";

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['erro'] = "Erro ao salvar alterações: " . $e->getMessage();
    }

    header("Location: ../pages/realizar_auditoria.php?id_auditoria=" . $id_auditoria);
    exit;
}

function excluirItem($conn, $id_auditoria) {
    $id = (int)($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        $_SESSION['erro'] = "ID do item é obrigatório!";
        header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
        exit;
    }
    
    try {
        $sql = "DELETE FROM checklist WHERE id = ? AND id_auditoria = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar exclusão: " . $conn->error);
        }
        
        $stmt->bind_param("ii", $id, $id_auditoria);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['mensagem'] = "Item excluído com sucesso!";
            } else {
                $_SESSION['erro'] = "Item não encontrado ou não pertence a esta auditoria!";
            }
        } else {
            throw new Exception("Erro ao executar exclusão: " . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro ao excluir item: " . $e->getMessage();
    }
    
    header("Location: ../pages/checklist.php?id_auditoria=" . $id_auditoria);
    exit;
}
?>