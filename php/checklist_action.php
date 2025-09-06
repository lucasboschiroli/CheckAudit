<?php
session_start();
require_once "../config/conexao.php";


$method = $_SERVER['REQUEST_METHOD'];
$action = '';

if ($method == 'POST') {
    $action = $_POST['action'] ?? '';
} else if ($method == 'GET') {
    $action = $_GET['action'] ?? '';
}

if (!empty($action)) {
    switch($action) {
        case 'create':
            
            $pergunta = trim($_POST['pergunta']);
            if (!empty($pergunta)) {
                $stmt = $conn->prepare("INSERT INTO checklist (pergunta, resultado, responsavel, observacoes, classificacao_nc, acao_corretiva, situacao_nc) VALUES (?, 'N/A', '', '', '', '', 'Pendente')");
                $stmt->bind_param("s", $pergunta);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Pergunta adicionada com sucesso!";
                } else {
                    $_SESSION['error'] = "Erro ao adicionar pergunta.";
                }
                $stmt->close();
            }
            break;
            
        case 'create_complete':
           
            $pergunta = trim($_POST['pergunta']);
            $resultado = $_POST['resultado'] ?? 'N/A';
            $responsavel = trim($_POST['responsavel'] ?? '');
            $observacoes = trim($_POST['observacoes'] ?? '');
            $classificacao_nc = trim($_POST['classificacao_nc'] ?? '');
            $acao_corretiva = trim($_POST['acao_corretiva'] ?? '');
            $situacao_nc = $_POST['situacao_nc'] ?? 'Pendente';
            
            if (!empty($pergunta)) {
                $stmt = $conn->prepare("INSERT INTO checklist (pergunta, resultado, responsavel, observacoes, classificacao_nc, acao_corretiva, situacao_nc) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $pergunta, $resultado, $responsavel, $observacoes, $classificacao_nc, $acao_corretiva, $situacao_nc);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Item adicionado com sucesso!";
                } else {
                    $_SESSION['error'] = "Erro ao adicionar item.";
                }
                $stmt->close();
            }
            break;
            
        case 'update':
           
            $id = intval($method == 'POST' ? $_POST['id'] : $_GET['id']);
            $resultado = ($method == 'POST' ? $_POST['resultado'] : $_GET['resultado']) ?? 'N/A';
            $responsavel = trim(($method == 'POST' ? $_POST['responsavel'] : $_GET['responsavel']) ?? '');
            $observacoes = trim(($method == 'POST' ? $_POST['observacoes'] : $_GET['observacoes']) ?? '');
            $classificacao_nc = trim(($method == 'POST' ? $_POST['classificacao_nc'] : $_GET['classificacao_nc']) ?? '');
            $acao_corretiva = trim(($method == 'POST' ? $_POST['acao_corretiva'] : $_GET['acao_corretiva']) ?? '');
            $situacao_nc = ($method == 'POST' ? $_POST['situacao_nc'] : $_GET['situacao_nc']) ?? 'Pendente';
            
            $stmt = $conn->prepare("UPDATE checklist SET resultado = ?, responsavel = ?, observacoes = ?, classificacao_nc = ?, acao_corretiva = ?, situacao_nc = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $resultado, $responsavel, $observacoes, $classificacao_nc, $acao_corretiva, $situacao_nc, $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Item atualizado com sucesso!";
            } else {
                $_SESSION['error'] = "Erro ao atualizar item.";
            }
            $stmt->close();
            break;
            
        case 'update_all':
           
            $sucessos = 0;
            $erros = 0;
            
            if (isset($_POST['resultado']) && is_array($_POST['resultado'])) {
                foreach ($_POST['resultado'] as $id => $resultado) {
                    $id = intval($id);
                    $responsavel = trim($_POST['responsavel'][$id] ?? '');
                    $observacoes = trim($_POST['observacoes'][$id] ?? '');
                    $classificacao_nc = trim($_POST['classificacao_nc'][$id] ?? '');
                    $acao_corretiva = trim($_POST['acao_corretiva'][$id] ?? '');
                    $situacao_nc = $_POST['situacao_nc'][$id] ?? 'Pendente';
                    
                    $stmt = $conn->prepare("UPDATE checklist SET resultado = ?, responsavel = ?, observacoes = ?, classificacao_nc = ?, acao_corretiva = ?, situacao_nc = ? WHERE id = ?");
                    $stmt->bind_param("ssssssi", $resultado, $responsavel, $observacoes, $classificacao_nc, $acao_corretiva, $situacao_nc, $id);
                    
                    if ($stmt->execute()) {
                        $sucessos++;
                    } else {
                        $erros++;
                    }
                    $stmt->close();
                }
            }
            
            if ($sucessos > 0) {
                $_SESSION['success'] = "Atualizados {$sucessos} itens com sucesso!";
            } else {
                $_SESSION['info'] = "Nenhuma alteração foi feita.";
            }
            if ($erros > 0) {
                $_SESSION['error'] = "Erro ao atualizar {$erros} itens.";
            }
            break;
            
        case 'delete':
           
            $id = intval($method == 'POST' ? $_POST['id'] : $_GET['id']);
            
            $stmt = $conn->prepare("DELETE FROM checklist WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Item excluído com sucesso!";
            } else {
                $_SESSION['error'] = "Erro ao excluir item.";
            }
            $stmt->close();
            break;
            
        default:
            $_SESSION['error'] = "Ação inválida.";
            break;
    }
}


header("Location: ../pages/checklist.php");
exit();
?>