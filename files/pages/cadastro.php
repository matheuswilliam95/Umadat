<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Redireciona se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}

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
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
    <script defer src="<?php echo PASTA_BASE; ?>public/js/main.js?v=<?php echo time(); ?>"></script>
</head>


<header>
    <?php include __DIR__ . '/../templates/header.php'; ?>
</header>

<body>
    <div class="main_container">
        <div class="container">
            <div class="cadastro_container">
                <h2>Criar uma nova conta</h2>
                <h4>É rápido e fácil.</h4>
                <?php if (isset($error)): ?>
                    <p class="error-message">
                        <?php echo $error; ?>
                    </p>
                <?php endif; ?>
                <form action="cadastro.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="text" name="nome" id="nome" placeholder="Nome Completo" required>

                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <input type="text" name="telefone" id="telefone" placeholder="Celular">
                    <input type="text" name="documento" id="documento" placeholder="Identidade (opcional para viagens)">
                    <input type="password" name="senha" id="senha" placeholder=" Nova Senha" required>
                    <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Repita a Senha"
                        required>

                    <!-- Campo de entrada para selecionar a congregação -->
                    <input list="congregacoes" name="congregacao_nome" id="cadastro_congregacao" required>
                    <datalist id="congregacoes">
                        <?php foreach ($congregacoes as $congregacao): ?>
                            <option value="<?php echo htmlspecialchars($congregacao['nome']); ?>"
                                data-id="<?php echo $congregacao['id']; ?>"></option>
                        <?php endforeach; ?>
                    </datalist>

                    <!-- Campo oculto para armazenar o ID -->
                    <input type="hidden" name="congregacao_id" id="congregacao_id">


                    <select name="conjunto" id="cadastro_conjunto" aria-placeholder="Grupo ou Conjunto" required>
                        <option value="">Selecione uma congregação primeiro</option>
                    </select>

                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
    <script defer src="/umadat/files/public/js/cadastro.js?v=<?php echo time(); ?>"></script>

    <script>
        document.getElementById("cadastro_congregacao").addEventListener("input", function () {
            let input = this;
            let datalist = document.getElementById("congregacoes");
            let hiddenInput = document.getElementById("congregacao_id");
            let options = datalist.getElementsByTagName("option");

            hiddenInput.value = ""; // Limpa o ID se não houver correspondência

            for (let option of options) {
                if (option.value === input.value) {
                    hiddenInput.value = option.getAttribute("data-id"); // Captura o ID correto
                    break;
                }
            }
        });
    </script>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>


</html>