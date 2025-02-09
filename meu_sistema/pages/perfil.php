<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();
checkLogin();

$userId = $_SESSION['user_id'];
$user = getUserProfile($userId);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script defer src="/public/js/perfil.js"></script>
</head>

<body>
    <div class="profile-container">
        <h2>Perfil de <?php echo htmlspecialchars($user['nome']); ?></h2>
        <form id="profile-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($user['telefone']); ?>">

            <label for="congregacao">Congregação:</label>
            <input type="text" name="congregacao" id="congregacao"
                value="<?php echo htmlspecialchars($user['congregacao']); ?>">

            <button type="submit">Salvar Alterações</button>
        </form>

        <h3>Alterar Senha</h3>
        <form id="password-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <label for="current_password">Senha Atual:</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">Nova Senha:</label>
            <input type="password" name="new_password" id="new_password" required>

            <button type="submit">Alterar Senha</button>
        </form>

        <h3>Eventos Inscritos</h3>
        <ul id="event-list">
            <!-- Eventos serão carregados via AJAX -->
        </ul>
    </div>
</body>

</html>