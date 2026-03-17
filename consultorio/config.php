<?php
// config.php - Configurações do banco de dados
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Altere para seu usuário MySQL
define('DB_PASS', ''); // Altere para sua senha MySQL
define('DB_NAME', 'consultorio');

// Criar conexão com MySQL
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Configurar charset para UTF-8
$conn->set_charset("utf8mb4");

// Função para escapar strings e prevenir SQL injection
function escape($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Função para redirecionar
function redirect($url) {
    header("Location: $url");
    exit();
}

// Função para mostrar mensagens
function setMessage($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

function getMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'success';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        return "<div class='alert alert-$type'>$message</div>";
    }
    return '';
}
?>