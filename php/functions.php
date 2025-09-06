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



?>