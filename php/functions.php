<?php
function BuscarUsuarioEmail($conn, $email_login) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email_login);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function buscarAuditorias(){

}

function inserirAuditoria($conn, $titulo, $responsavel, $data, $objetivo, $id_usuario, $email_responsavel){
    $stmt = $conn->prepare("INSERT INTO auditoria (titulo_projeto, responsavel, data_realizacao, objetivo, id_usuario, email_responsavel) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $titulo, $responsavel, $data, $objetivo, $id_usuario, $email_responsavel);
    $stmt->execute();
    $stmt->close();
}

function buscarAudutoriasIdUsuario($conn, $id_usuario){
    $stmt = $conn->prepare("SELECT * FROM auditoria WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $result = $stmt->get_result();
    $auditorias = [];

    while ($row = $result->fetch_assoc()) {
        $auditorias[] = $row;
    }

    $stmt->close();
    return $auditorias;

}

function buscarAudutoriasIdAuditoria($conn, $id_auditoria) {
    $stmt = $conn->prepare("SELECT * FROM auditoria WHERE id_auditoria = ?");
    $stmt->bind_param("i", $id_auditoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function buscarNaoConformidadesIdChecklist($conn, $id_auditoria){
    $resultado = "NC";
    $stmt = $conn->prepare("SELECT * FROM checklist WHERE id_auditoria = ? AND resultado = ?");
    $stmt->bind_param("is", $id_auditoria, $resultado);
    $stmt->execute();
    $result = $stmt->get_result();

    $ncs = [];
    while($row = $result->fetch_assoc()){
        $ncs[] = $row;
    }

    $stmt->close();
    return $ncs;
}

function marcarNCComoResolvida($conn, $id){
    $resultado = "resolvida";
    $stmt = $conn->prepare("UPDATE checklist SET situacao_nc = ? WHERE id = ?");
    $stmt->bind_param("si", $resultado, $id);
    $stmt->execute();
    $stmt->close();
}

function inserirEscalonamento($conn, $id_nc, $responsavel, $email) {
    $sql = "INSERT INTO escalonamento (id_nc, responsavel_imediato, email_responsavel_imediato, data_escalonamento)
            VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Erro no prepare
        return false;
    }

    $stmt->bind_param("iss", $id_nc, $responsavel, $email);

    if ($stmt->execute()) {
        return true; // sucesso
    } else {
        return false; // erro ao executar
    }

}

function buscarEscalonamentosPorNC($conn, $id_nc) {
    $stmt = $conn->prepare("SELECT * FROM escalonamento WHERE id_nc = ? ORDER BY data_escalonamento ASC");
    if (!$stmt) {
        return []; // retorna array vazio se falhar
    }

    $stmt->bind_param("i", $id_nc);
    $stmt->execute();

    $result = $stmt->get_result();
    $escalonamentos = [];

    while ($row = $result->fetch_assoc()) {
        $escalonamentos[] = $row;
    }

    $stmt->close();
    return $escalonamentos;
}






?>