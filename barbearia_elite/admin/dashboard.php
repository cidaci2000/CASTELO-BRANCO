<?php
session_start();
require_once '../config.php';

// Verifica se está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Se for cliente, não pode acessar área admin
if ($_SESSION['usuario_tipo'] == 'cliente') {
    header("Location: ../cliente/app.php");
    exit();
}

// Página do admin
$nome_admin = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Barbearia Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <h1>💈 Barbearia Elite - Admin</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($nome_admin); ?>!</p>
    <a href="../logout.php" style="color: white;">Sair</a>
</header>

<main>
    <h2>Painel Administrativo</h2>
    <!-- Conteúdo do admin aqui -->
</main>

</body>
</html>