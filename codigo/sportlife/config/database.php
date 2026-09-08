<?php
// Configuração do banco de dados
$host = 'localhost';
$user = 'root'; // Seu usuário do MySQL
$password = ''; // Sua senha do MySQL (deixe em branco se não tiver)
$database = 'sportlife'; // Nome do seu banco de dados

// Criar conexão
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Definir charset para UTF-8
$conn->set_charset("utf8mb4");
?>