<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();
checkAdmin(); // Garante que apenas administradores acessem

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Evento inválido.");
}

$eventId = intval($_GET['id']);
$evento = getEventDetails($eventId);

if (!$evento) {
    die("Evento não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (deleteEvent($eventId)) {
        header("Location: /admin/eventos.php?msg=Evento deletado com sucesso!");
        exit;
    } else {
        $error = "Erro ao deletar evento.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Evento - <?php echo htmlspecialchars($evento['titulo']); ?></title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <script defer src="/public/js/admin_eventos.js"></script>
</head>
<body>
    <div class="admin-container">
        <h2>Excluir Evento</h2>
        <p>Tem certeza de que deseja excluir o evento <strong><?php echo htmlspecialchars($evento['titulo']); ?></strong>?</p>
        <form method="POST">
            <button type="submit" class="delete-btn">Confirmar Exclusão</button>
            <a href="/admin/eventos.php" class="cancel-btn">Cancelar</a>
        </form>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
