<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Evento não encontrado.");
}

$eventId = intval($_GET['id']);
$evento = getEventDetails($eventId);

if (!$evento) {
    die("Evento não encontrado ou acesso restrito.");
}

$relatedEvents = getRelatedEvents($eventId);
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($evento['titulo']); ?> - <?php echo SITE_NAME; ?></title>
    <script defer src="<?php echo PASTA_BASE; ?>public/js/main.js"></script>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
    <script defer src="<?php echo PASTA_BASE; ?>public/js/evento.js"></script>
</head>

<header>
    <?php include __DIR__ . '/../templates/header.php'; ?>
</header>

<body>
    <div class="main_container">
        <div class="container">
            <div class="evento-container">
                <h2><?php echo htmlspecialchars($evento['titulo']); ?></h2>


                <?php if (!empty($evento['imagem_capa'])): ?>
                    <img src="<?php echo PASTA_BASE . htmlspecialchars($evento['imagem_capa']); ?>" alt="Imagem do evento"
                        class="capa_evento_single">
                <?php endif; ?>

                <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($evento['descricao'])); ?></p>
                <p><strong>Data:</strong> <?php echo formatDate($evento['data_inicio']); ?> -
                    <?php echo formatDate($evento['data_fim']); ?>
                </p>
                <p><strong>Horário:</strong>
                    <?php echo $evento['horario_inicio'] ? date('H:i', strtotime($evento['horario_inicio'])) : 'N/A'; ?>
                    às
                    <?php echo $evento['horario_fim'] ? date('H:i', strtotime($evento['horario_fim'])) : 'N/A'; ?>
                </p>
                <p><strong>Local:</strong>
                    <?php
                    $local = $evento['local'] ?? 'N/A';
                    if ($local !== 'N/A') {
                        echo '<a href="https://www.google.com/maps/search/?api=1&query=' . urlencode($local) . '" target="_blank">' . htmlspecialchars($local) . '</a>';
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </p>
                <p><strong>Valor:</strong>
                    <?php echo $evento['valor'] ? 'R$ ' . number_format($evento['valor'], 2, ',', '.') : 'Gratuito'; ?>
                </p>
                <p><strong>Responsável:</strong> <?php echo htmlspecialchars($evento['responsavel_nome'] ?? 'N/A'); ?>
                </p>

                <?php if ($evento['data_limite_inscricao'] >= date('Y-m-d')): ?>
                    <button id="inscricao-btn" data-evento-id="<?php echo $evento['id']; ?>">Inscreva-se</button>
                <?php endif; ?>
                <?php if ($evento['data_limite_inscricao'] >= date('Y-m-d')): ?>
                    <button id="inscricao-btn" data-evento-id="<?php echo $evento['id']; ?>">Inscreva-se</button>
                <?php endif; ?>

                <!-- Botão para salvar o evento na agenda do celular -->
                <a class="btn" href="export_event_ics.php?id=<?php echo $evento['id']; ?>">Salvar na Agenda</a>


                <h3>Eventos Relacionados</h3>
                <ul>
                    <?php foreach ($relatedEvents as $relEvent): ?>
                        <li><a href="evento.php?id=<?php echo $relEvent['id']; ?>">
                                <?php echo htmlspecialchars($relEvent['titulo']); ?> </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>

</html>