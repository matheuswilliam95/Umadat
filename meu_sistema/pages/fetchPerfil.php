<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');
session_start();

if (!isAuthenticated()) {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$action = $input['action'] ?? '';
$userId = $_SESSION['user_id'];

switch ($action) {
    case "getProfile":
        $user = getUserProfile($userId);
        echo json_encode(["success" => true, "user" => $user]);
        break;
    
    case "updateProfile":
        if (!verifyCSRFToken($input['csrf_token'])) {
            echo json_encode(["success" => false, "message" => "Token CSRF inválido."]);
            exit;
        }
        $updated = updateUserProfile($userId, [
            "nome" => sanitizeInput($input['nome']),
            "telefone" => sanitizeInput($input['telefone']),
            "congregacao" => sanitizeInput($input['congregacao'])
        ]);
        echo json_encode(["success" => $updated, "message" => $updated ? "Perfil atualizado com sucesso." : "Erro ao atualizar perfil."]);
        break;
    
    case "changePassword":
        if (!verifyCSRFToken($input['csrf_token'])) {
            echo json_encode(["success" => false, "message" => "Token CSRF inválido."]);
            exit;
        }
        $changed = changeUserPassword($userId, $input['currentPassword'], $input['newPassword']);
        echo json_encode(["success" => $changed, "message" => $changed ? "Senha alterada com sucesso." : "Erro ao alterar senha."]);
        break;
    
    case "getUserEvents":
        $events = getUserEvents($userId);
        echo json_encode(["success" => true, "events" => $events]);
        break;
    
    case "cancelEvent":
        $eventId = intval($input['eventId']);
        $canceled = cancelEventRegistration($userId, $eventId);
        echo json_encode(["success" => $canceled, "message" => $canceled ? "Inscrição cancelada com sucesso." : "Erro ao cancelar inscrição."]);
        break;
    
    default:
        echo json_encode(["success" => false, "message" => "Ação inválida."]);
        break;
}
?>
