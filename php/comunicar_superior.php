<?php
session_start();
include_once "../config/conexao.php";
include "../php/functions.php";
require "../vendor/autoload.php"; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1️⃣ Pegar variáveis do POST
    $id_nc = isset($_POST['id_nc']) ? intval($_POST['id_nc']) : 0;
    $responsavel = isset($_POST['responsavel_imediato']) ? trim($_POST['responsavel_imediato']) : '';
    $email = isset($_POST['email_responsavel_imediato']) ? trim($_POST['email_responsavel_imediato']) : '';

    if ($id_nc > 0 && !empty($responsavel) && !empty($email)) {

        // 2️⃣ Salvar na tabela escalonamento
        if (inserirEscalonamento($conn, $id_nc, $responsavel, $email)) {

            // 3️⃣ Incrementar número de escalonamentos na NC
            // Incrementar número de escalonamentos na NC, tratando NULL como 0
            $stmt = $conn->prepare("
            UPDATE checklist 
            SET escalonamento = COALESCE(escalonamento, 0) + 1 
            WHERE id = ?
            ");
            $stmt->bind_param("i", $id_nc);
            $stmt->execute();
            $stmt->close();


            // 4️⃣ Buscar NC específica usando função existente
            $id_auditoria = 0;
            $ncs = [];
            $stmt = $conn->prepare("SELECT * FROM checklist WHERE id = ?");
            $stmt->bind_param("i", $id_nc);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $nc = $result->fetch_assoc();
                $id_auditoria = $nc['id_auditoria'];
            } else {
                $_SESSION['msg'] = "NC não encontrada!";
                header("Location: ../pages/ncs.php");
                exit();
            }
            $stmt->close();

            // 5️⃣ Buscar dados da auditoria
            $auditoria = buscarAudutoriasIdAuditoria($conn, $id_auditoria);

            // 6️⃣ Enviar e-mail para o superior imediato
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'julianalulinhavecchi@gmail.com';
                $mail->Password   = 'nyvzcnuwwujfkckv';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom($_SESSION['user_login']['email'], 'QA - ' . $_SESSION['user_login']['name']);
                $mail->addAddress($email, $responsavel);

                $mail->isHTML(true);
                $mail->Subject = "Escalonamento - Solicitação de Resolução de NC #{$nc['id']}";
                $mail->Body = "
                    <h2>Escalonamento de Não Conformidade</h2>
                    <p><b>Projeto auditado:</b> {$auditoria['titulo_projeto']}</p>
                    <p><b>Responsável:</b> {$auditoria['responsavel']}</p>
                    <p><b>Superior imediato:</b> {$responsavel}</p>
                    <p><b>Prazo para resolução:</b> {$nc['prazo_resolucao']}</p>
                    <p><b>Descrição detalhada:</b> {$nc['observacoes']}</p>
                ";
                $mail->AltBody = "Escalonamento NC #{$nc['id']}. Verifique no sistema.";
                $mail->send();

                $_SESSION['msg'] = "Escalonamento registrado e e-mail enviado com sucesso!";

            } catch (Exception $e) {
                $_SESSION['msg'] = "Escalonamento salvo, mas erro ao enviar e-mail: {$mail->ErrorInfo}";
            }

        } else {
            $_SESSION['msg'] = "Erro ao registrar escalonamento no banco.";
        }

    } else {
        $_SESSION['msg'] = "Dados inválidos. Verifique os campos.";
    }

    // Redirecionar para a página de NCs da auditoria
    header("Location: ../pages/ncs.php?id_auditoria=" . $id_auditoria);
    exit();
}
