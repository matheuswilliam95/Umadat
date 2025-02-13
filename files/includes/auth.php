<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

// Função para login do usuário
function login($email, $senha)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, senha FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

// Função para verificar se o usuário está autenticado
function isAuthenticated()
{
    return isset($_SESSION['user_id']);
}


?>