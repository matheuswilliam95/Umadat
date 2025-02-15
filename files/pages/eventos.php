<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$eventos = getPublicEvents();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - <?php echo SITE_NAME; ?></title>
    <script defer src="<?php echo PASTA_BASE; ?>public/js/main.js"></script>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="eventos-container">
        <h2>Eventos Públicos</h2>
        <form id="filtro-form">
            <input type="text" name="titulo" placeholder="Buscar por nome do evento">
            <input type="date" name="data_inicio">
            <select name="tipo">
                <option value="">Todos os tipos</option>
                <option value="campo">Campo</option>
                <option value="regional">Regional</option>
                <option value="congregacao">Congregação</option>
                <option value="conjunto">Conjunto</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>

        <ul class="eventos-lista">
            <?php foreach ($eventos as $evento): ?>
                <li class="evento-item">
                    <div class="evento-imagem">
                        <img src="<?php echo PASTA_BASE . htmlspecialchars($evento['imagem_capa'] ??  PASTA_BASE . 'public/img/default_evento.jpg'); ?>"
                            alt="<?php echo htmlspecialchars($evento['titulo']); ?>">

                    </div>
                    <div class="evento-descricao">
                        <h3><?php echo htmlspecialchars($evento['titulo']); ?></h3>
                        <p><strong>Data:</strong> <?php echo formatDate($evento['data_inicio']); ?></p>
                        <p><strong>Horário:</strong>
                            <?php echo $evento['horario_inicio'] ? date('H:i', strtotime($evento['horario_inicio'])) : 'N/A'; ?>
                        </p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['local'] ?? 'N/A'); ?></p>
                        <a href="evento.php?id=<?php echo $evento['id']; ?>" class="detalhes-btn">Ver Detalhes</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>