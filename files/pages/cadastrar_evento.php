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

        // Criar evento no banco
        if (createEvent(compact('titulo', 'descricao', 'data_inicio', 'horario_inicio', 'data_fim', 'horario_fim', 'local', 'valor', 'data_limite_inscricao', 'responsavel_nome', 'responsavel_contato', 'tipo', 'criado_por'))) {
            $evento_id = $pdo->lastInsertId(); // Obtém o ID do evento recém-criado
            // Remove barras extras no caminho da imagem
            $caminho_imagem = rtrim(PASTA_BASE, '/') . "/public/uploads/$evento_id/capa.jpg";

            // Exibir no console do navegador
            echo "<script>console.log('Caminho da imagem: " . addslashes($caminho_imagem) . "');</script>";


            // Upload da imagem da capa
            if (!empty($_FILES['capa']['name'])) {
                $upload_dir = rtrim(PASTA_BASE, '/') . "/public/uploads/$evento_id/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $caminho_arquivo = $upload_dir . "capa.jpg"; // Salvando como JPG

                if (move_uploaded_file($_FILES['capa']['tmp_name'], $caminho_arquivo)) {
                    // Atualizar o evento com o caminho da imagem
                    updateEventImage($evento_id, $caminho_imagem);
                } else {
                    $error = "Erro ao fazer upload da imagem.";
                }
            }

            header("Location: eventos.php?msg=Evento cadastrado com sucesso!");
            exit;
        } else {
            $error = "Erro ao cadastrar evento. Verifique os dados e tente novamente.";
        }
    }
}

// Função para criar evento
function createEvent($eventData)
{
    global $pdo;
    $sql = "INSERT INTO eventos (titulo, descricao, data_inicio, horario_inicio, data_fim, horario_fim, local, valor, data_limite_inscricao, responsavel_nome, responsavel_contato, tipo, criado_por) 
            VALUES(:titulo, :descricao, :data_inicio, :horario_inicio, :data_fim, :horario_fim, :local, :valor, :data_limite_inscricao, :responsavel_nome, :responsavel_contato, :tipo, :criado_por)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($eventData);
}

// Função para atualizar o caminho da imagem no banco
function updateEventImage($evento_id, $caminho_imagem)
{
    global $pdo;
    $sql = "UPDATE eventos SET imagem_capa = :imagem_capa WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['imagem_capa' => $caminho_imagem, 'id' => $evento_id]);
}
?>




<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
    <script defer src="<?php echo PASTA_BASE; ?>public/js/admin_eventos.js?v=<?php echo time(); ?>"></script>
</head>
<header>
    <?php include __DIR__ . '/../templates/header.php'; ?>
</header>

<body>
    <div class=" main_container">
        <div class="container">

            <div class="admin-container">
                <h2>Cadastrar Novo Evento</h2>
                <?php if (isset($error)): ?>
                    <p class="error-message"> <?php echo $error; ?> </p>
                <?php endif; ?>
                <form action="cadastrar_evento.php" method="POST">
                    <label class="cadastro_evento">Descrição do Evento</label>
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="text" name="titulo" id="titulo" required placeholder="Título do Evento">
                    <textarea name="descricao" id="descricao" placeholder="Descrição" required></textarea>


                    <label class="cadastro_evento">Início do Evento</label>
                    <input type="date" name="data_inicio" id="data_inicio" placeholder="Data de Início" required>
                    <input type="time" name="horario_inicio" id="horario_inicio" placeholder="Horário de Início">
                    <label>Término do Evento</label>
                    <input type="date" name="data_fim" id="data_fim" placeholder="Data de Fim" required>
                    <input type="time" name="horario_fim" id="horario_fim" placeholder="Horário de Fim">

                    <label class="cadastro_evento">Sobre o Evento</label>
                    <input type="text" name="local" id="local" placeholder="Local do Evento">
                    <input type="number" name="valor" id="valor" step="0.01" placeholder="Valor do Evento">


                    <label class="cadastro_evento">Organizador</label>
                    <input type="text" name="responsavel_nome" id="responsavel_nome"
                        placeholder="Organizador do Evento">
                    <input type="text" name="responsavel_contato" id="responsavel_contato"
                        placeholder="Telefone ou E-mail do Responsável">
                    <select name="tipo" id="tipo" required placeholder="Público-Alvo">
                        <option value="publico">Público</option>
                        <option value="restrito">Restrito</option>
                    </select>

                    <label class="cadastro_evento">Data limite para Inscrição</label>
                    <input type="date" name="data_limite_inscricao" id="data_limite_inscricao"
                        placeholder="Data Limite para Inscrição">

                    <label class="cadastro_evento">Capa do Evento</label>
                    <input type="file" name="capa" accept="image/png, image/jpeg">

                    <button type="submit">Cadastrar Evento</button>
                </form>
            </div>
        </div>
    </div>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>

</html>