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
$inscritos = getEventRegistrations($eventId);
$evento = getEventDetails($eventId);

if (!$evento) {
    die("Evento não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscritos no Evento - <?php echo htmlspecialchars($evento['titulo']); ?></title>
    <link rel="stylesheet" href="/public/css/admin.css">
    <script defer src="/public/js/admin_eventos.js"></script>
</head>
<body>
    <div class="admin-container">
        <h2>Inscritos no Evento: <?php echo htmlspecialchars($evento['titulo']); ?></h2>
        
        <?php if (empty($inscritos)): ?>
            <p>Nenhum participante inscrito neste evento ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Data de Inscrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscritos as $inscrito): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($inscrito['nome']); ?></td>
                            <td><?php echo htmlspecialchars($inscrito['email']); ?></td>
                            <td><?php echo htmlspecialchars($inscrito['telefone']); ?></td>
                            <td><?php echo formatDate($inscrito['data_inscricao']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <button onclick="exportData(<?php echo $eventId; ?>, 'excel')">Exportar para Excel</button>
            <button onclick="exportData(<?php echo $eventId; ?>, 'pdf')">Exportar para PDF</button>
        <?php endif; ?>
    </div>
</body>
</html>
