<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

session_start();

$erro = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $senha = $_POST['senha'];
    
    if (login($email, $senha)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script defer src="/public/js/main.js"></script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($erro): ?>
            <p class="error-message"> <?php echo $erro; ?> </p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            
            <button type="submit" class="login-btn">Entrar</button>
        </form>
        <div class="login-links">
            <a href="recuperar_senha.php">Esqueceu a senha?</a>
            <a href="cadastro.php">Criar uma conta</a>
        </div>
    </div>
</body>
</html>
