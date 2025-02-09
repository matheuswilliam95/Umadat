<?php

require_once __DIR__ . '/db.php';

// 🔹 Segurança

// Sanitiza entrada de usuário para evitar XSS
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Gera um token CSRF para proteger formulários
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verifica se o token CSRF é válido
function verifyCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// 🔹 Autenticação e Sessões

// Verifica se o usuário está autenticado
function checkLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Encerra a sessão do usuário
function logout()
{
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// 🔹 Banco de Dados

// Executa uma consulta SQL e retorna uma única linha
function fetchSingleRow($sql, $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Executa uma consulta SQL e retorna várias linhas
function fetchAllRows($sql, $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Executa um comando SQL (INSERT, UPDATE, DELETE)
function executeQuery($sql, $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// 🔹 Utilidades

// Redireciona para uma URL de forma segura
function redirect($url)
{
    header("Location: " . filter_var($url, FILTER_SANITIZE_URL));
    exit;
}

// Formata datas para exibição amigável
function formatDate($date)
{
    return date("d/m/Y H:i", strtotime($date));
}

// Retorna o papel de um usuário no sistema
function getUserRole($userId)
{
    $sql = "SELECT role FROM usuarios WHERE id = ?";
    $result = fetchSingleRow($sql, [$userId]);
    return $result ? $result['role'] : null;
}

// Retorna as informações do usuário
function getUserProfile($userId)
{
    $sql = "SELECT nome, telefone, (SELECT nome FROM hierarquia WHERE id = (SELECT hierarquia_id FROM usuarios_hierarquia WHERE usuario_id = ? LIMIT 1)) AS congregacao FROM usuarios WHERE id = ?";
    return fetchSingleRow($sql, [$userId, $userId]);
}

// Atualiza informações do perfil do usuário
function updateUserProfile($userId, $data)
{
    $sql = "UPDATE usuarios SET nome = ?, telefone = ? WHERE id = ?";
    return executeQuery($sql, [$data['nome'], $data['telefone'], $userId]);
}

// Altera a senha do usuário
function changeUserPassword($userId, $currentPassword, $newPassword)
{
    $user = fetchSingleRow("SELECT senha FROM usuarios WHERE id = ?", [$userId]);
    if ($user && password_verify($currentPassword, $user['senha'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return executeQuery("UPDATE usuarios SET senha = ? WHERE id = ?", [$hashedPassword, $userId]);
    }
    return false;
}

// Obtém eventos inscritos do usuário
function getUserEvents($userId)
{
    $sql = "SELECT e.id, e.titulo FROM eventos e JOIN inscricoes i ON e.id = i.evento_id WHERE i.usuario_id = ?";
    return fetchAllRows($sql, [$userId]);
}

// Cancela a inscrição de um evento
function cancelEventRegistration($userId, $eventId)
{
    return executeQuery("DELETE FROM inscricoes WHERE usuario_id = ? AND evento_id = ?", [$userId, $eventId]);
}

// Função para buscar eventos públicos
function getPublicEvents()
{
    global $pdo;
    $sql = "SELECT id, titulo, data_inicio, horario_inicio, local FROM eventos WHERE tipo = 'publico' ORDER BY data_inicio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?> // Fim do código.