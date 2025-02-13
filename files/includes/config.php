<?php

// Definição do fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Carregar configurações do config.ini
$config = parse_ini_file(__DIR__ . '/../includes/config.ini', true);

if (!$config) {
    die("Erro ao carregar o arquivo config.ini");
}

// Configurações do banco de dados
define('DB_HOST', $config['database']['host']);
define('DB_NAME', $config['database']['name']);
define('DB_USER', $config['database']['user']);
define('DB_PASS', $config['database']['pass']);
define('SITE_NAME', $config['system']['site_name']);
define('PASTA_BASE', $config['system']['pasta_base']);

// Caminho base do sistema
define('BASE_URL', $config['system']['base_url']);

// Ativação de exibição de erros apenas em ambiente de desenvolvimento
define('DEBUG_MODE', isset($config['system']['mode_debug']) ? filter_var($config['system']['mode_debug'], FILTER_VALIDATE_BOOLEAN) : false);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Chave secreta para hash e tokens
define('SECRET_KEY', $config['system']['secret_key']);

// Inclusão do arquivo de conexão com o banco de dados
require_once __DIR__ . '/db.php';

?>