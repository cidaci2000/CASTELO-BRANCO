<?php
require_once 'config.php';
$paginaAtual = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkateFest Brasil</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-container">
        <a href="index.php" class="logo" style="text-decoration:none;">
            <h1>SKATE FEST</h1>
            <p>BRASIL</p>
        </a>
        <div class="nav-links">
            <?php if(!isLogado()): ?>
                <a href="index.php" class="<?= $paginaAtual == 'index' ? 'active' : '' ?>">HOME</a>
                <a href="eventos.php" class="<?= $paginaAtual == 'eventos' ? 'active' : '' ?>">📅 EVENTOS</a>
                <a href="dicas.php" class="<?= $paginaAtual == 'dicas' ? 'active' : '' ?>">💡 DICAS</a>
                <button class="btn-login" onclick="abrirModalLogin()">ENTRAR / CADASTRAR</button>
            <?php else: ?>
                <?php if(isAdmin()): ?>
                    <a href="dashboard_admin.php" class="<?= $paginaAtual == 'dashboard_admin' ? 'active' : '' ?>">👑 DASHBOARD</a>
                    <a href="usuarios.php" class="<?= $paginaAtual == 'usuarios' ? 'active' : '' ?>">👥 USUÁRIOS</a>
                <?php elseif(isRepresentante()): ?>
                    <a href="dashboard_rep.php" class="<?= $paginaAtual == 'dashboard_rep' ? 'active' : '' ?>">👔 MEU PAINEL</a>
                <?php elseif(isCompetidor()): ?>
                    <a href="dashboard_comp.php" class="<?= $paginaAtual == 'dashboard_comp' ? 'active' : '' ?>">🛹 MEU PAINEL</a>
                <?php endif; ?>
                
                <a href="eventos.php" class="<?= $paginaAtual == 'eventos' ? 'active' : '' ?>">📅 EVENTOS</a>
                <a href="notas.php" class="<?= $paginaAtual == 'notas' ? 'active' : '' ?>">📊 NOTAS</a>
                <a href="dicas.php" class="<?= $paginaAtual == 'dicas' ? 'active' : '' ?>">💡 DICAS</a>
                
                <?php if(isCompetidor() || isAdmin()): ?>
                    <a href="inscricao.php" class="<?= $paginaAtual == 'inscricao' ? 'active' : '' ?>">📝 INSCRIÇÃO</a>
                <?php endif; ?>
                
                <a href="perfil.php" class="<?= $paginaAtual == 'perfil' ? 'active' : '' ?>">👤 PERFIL</a>
                
                <div class="user-info">
                    <span class="avatar-placeholder"><?= isAdmin() ? '👑' : (isRepresentante() ? '👔' : '🛹') ?></span>
                    <span class="user-name"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
                    <span class="user-badge"><?= isAdmin() ? 'Admin' : (isRepresentante() ? 'Rep.' : 'Skater') ?></span>
                    <a href="login.php?logout=1" class="btn-logout">🚪 SAIR</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="toast-container" id="toastContainer"></div>

<script>
function abrirModalLogin() {
    window.location.href = 'index.php?login=1';
}
function mostrarToast(msg, tipo) {
    const c = document.getElementById('toastContainer');
    if (!c) return;
    const t = document.createElement('div');
    t.className = 'toast ' + tipo;
    t.textContent = msg;
    c.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>