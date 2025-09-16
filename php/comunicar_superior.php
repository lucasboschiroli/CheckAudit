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
            $stmt = $conn->prepare("
                UPDATE checklist 
                SET escalonamento = COALESCE(escalonamento, 0) + 1 
                WHERE id = ?
            ");
            $stmt->bind_param("i", $id_nc);
            $stmt->execute();
            $stmt->close();

            // 4️⃣ Buscar NC específica
            $stmt = $conn->prepare("SELECT * FROM checklist WHERE id = ?");
            $stmt->bind_param("i", $id_nc);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
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

            // 6️⃣ Buscar histórico de escalonamento
            $escalonamentos = [];
            $stmt = $conn->prepare("SELECT * FROM escalonamento WHERE id_nc = ? ORDER BY data_escalonamento ASC");
            $stmt->bind_param("i", $id_nc);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $escalonamentos[] = $row;
            }
            $stmt->close();

            $historico_escalonamento = '';
            if (!empty($escalonamentos)) {
                $historico_escalonamento .= '<h3>Histórico de Escalonamentos:</h3><ul style="padding-left:20px;">';
                foreach ($escalonamentos as $esc) {
                    $data = date('d/m/Y H:i', strtotime($esc['data_escalonamento']));
                    $historico_escalonamento .= "<li>{$esc['responsavel_imediato']} ({$esc['email_responsavel_imediato']}) - {$data}</li>";
                }
                $historico_escalonamento .= '</ul>';
            }

            // 7️⃣ Enviar e-mail com PHPMailer
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
                $mail->Subject = "Escalonamento - NC #{$nc['id']}";

                $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height:1.6; max-width:600px; margin:auto; color:#000; font-size:14px;'>
                    <h2 style='color:#dc3545; text-align:center;'>Escalonamento de Não Conformidade</h2>
                    <p><b>Projeto auditado:</b> {$auditoria['titulo_projeto']}</p>
                    <p><b>Responsável pelo projeto:</b> {$auditoria['responsavel']}</p>
                    <p><b>NC #:</b> {$nc['id']}</p>
                    <p><b>Superior imediato:</b> {$responsavel}</p>
                    <p><b>Prazo para resolução:</b> {$nc['prazo_resolucao']} dias</p>
                    <hr>
                    <h3>Descrição:</h3>
                    <p>{$nc['observacoes']}</p>
                    {$historico_escalonamento}
                    <p style='margin-top:20px; color:#555; font-size:13px;'>Você tem 24 horas úteis para contestação.</p>
                </div>
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

    marcarNCComoEscalonada($conn,$id_nc );
    // Redirecionar para a página de NCs da auditoria
    header("Location: ../pages/ncs.php?id_auditoria=" . $id_auditoria);
    exit();
}
