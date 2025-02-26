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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swipe/2.2.14/swipe.min.js"></script>
    <title>Eventos - <?php echo SITE_NAME; ?></title>
    <script defer src="<?php echo PASTA_BASE; ?>public/js/main.js"></script>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
    <style>
        /* Estilos básicos para o slider e a galeria */
        .evento-slider {
            display: flex;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .evento-slide {
            flex: 0 0 100%;
            box-sizing: border-box;
            padding: 10px;
        }

        .galeria-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }

        .galeria-container img {
            max-height: 150px;
        }
    </style>
</head>
<header>
    <?php include __DIR__ . '/../templates/header.php'; ?>
</header>

<body>
    <div class="main_container">
        <div class="eventos-container">
            <h2>Eventos Públicos</h2>
            <form id="filtro-form">
                <input type="text" name="titulo" placeholder="Buscar por nome do evento">
                <input type="date" name="data_inicio" placeholder="Data de início">
                <select name="tipo" placeholder="Tipo de evento">
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
                    <script>
                        console.log(<?php echo json_encode($evento, JSON_UNESCAPED_UNICODE); ?>);
                    </script>
                    <li class="evento-item">
                        <div class="evento-slider">
                            <!-- Slide 1: Capa + Botão -->
                            <div class="evento-slide">
                                <div class="evento-imagem">
                                    <img src="<?php echo PASTA_BASE . htmlspecialchars($evento['imagem_capa'] ?? (PASTA_BASE . 'public/img/default_evento.jpg')); ?>"
                                        alt="<?php echo htmlspecialchars($evento['titulo']); ?>">
                                    <h2 class="titulo_enventos"><?php echo htmlspecialchars($evento['titulo']); ?></h2>
                                </div>
                            </div>

                            <!-- Slide 2: Detalhes do Evento -->
                            <div class="evento-slide">
                                <div class="evento-descricao">
                                    <h3><?php echo htmlspecialchars($evento['titulo']); ?></h3>
                                    <p><strong>Data:</strong> <?php echo formatDate($evento['data_inicio']); ?></p>
                                    <p>
                                        <strong>Horário:</strong>
                                        <?php echo $evento['horario_inicio'] ? date('H:i', strtotime($evento['horario_inicio'])) : 'N/A'; ?>
                                    </p>
                                    <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['local'] ?? 'N/A'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="evento.php?id=<?php echo $evento['id']; ?>" class="detalhes-btn">Ver Detalhes</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".evento-slider").forEach(function (slider) {
                new Swipe(slider, {
                    startSlide: 0,
                    speed: 400,
                    auto: false,
                    draggable: true,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function (index, element) {
                        console.log("Slide ativo:", index);
                    },
                    transitionEnd: function (index, element) {
                        console.log("Transição finalizada no slide:", index);
                    }
                });
            });
        });


    </script>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>

</html>