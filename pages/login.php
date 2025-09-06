<?php 
session_start();


if (isset($_SESSION['user_login']) && !empty($_SESSION['user_login'])) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CheckAudit</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <div class="signup-container">
        <h2 class="signup-title">Login</h2>
        
        <?php

        if (isset($_GET['error'])) {
            echo '<div style="color: #ff6b6b; text-align: center; margin-bottom: 2rem; font-size: 1.4rem;">';
            switch($_GET['error']) {
                case 'incorrect_password':
                    echo 'Senha incorreta!';
                    break;
                case 'user_not_found':
                    echo 'Usuário não encontrado!';
                    break;
                case 'missing_data':
                    echo 'Erro ao processar dados!';
                    break;
                default:
                    echo 'Erro no login!';
            }
            echo '</div>';
        }
        ?>
        
        <form action="../php/loginController.php" method="POST">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Informe seu Email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Informe sua senha" required minlength="6">
            </div>
            
            <button type="submit" class="signup-btn">Entrar</button>
            
            <div class="form-links">
                <a href="cadastro.php">Não tem conta? Cadastre-se aqui</a>
                <br><br>
                <a href="#">Esqueceu sua senha?</a>
            </div>
        </form>
    </div>
</body>

</html>