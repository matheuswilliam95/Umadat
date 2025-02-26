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
                    <?php echo "<script> debug.log(evento)</script>"; ?>
                    <li class="evento-item">
                        <div class="evento-slider">
                            <!-- Slide 1: Capa + Botão -->
                            <div class="evento-slide">
                                <div class="evento-imagem">
                                    <img src="<?php echo PASTA_BASE . htmlspecialchars($evento['imagem_capa'] ?? (PASTA_BASE . 'public/img/default_evento.jpg')); ?>"
                                        alt="<?php echo htmlspecialchars($evento['titulo']); ?>">
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

                            <!-- Slide 3: Galeria de Fotos -->
                            <div class="evento-slide">
                                <div class="evento-galeria">
                                    <h3>Galeria de Fotos</h3>
                                    <?php
                                    // Consulta as imagens associadas a este evento
                                    $stmt = $pdo->prepare("SELECT caminho_imagem FROM evento_imagens WHERE evento_id = ?");
                                    $stmt->execute([$evento['id']]);
                                    $imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <?php if (count($imagens) > 0): ?>
                                        <div class="galeria-container">
                                            <?php foreach ($imagens as $imagem): ?>
                                                <img src="<?php echo PASTA_BASE . 'public/uploads/' . $evento['id'] . '/' . htmlspecialchars($imagem['caminho_imagem']); ?>"
                                                    alt="Imagem do evento <?php echo htmlspecialchars($evento['titulo']); ?>">
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>Nenhuma foto disponível.</p>
                                    <?php endif; ?>
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
        // Lógica do slider para múltiplos slides (capa, detalhes e galeria)
        document.querySelectorAll('.evento-slider').forEach(slider => {
            const slides = slider.querySelectorAll('.evento-slide');
            let currentIndex = 0;
            let startX = 0;

            slider.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });

            slider.addEventListener('touchend', (e) => {
                const endX = e.changedTouches[0].clientX;
                const diff = endX - startX;
                if (diff < -50 && currentIndex < slides.length - 1) {
                    currentIndex++;
                } else if (diff > 50 && currentIndex > 0) {
                    currentIndex--;
                }
                slider.style.transform = `translateX(-${currentIndex * 100}%)`;
            });
        });
    </script>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>

</html>