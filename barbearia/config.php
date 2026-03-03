<?php
// ===============================
// CONFIGURAÇÃO DO BANCO DE DADOS
// ===============================

$host = "localhost";     // Servidor
$banco = "barbearia";     // Nome do banco
$usuario = "root";       // Usuário do MySQL
$senha = "";             // Senha do MySQL
$charset = "utf8mb4";    // Charset recomendado

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$banco;charset=$charset";

// Opções do PDO
$opcoes = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Ativa erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorno como array associativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Segurança extra
];

try {
    $pdo = new PDO($dsn, $usuario, $senha, $opcoes);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());}