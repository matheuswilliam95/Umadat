<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: eventos.php");
    exit;
}

$erro = "";
$redirectUrl = isset($_GET['redirect']) ? $_GET['redirect'] : 'eventos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $senha = $_POST['senha'];

    if (login($email, $senha)) {
        header("Location: " . $redirectUrl);
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
    <script defer src="<?php echo PASTA_BASE; ?>public/js/main.js"></script>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">

</head>

<body>


    <div class="main_container">
        <div class="container container_login">
            <div class="logo_img_div">
                <img class="logo_img" src="<?php echo PASTA_BASE; ?>public/img/system_logo.png">
            </div>
            <h2>Entre e Participe!</h2>
            <?php if (!empty($erro)): ?>
                <p class="error-message">
                    <?php echo htmlspecialchars($erro); ?>
                </p>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?redirect=' . urlencode($redirectUrl); ?>" method="POST">

                <input type="email" name="email" id="email" placeholder="Email ou telefone" required>

                <input type="password" name="senha" id="senha" placeholder="Senha" required>

                <button type="submit" class="login_BT button">Entrar</button>
            </form>
            <div class="login_links">
                <a class="link_login" href="recuperar_senha.php">Esqueceu a senha?</a>
                <a class="criar_conta_BT" href="cadastro.php">
                    Criar uma conta
                </a>
            </div>
        </div>
    </div>
</body>

</html>