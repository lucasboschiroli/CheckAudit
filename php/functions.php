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

function inserirAuditoria($conn, $titulo, $responsavel, $data, $objetivo, $id_usuario){
    $stmt = $conn->prepare("INSERT INTO auditoria (titulo_projeto, responsavel, data_realizacao, objetivo, id_usuario) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $titulo, $responsavel, $data, $objetivo, $id_usuario);
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




?>