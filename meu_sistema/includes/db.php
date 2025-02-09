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
    // Tratamento de erro seguro
    if (DEBUG_MODE) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    } else {
        die("Erro na conexão com o banco de dados. Contate o administrador.");
    }
}

?>
