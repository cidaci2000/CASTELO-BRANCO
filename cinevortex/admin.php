<?php
session_start();

// Verificar se usuário está logado e é admin
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <h1 class="logo">CINE<span>VORTEX</span> • ADMIN</h1>
            <nav>
                <a href="home.php">Home</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="admin-container">
            <h2>Painel Administrativo</h2>
            <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</p>
            
            <div class="admin-cards">
                <div class="card">
                    <h3>👥 Usuários</h3>
                    <p>Gerenciar usuários do sistema</p>
                    <a href="admin/usuarios.php">Gerenciar</a>
                </div>
                
                <div class="card">
                    <h3>🎬 Filmes</h3>
                    <p>Gerenciar catálogo de filmes</p>
                    <a href="admin/filmes.php">Gerenciar</a>
                </div>
                
                <div class="card">
                    <h3>📊 Relatórios</h3>
                    <p>Visualizar estatísticas</p>
                    <a href="admin/relatorios.php">Visualizar</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>CineVortex © 2026 - Painel Administrativo</p>
    </footer>
</body>
</html>