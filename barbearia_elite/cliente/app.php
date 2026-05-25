<?php
session_start();
require_once '../config.php';

// Verifica se está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Se for admin, não pode acessar área do cliente
if ($_SESSION['usuario_tipo'] == 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Página do cliente
$nome_cliente = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente - Barbearia Elite</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <h1>💈 Barbearia Elite</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($nome_cliente); ?>!</p>
    <a href="../logout.php" style="color: white;">Sair</a>
</header>

<main>
    <h2>Dashboard do Cliente</h2>
    <!-- Conteúdo do cliente aqui -->
</main>

</body>
</html>