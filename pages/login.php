<?php session_start();?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
        <div class="signup-container">
        <h2 class="signup-title">Login</h2>
        
        <form action="../php/loginController.php" method="POST" onsubmit="return validateForm()">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="informe seu Email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Informe sua senha" required minlength="6" onkeyup="checkPasswordStrength()">
                <div id="password-strength" class="password-strength" style="display: none;"></div>
            </div>
            
            
            <button type="submit" class="signup-btn"> Entrar</button>
            
            <div class="form-links">
                <a href="#">Esqueceu sua senha?</a>
            </div>
        </form>
    </div>
</body>
</html>