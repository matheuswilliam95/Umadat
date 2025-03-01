<?php
require_once __DIR__ . '/../includes/config.php';
?>
<div class="mobile_footer desktop_footer">
    <div class="footer_links">
        <a href="<?php echo BASE_URL; ?>contato.php">Contato</a>
        <a href="<?php echo BASE_URL; ?>privacidade.php">Política de Privacidade</a>
    </div>

    <div class="footer_copy">
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos os direitos reservados.</p>
        <p>Desenvolvido com ❤️ para nossa Igreja</p>
    </div>

    <script defer src="<?php echo PASTA_BASE; ?>public/js/main.js"></script>
</div>

</html>