<?php
require_once __DIR__ . '/../includes/config.php';
?>
<footer class="mobile_footer desktop_footer">
    <div class="footer_links">
        <a href="<?php echo BASE_URL; ?>sobre.php">Sobre</a>
        <a href="<?php echo BASE_URL; ?>contato.php">Contato</a>
        <a href="<?php echo BASE_URL; ?>termos.php">Termos de Uso</a>
        <a href="<?php echo BASE_URL; ?>privacidade.php">Política de Privacidade</a>
    </div>
    <div class="footer_social">
        <a href="#" class="social-icon">📘</a>
        <a href="#" class="social-icon">🐦</a>
        <a href="#" class="social-icon">📸</a>
    </div>
    <div class="footer_copy">
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos os direitos reservados.</p>
        <p>Desenvolvido com ❤️ para a comunidade.</p>
    </div>
</footer>

<script defer src="/public/js/main.js"></script>
</body>

</html>