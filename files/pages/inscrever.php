<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $evento_id = intval($_POST['evento_id']);
    $action = $_POST['action'];

    if ($action === 'inscrever') {
        $sql = "INSERT INTO inscricoes (usuario_id, evento_id) VALUES (:usuario_id, :evento_id)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['usuario_id' => $usuario_id, 'evento_id' => $evento_id])) {
            echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao realizar inscrição.']);
        }
    } elseif ($action === 'cancelar') {
        $sql = "DELETE FROM inscricoes WHERE usuario_id = :usuario_id AND evento_id = :evento_id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(['usuario_id' => $usuario_id, 'evento_id' => $evento_id])) {
            echo json_encode(['success' => true, 'message' => 'Inscrição cancelada com sucesso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cancelar inscrição.']);
        }
    }
}
?>
