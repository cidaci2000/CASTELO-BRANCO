<?php
// Iniciar a sessão
session_start();

// Verificar se o arquivo de conexão existe
$conexao_path = __DIR__ . '/conexao.php';

if (file_exists($conexao_path)) {
    require_once $conexao_path;
} else {
    // Se o arquivo não existir, criar uma conexão simples
    try {
        $conn = new mysqli('localhost', 'root', '', 'candy_loves');
        if ($conn->connect_error) {
            throw new Exception("Erro de conexão: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        // Se não conseguir conectar, continuar mesmo assim
        error_log("Erro ao conectar ao banco: " . $e->getMessage());
    }
}

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se deseja destruir completamente a sessão, também apague o cookie da sessão.
// Isso destruirá a sessão, e não apenas os dados dela.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir a sessão
session_destroy();

// Redirecionar para a página de login
header("Location: /2026/castelo09/candy-loves/php/login.php");
exit();
?>