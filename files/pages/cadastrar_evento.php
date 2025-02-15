<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

checkAdmin(); // Garante que apenas administradores acessem

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = sanitizeInput($_POST['titulo']);
    $descricao = sanitizeInput($_POST['descricao']);
    $data_inicio = $_POST['data_inicio'];
    $horario_inicio = $_POST['horario_inicio'] ?: null;
    $data_fim = $_POST['data_fim'];
    $horario_fim = $_POST['horario_fim'] ?: null;
    $local = sanitizeInput($_POST['local'] ?? '');
    $valor = $_POST['valor'] ?: null;
    $data_limite_inscricao = $_POST['data_limite_inscricao'] ?: null;
    $responsavel_nome = sanitizeInput($_POST['responsavel_nome'] ?? '');
    $responsavel_contato = sanitizeInput($_POST['responsavel_contato'] ?? '');
    $tipo = $_POST['tipo'];
    $csrf_token = $_POST['csrf_token'];

    if (!verifyCSRFToken($csrf_token)) {
        $error = "Token CSRF inválido.";
    } elseif (empty($titulo) || empty($descricao) || empty($data_inicio) || empty($data_fim)) {
        $error = "Preencha todos os campos obrigatórios.";
    } else {
        $criado_por = $_SESSION['user_id'];
        if (createEvent(compact('titulo', 'descricao', 'data_inicio', 'horario_inicio', 'data_fim', 'horario_fim', 'local', 'valor', 'data_limite_inscricao', 'responsavel_nome', 'responsavel_contato', 'tipo', 'criado_por'))) {
            header("Location: eventos.php?msg=Evento cadastrado com sucesso!");
            exit;
        } else {
            $error = "Erro ao cadastrar evento. Verifique os dados e tente novamente.";
        }
    }
}

// Other function definitions

function createEvent($eventData)
{
    global $db;
    $sql = "INSERT INTO eventos (titulo, descricao, data_inicio, horario_inicio, data_fim, horario_fim, local, valor, data_limite_inscricao, responsavel_nome, responsavel_contato, tipo, criado_por) 
            VALUES(: titulo, : descricao, : data_inicio, : horario_inicio, : data_fim, : horario_fim, : local, : valor, : data_limite_inscricao, : responsavel_nome, : responsavel_contato, : tipo, : criado_por)";
    $stmt = $db->prepare($sql);
    return $stmt->execute($eventData);
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css">
    <script defer src="<?php echo PASTA_BASE; ?>public/js/admin_eventos.js"></script>
</head>

<body>
    <div class="admin-container">
        <h2>Cadastrar Novo Evento</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"> <?php echo $error; ?> </p>
        <?php endif; ?>
        <form action="cadastrar_evento.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <label for="titulo">Título do Evento:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required></textarea>

            <label for="data_inicio">Data de Início:</label>
            <input type="date" name="data_inicio" id="data_inicio" required>

            <label for="horario_inicio">Horário de Início:</label>
            <input type="time" name="horario_inicio" id="horario_inicio">

            <label for="data_fim">Data de Fim:</label>
            <input type="date" name="data_fim" id="data_fim" required>

            <label for="horario_fim">Horário de Fim:</label>
            <input type="time" name="horario_fim" id="horario_fim">

            <label for="local">Local:</label>
            <input type="text" name="local" id="local">

            <label for="valor">Valor:</label>
            <input type="number" name="valor" id="valor" step="0.01">

            <label for="data_limite_inscricao">Data Limite para Inscrição:</label>
            <input type="date" name="data_limite_inscricao" id="data_limite_inscricao">

            <label for="responsavel_nome">Nome do Responsável:</label>
            <input type="text" name="responsavel_nome" id="responsavel_nome">

            <label for="responsavel_contato">Contato do Responsável:</label>
            <input type="text" name="responsavel_contato" id="responsavel_contato">

            <label for="tipo">Público-Alvo:</label>
            <select name="tipo" id="tipo" required>
                <option value="publico">Público</option>
                <option value="restrito">Restrito</option>
            </select>

            <button type="submit">Cadastrar Evento</button>
        </form>
    </div>
    <script>

    </script>
</body>

</html>