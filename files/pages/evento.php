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

$isInscrito = false;
$isLoggedIn = isset($_SESSION['user_id']);
if ($isLoggedIn) {
    $usuario_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscricoes WHERE usuario_id = :usuario_id AND evento_id = :evento_id");
    $stmt->execute(['usuario_id' => $usuario_id, 'evento_id' => $eventId]);
    $isInscrito = $stmt->fetchColumn() > 0;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head></head>
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
                <h2><?php echo strtoupper(htmlspecialchars($evento['titulo'])); ?></h2>


                <!-- Imagem -->
                <?php if (!empty($evento['imagem_capa'])): ?>
                    <img src="<?php echo PASTA_BASE . htmlspecialchars($evento['imagem_capa']); ?>" alt="Imagem do evento"
                        class="capa_evento_single">
                <?php endif; ?>

                <!-- Informações Gerais do Evento -->
                <h2 class="subtitulo_evento_infos">Informações do Evento</h2>


                <!-- Descrição -->
                <p><strong>Descrição:</strong>
                    <?php
                    $descricao = htmlspecialchars($evento['descricao']);
                    $descricao = preg_replace('/@(\w+)/', '<a class="link_instagram_participantes" href="https://www.instagram.com/$1" target="_blank">@$1</a>', $descricao);
                    echo nl2br($descricao);
                    ?>
                </p>

                <!-- Data -->
                <p><strong>Data:</strong>
                    <?php
                    $dataInicio = date('d/m/Y', strtotime($evento['data_inicio']));
                    $dataFim = date('d/m/Y', strtotime($evento['data_fim']));
                    echo $dataInicio === $dataFim ? $dataInicio : $dataInicio . ' - ' . $dataFim;
                    ?>
                </p>

                <!-- Horário -->
                <p><strong>Horário:</strong>
                    <?php echo $evento['horario_inicio'] ? date('H:i', strtotime($evento['horario_inicio'])) : 'N/A'; ?>
                    às
                    <?php echo $evento['horario_fim'] ? date('H:i', strtotime($evento['horario_fim'])) : 'N/A'; ?>
                </p>

                <!-- Local -->
                <p><strong>Local:</strong>
                    <?php
                    $local = $evento['local'] ?? 'N/A';
                    if ($local !== 'N/A') {
                        echo '<a class="link_local_texto" href="https://www.google.com/maps/search/?api=1&query=' . urlencode($local) . '" target="_blank">' . htmlspecialchars($local) . '</a>';
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </p>


                <!-- Valores -->
                <?php if ($evento['valor'] > 0): ?>
                    <p><strong>Valor:</strong>
                        <?php echo 'R$ ' . number_format($evento['valor'], 2, ',', '.'); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($evento['responsavel_nome'])): ?>
                    <p><strong>Responsável:</strong> <?php echo htmlspecialchars($evento['responsavel_nome']); ?></p>
                <?php endif; ?>

                <!-- Realizadores -->
                <h2 class="subtitulo_evento_infos">Organizadores</h2>

                <?php
                $congregacao = !is_null($evento['congregacao']) ? getCongregacaoName($evento['congregacao']) : 'N/A';
                $conjunto = !is_null($evento['conjunto']) ? getConjuntoName($evento['conjunto']) : 'N/A';
                ?>

                <?php if (!is_null($evento['congregacao'])): ?>
                    <p><strong>Congregação:</strong> <?php echo htmlspecialchars($congregacao); ?></p>
                <?php endif; ?>
                <?php if (!is_null($evento['conjunto'])): ?>
                    <p><strong>Conjunto:</strong> <?php echo htmlspecialchars($conjunto); ?></p>
                <?php endif; ?>


                <!-- Inscrições -->
                <?php if (!is_null($evento['data_limite_inscricao'])): ?>
                    <p>
                        <strong>Data limite de inscrição:</strong>
                        <?php echo date('d/m/Y', strtotime($evento['data_limite_inscricao'])); ?>
                    </p>
                    <?php if (strtotime(date('Y-m-d')) <= strtotime($evento['data_limite_inscricao'])): ?>
                        <button id="inscricao-btn" class="inscricao_button <?php echo $isInscrito ? 'cancelar' : ''; ?>"
                            data-evento-id="<?php echo $evento['id']; ?>"
                            data-action="<?php echo $isLoggedIn ? ($isInscrito ? 'cancelar' : 'inscrever') : 'login'; ?>">
                            <?php echo $isLoggedIn ? ($isInscrito ? 'Cancelar Inscrição' : 'Inscrever') : 'Inscrever'; ?>
                        </button>
                    <?php else: ?>
                        <button id="inscricao-btn" class="inscricao_button" disabled>Inscrição Encerrada</button>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="evento-links">
                    <!-- Botão para exportar para o Google Calendar -->
                    <?php
                    $startDateTime = !empty($evento['horario_inicio'])
                        ? date('Ymd\THis\Z', strtotime($evento['data_inicio'] . ' ' . $evento['horario_inicio']))
                        : date('Ymd\THis\Z', strtotime($evento['data_inicio']));
                    $endDateTime = !empty($evento['horario_fim'])
                        ? date('Ymd\THis\Z', strtotime($evento['data_fim'] . ' ' . $evento['horario_fim']))
                        : date('Ymd\THis\Z', strtotime($evento['data_fim']));

                    $googleCalendarUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE";
                    $googleCalendarUrl .= "&text=" . urlencode($evento['titulo']);
                    $googleCalendarUrl .= "&dates=" . $startDateTime . "/" . $endDateTime;
                    $googleCalendarUrl .= "&details=" . urlencode($evento['descricao']);
                    if (!empty($evento['local'])) {
                        $googleCalendarUrl .= "&location=" . urlencode($evento['local']);
                    }
                    ?>
                    <div class="alinhamento_icones">
                        <a class="botoes_link_evento" target="_blank" href="<?php echo $googleCalendarUrl; ?>">
                            <img class="icon_evento" src="<?php echo PASTA_BASE; ?>public/img/calendar_icon.png"
                                alt="Adicionar ao Google Calendar">
                            <br>
                        </a>
                        <small>Salvar data</small>
                    </div>

                    <!-- Botão para compartilhar link do evento -->
                    <div class="alinhamento_icones">
                        <a class="icon_evento botoes_link_evento" id="compartilhar-btn" href="javascript:void(0);"
                            onclick="compartilharEvento()">
                            <img class="icon_evento" src="<?php echo PASTA_BASE; ?>public/img/share_icon.png"
                                alt="Compartilhar">
                        </a>
                        <br><small>Compartilhar</small>
                    </div>

                    <script>
                        function compartilharEvento() {
                            const url = window.location.href;
                            if (navigator.share) {
                                navigator.share({
                                    title: '<?php echo htmlspecialchars($evento['titulo']); ?>',
                                    text: 'Confira este evento:',
                                    url: url
                                }).catch(console.error);
                            } else {
                                prompt('Copie o link do evento:', url);
                            }
                        }
                    </script>

                    <!-- Local -->
                    <div class="alinhamento_icones">
                        <p>
                            <?php
                            $local = $evento['local'] ?? 'N/A';
                            if ($local !== 'N/A') {
                                echo '<div style="text-align: center;">';
                                echo '<a class="botoes_link_evento" href="https://www.google.com/maps/search/?api=1&query=' . urlencode($local) . '" target="_blank"><img class="icon_evento" src="' . PASTA_BASE . 'public/img/place_icon.png" alt="Local"></a>';
                                echo '<br><small>Ver no mapa</small>';
                                echo '</div>';
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Instagram do Evento -->
                    <?php if (!is_null($evento['instagram_username'])): ?>
                        <div class="alinhamento_icones">
                            <a class="botoes_link_evento"
                                href="https://www.instagram.com/<?php echo htmlspecialchars($evento['instagram_username']); ?>"
                                target="_blank">
                                <img class="icon_evento" src="<?php echo PASTA_BASE; ?>public/img/instagram_icon.png"
                                    alt="Instagram">
                                <br>
                            </a>
                            <small>Instagram</small>
                        </div>
                    <?php endif; ?>
                </div>



                <?php if (!empty($relatedEvents)): ?>
                    <h3>Eventos Relacionados</h3>
                    <ul>
                        <?php foreach ($relatedEvents as $relEvent): ?>
                            <li><a href="evento.php?id=<?php echo $relEvent['id']; ?>">
                                    <?php echo htmlspecialchars($relEvent['titulo']); ?> </a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>

</html>