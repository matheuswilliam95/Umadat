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
    <form action="processa_cadastro.php" method="POST">
        <label for="nome">Nome da Congregação:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="regional">Regional:</label>
        <select id="regional" name="regional_id">
            <option value="">Selecione</option>
            <?php
            include 'conexao.php';
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