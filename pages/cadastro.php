<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="" type="image/jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Cadastro - CheckAudit</title>
</head>
<body>
    <div class="signup-container">
        <h2 class="signup-title">Crie sua conta</h2>
        
        <form action="../php/register.php" method="POST" onsubmit="return validateForm()">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Insira seu username" required minlength="3" maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Insira seu email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Insira sua senha" required minlength="6" onkeyup="checkPasswordStrength()">
                <div id="password-strength" class="password-strength" style="display: none;"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirme sua senha</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme sua senha" required>
            </div>

            <div class="form-group">
                <div class="recaptcha-container">
                    <div class="g-recaptcha" data-sitekey="6LeRLU0rAAAAAFaurGLkjnsdolroXd5OfGF4Do2d" required></div>
                </div>
            </div>

            <button type="submit" class="signup-btn">
                <i class="fas fa-user-plus"></i> Crie sua conta
            </button>
            
            <div class="form-links">
                <a href="login.php">Já possui um cadastro? Realize o login aqui</a>
            </div>
        </form>
    </div>
</body>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="../assets/cadastro.js"></script>

</html>