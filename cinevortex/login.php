<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <h1 class="logo" onclick="window.location.href='home.php'" style="cursor: pointer;">CINE<span>VORTEX</span></h1>
        </div>
    </header>

    <main class="main">
        <div class="auth-container">
            <div class="auth-header">
                <h2>🎬 Bem-vindo de volta!</h2>
                <p>Faça login para continuar</p>
            </div>

            <div id="errorMessage" class="error-message" style="display: none;"></div>
            <div id="successMessage" class="success-message" style="display: none;"></div>

            <form class="auth-form" id="loginForm">
                <div class="form-group">
                    <label for="email">📧 E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="seu@email.com">
                </div>

                <div class="form-group">
                    <label for="senha">🔒 Senha</label>
                    <input type="password" id="senha" name="senha" required placeholder="Sua senha">
                </div>

                <div class="form-group checkbox-group">
                    <input type="checkbox" id="lembrar" name="lembrar">
                    <label for="lembrar">Lembrar-me</label>
                </div>

                <button type="submit" class="btn-login-submit">Entrar</button>
            </form>

            <div class="auth-footer">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>

            <div class="back-home">
                <a href="home.php">← Voltar para o início</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>CineVortex © 2026 - Tudo sobre cinema em um só lugar</p>
    </footer>

    <script src="js/login.js"></script>
</body>
</html>