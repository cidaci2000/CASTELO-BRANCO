<?php
// Configuração do banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'consultorio');

// Conexão com o banco de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Configurar charset para UTF-8
$conn->set_charset("utf8mb4");

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true;
}

// Função para verificar se é admin
function isAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

// Função para proteger páginas (NÃO redireciona automaticamente)
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Função para proteger páginas de admin
function requireAdmin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

// Função para sanitizar dados
function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($data)));
}
?>