<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Congregação</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Cadastro de Nova Congregação</h2>
    <?php
    include 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = trim($_POST['nome']);
        $regional_id = !empty($_POST['regional_id']) ? $_POST['regional_id'] : NULL;

        if (!empty($nome)) {
            $query = "INSERT INTO congregacoes (nome, regional_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "si", $nome, $regional_id);

            if (mysqli_stmt_execute($stmt)) {
                echo "<p>Congregação cadastrada com sucesso!</p>";
            } else {
                echo "<p>Erro ao cadastrar: " . mysqli_error($conn) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p>O nome da congregação é obrigatório!</p>";
        }
    }
    ?>
    <form action="" method="POST">
        <label for="nome">Nome da Congregação:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="regional">Regional:</label>
        <select id="regional" name="regional_id">
            <option value="">Selecione</option>
            <?php
            $query = "SELECT id, nome FROM hierarquia ORDER BY nome";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
            }
            ?>
        </select>

        <button type="submit">Cadastrar</button>
    </form>
</body>

</html>