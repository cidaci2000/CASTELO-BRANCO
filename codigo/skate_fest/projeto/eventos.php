<?php
require_once 'config.php';

$eventos = $pdo->query("SELECT e.*, u.nome as organizador FROM eventos e LEFT JOIN usuarios u ON e.usuario_id = u.id ORDER BY e.data_evento DESC")->fetchAll();

$eventos_externos = [
    ['nome'=>'Skate World Tour 2024','descricao'=>'Circuito mundial de skate.','link'=>'https://www.worldskate.org/','imagem'=>'🌍'],
    ['nome'=>'Street League','descricao'=>'Principal liga de skate street.','link'=>'https://www.streetleague.com/','imagem'=>'🏙️'],
    ['nome'=>'X Games','descricao'=>'O evento extremo mais famoso.','link'=>'https://www.xgames.com/','imagem'=>'🔥'],
    ['nome'=>'Vans Park Series','descricao'=>'Competição de skate park.','link'=>'https://www.vansparkchamps.com/','imagem'=>'🏆'],
    ['nome'=>'Tampa Pro','descricao'=>'Evento tradicional de skate.','link'=>'https://www.tampapro.com/','imagem'=>'⚡'],
    ['nome'=>'Red Bull Skate','descricao'=>'Eventos Red Bull.','link'=>'https://www.redbull.com/br-pt/skateboarding','imagem'=>'🐂']
];
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>📅 EVENTOS DE SKATE</h1>
        <p>Confira os próximos eventos</p>
    </section>
    
    <h2 class="secao-titulo">🇧🇷 Eventos no Brasil</h2>
    <?php if(empty($eventos)): ?>
        <div class="card" style="text-align:center;">
            <p>Nenhum evento cadastrado ainda.</p>
        </div>
    <?php else: ?>
        <?php foreach($eventos as $e): ?>
            <div class="evento-card">
                <div class="evento-info">
                    <h3><?= htmlspecialchars($e['nome_evento']) ?></h3>
                    <p>📍 <?= htmlspecialchars($e['local_evento']) ?> - <?= htmlspecialchars($e['cidade']) ?>/<?= htmlspecialchars($e['estado']) ?></p>
                    <p>👤 <?= htmlspecialchars($e['organizador'] ?? 'Desconhecido') ?></p>
                </div>
                <div class="evento-data">
                    <div class="dia"><?= date('d', strtotime($e['data_evento'])) ?></div>
                    <div style="color:var(--light-blue);"><?= strtoupper(date('M', strtotime($e['data_evento']))) ?></div>
                    <?php if(!empty($e['link_evento'])): ?>
                        <a href="<?= htmlspecialchars($e['link_evento']) ?>" target="_blank" class="btn-link">ACESSAR</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <h2 class="secao-titulo" style="border-color:var(--light-blue);">🌍 Eventos Mundiais</h2>
    <div class="eventos-externos-grid">
        <?php foreach($eventos_externos as $e): ?>
            <div class="evento-externo-card">
                <span class="icone"><?= $e['imagem'] ?></span>
                <h3><?= htmlspecialchars($e['nome']) ?></h3>
                <p><?= htmlspecialchars($e['descricao']) ?></p>
                <a href="<?= htmlspecialchars($e['link']) ?>" target="_blank" class="btn">ACESSAR →</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'footer.php'; ?>