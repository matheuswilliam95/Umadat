<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_ACTIVE) {
    // Limpa todas as variáveis da sessão
    $_SESSION = [];

    // Invalida o cookie da sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destrói a sessão
    session_destroy();
}

// Redireciona para a página de login com uma mensagem de sucesso
header("Location: login.php?msg=Logout realizado com sucesso!");
exit;
?>