<?php
require_once 'config.php';
exigirCompetidor();

$stmt = $pdo->prepare("SELECT * FROM skatistas_eventos WHERE usuario_id = ? ORDER BY data_inscricao DESC");
$stmt->execute([$_SESSION['usuario_id']]);
$minhas_inscricoes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM skatistas_competicao WHERE usuario_id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$minhas_competicoes = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>🛹 PAINEL DO COMPETIDOR</h1>
        <p>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</p>
    </section>
    
    <div class="dashboard-stats">
        <div class="dash-stat"><span class="icon">📝</span><div class="number"><?= count($minhas_inscricoes) ?></div><div class="label">Inscrições</div></div>
        <div class="dash-stat"><span class="icon">🏆</span><div class="number"><?= count($minhas_competicoes) ?></div><div class="label">Competições</div></div>
        <div class="dash-stat"><span class="icon">⭐</span><div class="number"><?= isset($minhas_competicoes[0]) ? $minhas_competicoes[0]['media_geral'] : '0.0' ?></div><div class="label">Melhor Média</div></div>
    </div>
    
    <h3 class="secao-titulo">⚡ Ações Rápidas</h3>
    <div class="quick-actions">
        <a href="inscricao.php" class="quick-action"><span class="icon">📝</span><h4>Inscrever-se</h4><p>Participe de um evento</p></a>
        <a href="notas.php" class="quick-action"><span class="icon">📊</span><h4>Ver Notas</h4><p>Acompanhe sua pontuação</p></a>
        <a href="eventos.php" class="quick-action"><span class="icon">📅</span><h4>Eventos</h4><p>Ver agenda</p></a>
        <a href="perfil.php" class="quick-action"><span class="icon">👤</span><h4>Meu Perfil</h4><p>Atualizar dados</p></a>
    </div>
    
    <?php if(!empty($minhas_inscricoes)): ?>
        <h3 class="secao-titulo">📋 Minhas Inscrições</h3>
        <?php foreach($minhas_inscricoes as $i): ?>
            <div class="inscricao-card">
                <div>
                    <div class="evento-nome"><?= htmlspecialchars($i['nome_evento']) ?></div>
                    <div class="evento-data">📅 <?= date('d/m/Y', strtotime($i['data_inscricao'])) ?></div>
                </div>
                <span class="evento-categoria"><?= htmlspecialchars($i['categoria']) ?></span>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>