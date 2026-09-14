<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['usuario_id'])) {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="header">
    <div class="header-container">
        <h1 class="logo" onclick="window.location.href='index.php'" style="cursor:pointer;">CINE<span>VORTEX</span></h1>
    </div>
</header>

<main class="main-center">
    <div class="auth-container">
        <div class="auth-header">
            <h2>✨ Criar Nova Conta</h2>
            <p>Junte-se à comunidade CineVortex</p>
        </div>

        <div id="errorMessage" class="error-message" style="display:none;"></div>
        <div id="successMessage" class="success-message" style="display:none;"></div>

        <form class="auth-form" id="cadastroForm" novalidate>
            <div class="form-group">
                <label for="nome">👤 Nome completo *</label>
                <input type="text" id="nome" required placeholder="Seu nome completo">
            </div>
            <div class="form-group">
                <label for="email">📧 E-mail *</label>
                <input type="email" id="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label for="senha">🔒 Senha *</label>
                <input type="password" id="senha" required placeholder="Mínimo 6 caracteres">
            </div>
            <div class="form-group">
                <label for="confirmar_senha">🔒 Confirmar senha *</label>
                <input type="password" id="confirmar_senha" required placeholder="Digite novamente">
            </div>
            <button type="submit" class="btn-submit">Criar Minha Conta</button>
        </form>

        <div class="auth-footer">
            <p>Já tem conta? <a href="login.php">Faça login</a></p>
            <p style="margin-top:8px;"><a href="index.php">← Voltar</a></p>
        </div>
    </div>
</main>

<footer class="footer">
    <p>CineVortex © 2026</p>
</footer>

<script src="js/auth.js"></script>
</body>
</html>