<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitizeInput($_POST['nome']);
    $email = sanitizeInput($_POST['email']);
    $telefone = sanitizeInput($_POST['telefone'] ?? '');
    $documento = sanitizeInput($_POST['documento']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $congregacao = intval($_POST['congregacao']);
    $conjunto = intval($_POST['conjunto']);
    $csrf_token = $_POST['csrf_token'];

    if (!verifyCSRFToken($csrf_token)) {
        $error = "Token CSRF inválido.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "E-mail inválido.";
    } elseif (strlen($senha) < 8 || !preg_match('/[A-Za-z]/', $senha) || !preg_match('/[0-9]/', $senha)) {
        $error = "A senha deve ter no mínimo 8 caracteres, incluindo letras e números.";
    } elseif ($senha !== $confirmar_senha) {
        $error = "As senhas não coincidem.";
    } elseif (registerUser(compact('nome', 'email', 'telefone', 'documento', 'senha', 'congregacao', 'conjunto'))) {
        header("Location: login.php?msg=Cadastro realizado com sucesso!");
        exit;
    } else {
        $error = "Erro ao cadastrar. Verifique os dados e tente novamente.";
    }
}

$congregacoes = getCongregacoes();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <script defer src="/public/js/cadastro.js"></script>
</head>

<body>
    <div class="cadastro-container">
        <h2>Cadastro de Novo Usuário</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"> <?php echo $error; ?> </p>
        <?php endif; ?>
        <form action="cadastro.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="telefone">Telefone (opcional):</label>
            <input type="text" name="telefone" id="telefone">

            <label for="documento">Documento de Identificação:</label>
            <input type="text" name="documento" id="documento" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <label for="confirmar_senha">Confirmar Senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" required>

            <label for="congregacao">Congregação:</label>
            <select name="congregacao" id="congregacao" required>
                <option value="">Selecione...</option>
                <?php foreach ($congregacoes as $congregacao): ?>
                    <option value="<?php echo $congregacao['id']; ?>"> <?php echo htmlspecialchars($congregacao['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="conjunto">Conjunto:</label>
            <select name="conjunto" id="conjunto" required>
                <option value="">Selecione uma congregação primeiro</option>
            </select>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script defer src="/public/js/cadastro.js"></script>
</body>

</html>