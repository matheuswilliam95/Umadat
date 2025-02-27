<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    $_SESSION['usuario_id'] = $usuario_id; // Defina isso após validar o usuário

}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = sanitizeInput($_POST['nome']);
    $regional_id = !empty($_POST['regional_id']) ? intval($_POST['regional_id']) : NULL;

    if (!empty($nome)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO congregacoes (nome, regional_id) VALUES (:nome, :regional_id)");
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':regional_id', $regional_id, PDO::PARAM_INT);
            $stmt->execute();

            // Redireciona para evitar reenvio do formulário ao atualizar a página
            header("Location: cadastro_congregacao.php");
            exit;
        } catch (PDOException $e) {
            echo "<p>Erro ao cadastrar: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>O nome da congregação é obrigatório!</p>";
    }
}


$regionais = getRegionais();

?>

<!DOCTYPE html>
<html lang="pt-br">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Congregação - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
    <script defer src="<?php echo PASTA_BASE; ?>public/js/admin_eventos.js?v=<?php echo time(); ?>"></script>
</head>

<header>
    <?php include __DIR__ . '/../templates/header.php'; ?>
</header>


<body>
    <h2>Cadastro de Nova Congregação</h2>

    <form action="" method="POST">
        <label for="nome">Nome da Congregação:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="regional">Regional:</label>

        <select name="regional_id" id="regional_id">
            <option value="" disabled selected>Selecione uma Congregação</option>
            <?php foreach ($regionais as $regional): ?>
                <option value="<?php echo $regional['id']; ?>">
                    <?php echo htmlspecialchars($regional['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>