<?php

// Definição do fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'nome_do_banco');
define('DB_USER', 'usuario');
define('DB_PASS', 'senha_segura');

// Caminho base do sistema
define('BASE_URL', 'https://seusite.com/');

// Ativação de exibição de erros apenas em ambiente de desenvolvimento
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Chave secreta para hash e tokens
define('SECRET_KEY', 'sua_chave_secreta_super_segura');

// Inclusão do arquivo de conexão com o banco de dados
require_once __DIR__ . '/db.php';

?>