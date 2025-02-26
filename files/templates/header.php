<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<div class="logo">
    <a href="<?php echo BASE_URL; ?>">
        <img src="<?php echo PASTA_BASE . 'public/img/logo.png'; ?>" alt="Logo da Igreja"
            onerror="this.src='<?php echo PASTA_BASE . 'public/img/logo_placeholder.png'; ?>';">
    </a>
</div>
<nav class="mobile-nav">
    <!-- Adicionamos a classe "menu-btn" para garantir que os estilos sejam aplicados -->
    <button id="menu-toggle" class="menu-btn">☰</button>
    <ul id="menu" class="menu-list">
        <li><a href="<?php echo BASE_URL; ?>">Início</a></li>
        <li><a href="<?php echo PASTA_BASE; ?>pages/eventos.php">Eventos</a></li>
        <li><a href="<?php echo PASTA_BASE; ?>pages/cadastrar_evento.php">Novo Evento</a></li>
        <li><a href="<?php echo PASTA_BASE; ?>pages/cadastro.php">Novo Usuário</a></li>
        <li><a href="<?php echo PASTA_BASE; ?>pages/perfil.php">Perfil</a></li>
    </ul>
    <!-- <div class="quick-icons">
        <a href="<?php echo BASE_URL; ?>" class="icon-home">Início</a>
        <a href="<?php echo PASTA_BASE; ?>pages/eventos.php" class="icon-post">Eventos</a>
        <a href="<?php echo PASTA_BASE; ?>pages/perfil.php" class="icon-profile">Perfil</a>
    </div> -->
</nav>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const menu = document.getElementById("menu");
        // Em vez de manipular o estilo inline, alternamos uma classe
        menuToggle.addEventListener("click", function () {
            menu.classList.toggle("active");
        });
    });
</script>

</html>