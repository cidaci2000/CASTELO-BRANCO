<?php
// config.php
$host = 'localhost';
$user = 'root'; // Altere para seu usuário
$password = ''; // Altere para sua senha
$database = 'elite';

// Conexão MySQLi
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

