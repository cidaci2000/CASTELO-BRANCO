<?php
require_once 'config.php';
exigirAdmin();

// Estatísticas
$stats = $pdo->query("SELECT 
    (SELECT COUNT(*) FROM usuarios) as total_usuarios,
    (SELECT COUNT(*) FROM usuarios WHERE status = 'pendente') as pendentes,
    (SELECT COUNT(*) FROM usuarios WHERE tipo = 'representante') as representantes,
    (SELECT COUNT(*) FROM usuarios WHERE tipo = 'competidor') as competidores,
    (SELECT COUNT(*) FROM eventos) as eventos,
    (SELECT COUNT(*) FROM skatistas_eventos) as inscricoes
")->fetch();
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>👑 PAINEL ADMINISTRATIVO</h1>
        <p>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></p>
    </section>
    
    <div class="dashboard-stats">
        <div class="dash-stat"><span class="icon">👥</span><div class="number"><?= $stats['total_usuarios'] ?></div><div class="label">Usuários</div></div>
        <div class="dash-stat"><span class="icon">⏳</span><div class="number"><?= $stats['pendentes'] ?></div><div class="label">Pendentes</div></div>
        <div class="dash-stat"><span class="icon">👔</span><div class="number"><?= $stats['representantes'] ?></div><div class="label">Representantes</div></div>
        <div class="dash-stat"><span class="icon">🛹</span><div class="number"><?= $stats['competidores'] ?></div><div class="label">Competidores</div></div>
        <div class="dash-stat"><span class="icon">📅</span><div class="number"><?= $stats['eventos'] ?></div><div class="label">Eventos</div></div>
        <div class="dash-stat"><span class="icon">📝</span><div class="number"><?= $stats['inscricoes'] ?></div><div class="label">Inscrições</div></div>
    </div>
    
    <h3 class="secao-titulo">⚡ Ações Rápidas</h3>
    <div class="quick-actions">
        <a href="usuarios.php" class="quick-action"><span class="icon">👥</span><h4>Gerenciar Usuários</h4><p>Aprovar, editar e remover</p></a>
        <a href="notas.php" class="quick-action"><span class="icon">📊</span><h4>Ver Notas</h4><p>Acompanhar competição</p></a>
        <a href="eventos.php" class="quick-action"><span class="icon">📅</span><h4>Eventos</h4><p>Ver todos os eventos</p></a>
        <a href="perfil.php" class="quick-action"><span class="icon">👤</span><h4>Meu Perfil</h4><p>Atualizar dados</p></a>
    </div>
</main>

<?php include 'footer.php'; ?>