<?php
session_start();
require "../vendor/autoload.php";   // PHPMailer
include "../php/functions.php";
include "../config/conexao.php";          // suas funções

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$id_auditoria = $_GET['id_auditoria'];

// Buscar todas as NCs dessa auditoria
$ncs = buscarNaoConformidadesIdChecklist($conn, $id_auditoria);

// Buscar dados da auditoria
$auditoria = buscarAudutoriasIdAuditoria($conn, $id_auditoria);


// Percorrer cada NC e enviar e-mail
foreach ($ncs as $nc) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'julianalulinhavecchi@gmail.com'; // seu e-mail
        $mail->Password   = 'nyvzcnuwwujfkckv';          // senha de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remetente
        $mail->setFrom($_SESSION['user_login']['email'] , 'QA - ' . $_SESSION['user_login']['name']);

        // Destinatário
        $mail->addAddress($auditoria['email_responsavel'], $auditoria['responsavel']);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Solicitação de Resolução de Não Conformidade';
        $mail->Body = "
            <h2>Solicitação de Resolução de Não Conformidade</h2>
            <p><b>Projeto auditado:</b> {$auditoria['titulo_projeto']}</p>
            <p><b>Responsável:</b> {$auditoria['responsavel']}</p>
            <p><b>Prazo para resolução:</b> {$nc['prazo_resolucao']}</p>
            <p><b>Data da primeira solicitação:</b> {$nc['data_primeira']}</p>
            <p><b>Número de escalonamento:</b> {$nc['num_escalonamento']}</p>
            <p><b>Responsável QA:</b> {$nc['responsavel_qa']}</p>
            <p><b>Prazo para contestação:</b> {$nc['prazo_contestacao']}</p>
            <p><b>Ação corretiva indicada:</b> {$nc['acao_corretiva']}</p>
            <p><b>Classificação da NC:</b> {$nc['classificacao']}</p>
            <p><b>Descrição detalhada:</b> {$nc['descricao']}</p>
            <p><b>Histórico de escalonamentos:</b> {$nc['historico']}</p>
            <p><b>Observações adicionais:</b> {$nc['observacoes']}</p>
        ";
        $mail->AltBody = "Nova NC: {$nc['descricao']}. Ver detalhes no sistema.";

        $mail->send();
        echo "E-mail enviado com sucesso para {$responsavel['nome']} ({$responsavel['email']})!<br>";
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}<br>";
    }
}
?>

