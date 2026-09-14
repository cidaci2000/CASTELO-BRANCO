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
    <title>CineVortex • Login</title>
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
            <h2>🎬 Bem-vindo de volta!</h2>
            <p>Faça login para continuar</p>
        </div>

        <div id="errorMessage" class="error-message" style="display:none;"></div>
        <div id="successMessage" class="success-message" style="display:none;"></div>

        <form class="auth-form" id="loginForm" novalidate>
            <div class="form-group">
                <label for="email">📧 E-mail</label>
                <input type="email" id="email" required placeholder="seu@email.com" autocomplete="email">
            </div>
            <div class="form-group">
                <label for="senha">🔒 Senha</label>
                <input type="password" id="senha" required placeholder="Sua senha" autocomplete="current-password">
            </div>
            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <div class="auth-footer">
            <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
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