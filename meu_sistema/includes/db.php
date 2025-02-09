<?php

require_once __DIR__ . '/config.php';

try {
    // Configuração da conexão PDO com opções de segurança
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Registra erro no log se estiver em produção
    if (!DEBUG_MODE) {
        error_log("Erro de conexão com o banco: " . $e->getMessage());
    }

    // Exibe erro seguro
    $errorMessage = DEBUG_MODE ? "Erro na conexão com o banco de dados: " . $e->getMessage()
        : "Erro na conexão com o banco de dados. Contate o administrador.";

    // Redireciona para uma página de erro personalizada
    header("Location: /error.php?msg=" . urlencode($errorMessage));
    exit;
}

?>