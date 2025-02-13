<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (isset($_GET['congregacao_id'])) {
    $congregacao_id = intval($_GET['congregacao_id']);

    global $pdo;
    $stmt = $pdo->prepare("SELECT id, nome FROM conjuntos WHERE congregacao_id = ?");
    $stmt->execute([$congregacao_id]);
    $conjuntos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($conjuntos);
}
?>
