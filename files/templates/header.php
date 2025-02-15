<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rede social para igrejas - conecte-se com sua comunidade cristã">
    <title><?php echo SITE_NAME; ?></title>
</head>


<header class="mobile-header">
    <div class="logo">
        <a href="<?php echo BASE_URL; ?>">
            <img src="<?php echo PASTA_BASE . 'public/img/logo.png'; ?>" alt="Logo da Igreja"
                onerror="this.src='<?php echo PASTA_BASE . 'public/img/logo_placeholder.png'; ?>';">
        </a>
    </div>
    <nav class="mobile-nav">
        <button id="menu-toggle">☰</button>
        <ul id="menu" class="menu-list">
            <li><a href="<?php echo BASE_URL; ?>">Início</a></li>
            <li><a href="<?php echo BASE_URL; ?>postagens.php">Postagens</a></li>
            <li><a href="<?php echo BASE_URL; ?>perfil.php">Perfil</a></li>
        </ul>
        <div class="quick-icons">
            <a href="<?php echo BASE_URL; ?>" class="icon-home">🏠</a>
            <a href="<?php echo BASE_URL; ?>postagens.php" class="icon-post">📝</a>
            <a href="<?php echo BASE_URL; ?>perfil.php" class="icon-profile">👤</a>
        </div>
    </nav>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuToggle = document.getElementById("menu-toggle");
            const menu = document.getElementById("menu");
            menuToggle.addEventListener("click", function () {
                menu.style.display = menu.style.display === "flex" ? "none" : "flex";
            });
        });
    </script>
</header>

</html>