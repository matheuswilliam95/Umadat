<?php
require_once __DIR__ . '/config.php';
?>
<footer class="mobile-footer">
    <div class="footer-links">
        <a href="<?php echo BASE_URL; ?>sobre.php">Sobre</a>
        <a href="<?php echo BASE_URL; ?>contato.php">Contato</a>
        <a href="<?php echo BASE_URL; ?>termos.php">Termos de Uso</a>
        <a href="<?php echo BASE_URL; ?>privacidade.php">Política de Privacidade</a>
    </div>
    <div class="footer-social">
        <a href="#" class="social-icon">📘</a>
        <a href="#" class="social-icon">🐦</a>
        <a href="#" class="social-icon">📸</a>
    </div>
    <div class="footer-copy">
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos os direitos reservados.</p>
        <p>Desenvolvido com ❤️ para a comunidade.</p>
    </div>
</footer>

<script defer src="/public/js/main.js"></script>
</body>

</html>