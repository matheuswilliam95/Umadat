<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

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
if (!function_exists('getUserRole')) {
    function getUserRole($user_id)
    {
        global $db;
        $stmt = $db->prepare("SELECT role FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $user_id]);
        return $stmt->fetchColumn();
    }
}



// Retorna as informações do usuário
function getUserProfile($userId)
{
    $sql = "SELECT u.nome, u.telefone, 
                   c.nome AS congregacao, 
                   co.nome AS conjunto
            FROM usuarios u
            LEFT JOIN congregacoes c ON u.congregacao_id = c.id
            LEFT JOIN conjuntos co ON u.conjunto_id = co.id
            WHERE u.id = ? 
            LIMIT 1";

    $user = fetchSingleRow($sql, [$userId]);

    // Evita erro de htmlspecialchars()
    $user['congregacao'] = $user['congregacao'] ?? '';
    $user['conjunto'] = $user['conjunto'] ?? '';

    return $user;
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

// Função para buscar eventos públicos, incluindo a imagem de capa
function getPublicEvents()
{
    global $pdo;
    $sql = "SELECT id, titulo, data_inicio, horario_inicio, local, imagem_capa 
            FROM eventos 
            WHERE tipo = 'publico' 
            ORDER BY data_inicio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Obtém detalhes de um evento específico
function getEventDetails($eventId)
{
    $sql = "SELECT * FROM eventos WHERE id = ? AND tipo = 'publico'";
    return fetchSingleRow($sql, [$eventId]);
}

// Obtém eventos relacionados com base na mesma congregação
function getRelatedEvents($eventId)
{
    $sql = "SELECT e.id, e.titulo FROM eventos e JOIN eventos_hierarquia eh ON e.id = eh.evento_id WHERE eh.hierarquia_id = (SELECT hierarquia_id FROM eventos_hierarquia WHERE evento_id = ?) AND e.id != ? LIMIT 5";
    return fetchAllRows($sql, [$eventId, $eventId]);
}


// Obtém a lista de inscritos em um evento
function getEventRegistrations($eventId)
{
    $sql = "SELECT u.nome, u.email, u.telefone, i.data_inscricao FROM inscricoes i JOIN usuarios u ON i.usuario_id = u.id WHERE i.evento_id = ?";
    return fetchAllRows($sql, [$eventId]);
}


// Verifica se o usuário é administrador
function checkAdmin()
{
    if (!isset($_SESSION['user_id']) || getUserRole($_SESSION['user_id']) !== 'super_admin') {
        die("Acesso negado.");
    }
}

// Exclui um evento e suas inscrições associadas
function deleteEvent($eventId)
{
    global $pdo;

    try {
        $pdo->beginTransaction();

        // Excluir inscrições associadas
        $sql = "DELETE FROM inscricoes WHERE evento_id = ?";
        executeQuery($sql, [$eventId]);

        // Excluir o evento
        $sql = "DELETE FROM eventos WHERE id = ?";
        executeQuery($sql, [$eventId]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Registra um novo usuário
function registerUser($data)
{
    global $pdo;

    // Verifica se o e-mail já está cadastrado
    if (fetchSingleRow("SELECT id FROM usuarios WHERE email = ?", [$data['email']])) {
        return false;
    }

    $hashedPassword = password_hash($data['senha'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nome, email, senha, telefone, documento_identificacao, status, congregacao_id, conjunto_id) VALUES (?, ?, ?, ?, ?, 'ativo', ?, ?)";
    $params = [$data['nome'], $data['email'], $hashedPassword, $data['telefone'], $data['documento'], $data['congregacao'], $data['conjunto']];

    return executeQuery($sql, $params);
}

function getCongregacoes()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nome FROM congregacoes ORDER BY nome ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>