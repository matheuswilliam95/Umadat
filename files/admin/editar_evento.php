<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();
checkAdmin(); // Garante que apenas administradores acessem

$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 'novo';
$evento = ($eventId !== 'novo') ? getEventDetails($eventId) : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($eventId === 'novo') ? 'Criar Evento' : 'Editar Evento'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <script defer src="/public/js/admin_eventos.js"></script>
</head>
<body>
    <div class="admin-container">
        <h2><?php echo ($eventId === 'novo') ? 'Criar Novo Evento' : 'Editar Evento'; ?></h2>
        <form id="evento-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($eventId); ?>">
            
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($evento['titulo'] ?? ''); ?>" required>
            
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?php echo htmlspecialchars($evento['descricao'] ?? ''); ?></textarea>
            
            <label for="data_inicio">Data de Início:</label>
            <input type="date" name="data_inicio" id="data_inicio" value="<?php echo $evento['data_inicio'] ?? ''; ?>" required>
            
            <label for="horario_inicio">Horário de Início:</label>
            <input type="time" name="horario_inicio" id="horario_inicio" value="<?php echo $evento['horario_inicio'] ?? ''; ?>">
            
            <label for="data_fim">Data de Fim:</label>
            <input type="date" name="data_fim" id="data_fim" value="<?php echo $evento['data_fim'] ?? ''; ?>" required>
            
            <label for="horario_fim">Horário de Fim:</label>
            <input type="time" name="horario_fim" id="horario_fim" value="<?php echo $evento['horario_fim'] ?? ''; ?>">
            
            <label for="local">Local:</label>
            <input type="text" name="local" id="local" value="<?php echo htmlspecialchars($evento['local'] ?? ''); ?>">
            
            <label for="valor">Valor:</label>
            <input type="number" name="valor" id="valor" step="0.01" value="<?php echo htmlspecialchars($evento['valor'] ?? ''); ?>">
            
            <label for="data_limite_inscricao">Data Limite para Inscrição:</label>
            <input type="date" name="data_limite_inscricao" id="data_limite_inscricao" value="<?php echo $evento['data_limite_inscricao'] ?? ''; ?>">
            
            <label for="responsavel_nome">Nome do Responsável:</label>
            <input type="text" name="responsavel_nome" id="responsavel_nome" value="<?php echo htmlspecialchars($evento['responsavel_nome'] ?? ''); ?>">
            
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" required>
                <option value="publico" <?php echo ($evento['tipo'] ?? '') === 'publico' ? 'selected' : ''; ?>>Público</option>
                <option value="restrito" <?php echo ($evento['tipo'] ?? '') === 'restrito' ? 'selected' : ''; ?>>Restrito</option>
            </select>
            
            <button type="submit">Salvar</button>
            <a href="/admin/eventos.php" class="cancel-btn">Cancelar</a>
        </form>
    </div>
</body>
</html>
