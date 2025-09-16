<?php
session_start();
require "../vendor/autoload.php";
include "../php/functions.php";
include "../config/conexao.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Receber IDs
$id_auditoria = $_GET['id_auditoria'] ?? null;
$id_nc = $_GET['id_nc'] ?? null;

if (!$id_nc || !$id_auditoria) {
    die("ID da NC ou da auditoria não informado.");
}

// Buscar NC e auditoria
$nc = buscarNaoConformidadePorId($conn, $id_nc) ?: die("Não conformidade não encontrada.");
$auditoria = buscarAudutoriasIdAuditoria($conn, $nc['id_auditoria']);

$escalonamento = $nc['escalonamento'] ?? 0;
$data_primeira = date('d/m/Y \à\s H:i', strtotime($nc['created_at']));
$prazo_resolucao = $nc['prazo_resolucao'];

// Buscar histórico de escalonamento
$escalonamentos = [];
$stmt = $conn->prepare("SELECT * FROM escalonamento WHERE id_nc = ? ORDER BY data_escalonamento ASC");
$stmt->bind_param("i", $id_nc);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $escalonamentos[] = $row;
}
$stmt->close();

// Preparar HTML do histórico
$historico_escalonamento = '';
if (!empty($escalonamentos)) {
    $historico_escalonamento .= '<h3 style="font-family: Arial, sans-serif; color:#dc3545;">Histórico de Escalonamentos:</h3><ul style="font-family: Arial, sans-serif; color:#000;">';
    foreach ($escalonamentos as $esc) {
        $data = date('d/m/Y H:i', strtotime($esc['data_escalonamento']));
        $historico_escalonamento .= "<li>{$esc['responsavel_imediato']} ({$esc['email_responsavel_imediato']}) - {$data}</li>";
    }
    $historico_escalonamento .= '</ul>';
}

// PHPMailer
$mail = new PHPMailer(true);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviando E-mail - CheckAudit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'julianalulinhavecchi@gmail.com';
        $mail->Password   = 'nyvzcnuwwujfkckv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($_SESSION['user_login']['email'], 'QA - ' . $_SESSION['user_login']['username']);
        $mail->addAddress($auditoria['email_responsavel'], $auditoria['responsavel']);
        $mail->isHTML(true);
        $mail->Subject = 'Solicitação de Resolução de Não Conformidade';

        $mail->Body = "
<div style='font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin:0 auto; padding:20px; background-color:#f9f9f9; border:1px solid #ddd; border-radius:8px; color:#333;'>
    <h2 style='color:#dc3545; text-align:center;'>Solicitação de Resolução de Não Conformidade</h2>
    
    <div style='margin-bottom:15px;'>
        <p><strong>Projeto:</strong> {$auditoria['titulo_projeto']}</p>
        <p><strong>Responsável:</strong> {$auditoria['responsavel']}</p>
        <p><strong>Data da 1ª Solicitação:</strong> {$data_primeira}</p>
        <p><strong>Prazo de Resolução:</strong> {$prazo_resolucao} dias</p>
        <p><strong>Escalonamento:</strong> {$escalonamento}</p>
        <p><strong>Responsável QA:</strong>  {$_SESSION['user_login']['username']}</p>
    </div>

    <div style='margin-bottom:15px;'>
        <h3 style='color:#dc3545; margin-bottom:5px;'>Descrição:</h3>
        <p style='margin:0 0 10px 0;'>{$nc['pergunta']}</p>

        <h3 style='color:#dc3545; margin-bottom:5px;'>Classificação:</h3>
        <p style='margin:0 0 10px 0;'>{$nc['classificacao_nc']}</p>

        <h3 style='color:#dc3545; margin-bottom:5px;'>Ação Corretiva:</h3>
        <p style='margin:0 0 10px 0;'>{$nc['acao_corretiva']}</p>

        <h3 style='color:#dc3545; margin-bottom:5px;'>Observações:</h3>
        <p style='margin:0 0 10px 0;'>{$nc['observacoes']}</p>
    </div>

    {$historico_escalonamento}

    <p style='margin-top:20px; color:#555; font-size:13px; text-align:center;'>Você tem 24 horas úteis para contestação.</p>
</div>
";


        $mail->send();
        marcarNCComoComunicada($conn, $id_nc);

        echo "
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        html: 'E-mail enviado com sucesso para <b>{$auditoria['responsavel']}</b> ({$auditoria['email_responsavel']})!',
        confirmButtonText: 'OK',
        background: '#1b1b1b',
        color: '#fff',
        confirmButtonColor: '#28a745',
        backdrop: 'rgba(0,0,0,0.85)'
    }).then(() => {
        window.location.href = '../pages/ncs.php?id_auditoria={$id_auditoria}';
    });
    ";
    } catch (Exception $e) {
        echo "
    Swal.fire({
        icon: 'error',
        title: 'Erro!',
        html: 'Falha ao enviar e-mail: <b>{$mail->ErrorInfo}</b>',
        confirmButtonText: 'OK',
        background: '#1b1b1b',
        color: '#fff',
        confirmButtonColor: '#dc3545',
        backdrop: 'rgba(0,0,0,0.85)'
    }).then(() => {
        window.history.back();
    });
    ";
    }
    ?>
</script>

</body>
</html>
