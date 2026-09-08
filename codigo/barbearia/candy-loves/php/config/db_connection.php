<?php
// Credenciais do banco de dados - CONFIGURADO PARA SEU SERVIDOR
$db_host = '127.0.0.1';        // Seu servidor: 127.0.0.1
$db_name = 'candy_loves';      // Nome do banco de dados
$db_user = 'root';             // Seu usuário: root
$db_pass = '';                 // Sua senha: (vazia)
$db_charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";

// Opções PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['erro' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]));
}
?>
