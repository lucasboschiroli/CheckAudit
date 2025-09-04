<?php
session_start();
require_once '../config/conexao.php';


if (!$conn instanceof mysqli) {
    error_log("Database connection is not valid in register.php");
    header('Location: cadastro.html?error=system_error');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header('Location: cadastro.html?error=empty_fields');
        exit();
    }
    

    if ($password !== $confirm_password) {
        header('Location: cadastro.html?error=password_mismatch');
        exit();
    }
    
 
    if (strlen($password) < 6) {
        header('Location: cadastro.html?error=password_too_short');
        exit();
    }
    

    if (strlen($username) < 3 || strlen($username) > 50) {
        header('Location: cadastro.html?error=invalid_username_length');
        exit();
    }
    

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: cadastro.html?error=invalid_email');
        exit();
    }
    

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        header('Location: cadastro.html?error=invalid_username_format');
        exit();
    }
    
    try {

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            header('Location: cadastro.html?error=user_exists');
            exit();
        }
        
        $stmt->close();
        

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {

            $user_id = $conn->insert_id;
            

            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
 
            header('Location: ../pages/home.php?message=registration_success');
            exit();
        } else {
            $stmt->close();
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
    } catch (Exception $e) {

        error_log("Registration error: " . $e->getMessage());
        header('Location: cadastro.html?error=system_error');
        exit();
    }
} else {

    header('Location: cadastro.html');
    exit();
}

$conn->close();
?>