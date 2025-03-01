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
    $data_fim = $_POST['data_fim'] ?: null;
    $horario_fim = $_POST['horario_fim'] ?: null;
    $local = sanitizeInput($_POST['local'] ?? '');
    $valor = $_POST['valor'] ?: null;
    $data_limite_inscricao = $_POST['data_limite_inscricao'] ?: null;
    $responsavel_nome = sanitizeInput($_POST['responsavel_nome'] ?? '');
    $responsavel_contato = sanitizeInput($_POST['responsavel_contato'] ?? '');
    $instagram_username = sanitizeInput($_POST['instagram_username'] ?? '');
    $tipo = $_POST['tipo'];
    $congregacao = $_POST['congregacao'];
    $conjunto = $_POST['conjunto'];
    $csrf_token = $_POST['csrf_token'];

    if (!verifyCSRFToken($csrf_token)) {
        $error = "Token CSRF inválido.";
    } elseif (empty($titulo) || empty($descricao) || empty($data_inicio) || empty($horario_inicio) || empty($tipo)) {
        $error = "Preencha todos os campos obrigatórios.";
    } else {
        $criado_por = $_SESSION['user_id'];

        // Criar evento no banco
        if (createEvent(compact('titulo', 'descricao', 'data_inicio', 'horario_inicio', 'data_fim', 'horario_fim', 'local', 'valor', 'data_limite_inscricao', 'responsavel_nome', 'responsavel_contato', 'tipo', 'criado_por', 'instagram_username', 'congregacao', 'conjunto'))) {
            $evento_id = $pdo->lastInsertId(); // Obtém o ID do evento recém-criado
            $upload_dir = __DIR__ . "/../public/uploads/$evento_id/";
            $upload_dir = rtrim($upload_dir, '/') . '/';
            $caminho_banco = "public/uploads/$evento_id/capa.jpg"; // Caminho salvo no banco

            error_log("Upload dir: " . $upload_dir);

            if (!empty($_FILES['capa']['name'])) {
                if (!is_dir($upload_dir)) {
                    if (!mkdir($upload_dir, 0777, true)) {
                        error_log("Erro ao criar diretório: " . $upload_dir);
                    } else {
                        error_log("Diretório criado: " . $upload_dir);
                    }
                }

                $caminho_arquivo = $upload_dir . "capa.jpg";

                if ($_FILES['capa']['error'] === UPLOAD_ERR_OK) {
                    if (!move_uploaded_file($_FILES['capa']['tmp_name'], $caminho_arquivo)) {
                        error_log("Erro ao mover o arquivo para " . $caminho_arquivo);
                    } else {
                        error_log("Arquivo movido com sucesso: " . $caminho_arquivo);
                        updateEventImage($evento_id, $caminho_banco);
                        echo "<script>alert('Evento registrado com sucesso.'); window.location.href='evento.php?id=$evento_id';</script>";
                    }
                } else {
                    error_log("Erro no upload: Código " . $_FILES['capa']['error']);
                }
            }
        } else {
            $error = "Erro ao cadastrar evento. Verifique os dados e tente novamente.";
            echo "<script>alert('Erro ao cadastrar evento. Verifique os dados e tente novamente.');</script>";
        }
    }
}

// Função para criar evento
function createEvent($eventData)
{
    global $pdo;
    $sql = "INSERT INTO eventos (titulo, descricao, data_inicio, horario_inicio, data_fim, horario_fim, local, valor, data_limite_inscricao, responsavel_nome, responsavel_contato, tipo, criado_por, instagram_username, congregacao, conjunto) 
            VALUES(:titulo, :descricao, :data_inicio, :horario_inicio, :data_fim, :horario_fim, :local, :valor, :data_limite_inscricao, :responsavel_nome, :responsavel_contato, :tipo, :criado_por, :instagram_username, :congregacao, :conjunto)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($eventData);
}

// Função para atualizar o caminho da imagem no banco
function updateEventImage($evento_id, $caminho_banco)
{
    global $pdo;
    $sql = "UPDATE eventos SET imagem_capa = :imagem_capa WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['imagem_capa' => $caminho_banco, 'id' => $evento_id]);
}


$congregacoes = getCongregacoes();

?>




<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo PASTA_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
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
                <form action="cadastrar_evento.php" method="POST" enctype="multipart/form-data">
                    <label class="cadastro_evento">Descrição do Evento</label>
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="text" name="titulo" id="titulo" required placeholder="Título do Evento">
                    <textarea name="descricao" id="descricao" placeholder="Descrição" required></textarea>


                    <label class="cadastro_evento">Início do Evento</label>
                    <input type="date" name="data_inicio" id="data_inicio" placeholder="Data de Início" required>
                    <input type="time" name="horario_inicio" id="horario_inicio" placeholder="Horário de Início">
                    <label class="cadastro_evento">Término do Evento</label>
                    <input type="date" name="data_fim" id="data_fim" placeholder="Data de Fim">
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

                    <!-- Input do instagram -->
                    <label class="cadastro_evento">Instagram</label>
                    <input type="text" name="instagram_username" id="instagram_username" placeholder="Instagram">

                    <label class="cadastro_evento">Data limite para Inscrição</label>
                    <input type="date" name="data_limite_inscricao" id="data_limite_inscricao"
                        placeholder="Data Limite para Inscrição">


                    <!-- Input da Congregação -->
                    <select name="congregacao" id="cadastro_congregacao" required>
                        <option value="" disabled selected>Selecione uma Congregação</option>
                        <?php foreach ($congregacoes as $congregacao): ?>
                            <option value="<?php echo $congregacao['id']; ?>">
                                <?php echo htmlspecialchars($congregacao['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Input do conjunto -->
                    <select name="conjunto" id="cadastro_conjunto" aria-placeholder="Grupo ou Conjunto" required>
                        <option value="">Selecione uma congregação primeiro</option>
                    </select>


                    <label class="cadastro_evento">Capa do Evento</label>
                    <input type="file" name="capa" class="upload_file_button" accept="image/png, image/jpeg">

                    <button type="submit" class="button">Cadastrar Evento</button>

                </form>
            </div>
        </div>
    </div>
    <script defer src="/umadat/files/public/js/admin_eventos.js?v=<?php echo time(); ?>"></script>
</body>
<footer>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
</footer>

</html>