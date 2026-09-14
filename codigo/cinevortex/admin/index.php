<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

$pdo = getPDO();
$nomeAdmin = $_SESSION['usuario_nome'] ?? 'Admin';

/* ===== Estatísticas gerais ===== */
$totUsuarios   = (int)$pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totFilmes     = (int)$pdo->query("SELECT COUNT(*) FROM filmes")->fetchColumn();
$totAvaliacoes = (int)$pdo->query("SELECT COUNT(*) FROM avaliacoes")->fetchColumn();
$mediaGeral    = $pdo->query("SELECT ROUND(AVG(nota),2) FROM avaliacoes")->fetchColumn();

/* ===== Lista de usuários ===== */
$usuarios = $pdo->query("
    SELECT u.id, u.nome, u.email, u.tipo, u.data_cadastro,
           (SELECT COUNT(*) FROM avaliacoes a WHERE a.usuario_id = u.id) AS total_aval
    FROM usuarios u
    ORDER BY u.data_cadastro DESC
")->fetchAll();

/* ===== Lista de filmes ===== */
$filmes = $pdo->query("
    SELECT f.id, f.titulo, f.ano, f.genero,
           COUNT(a.id)             AS total_aval,
           ROUND(AVG(a.nota), 2)   AS media
    FROM filmes f
    LEFT JOIN avaliacoes a ON a.filme_id = f.id
    GROUP BY f.id, f.titulo, f.ano, f.genero
    ORDER BY f.titulo ASC
")->fetchAll();

/* ===== Lista de avaliações ===== */
$avaliacoes = $pdo->query("
    SELECT a.id, a.nota, a.comentario, a.data_avaliacao,
           u.nome AS usuario_nome,
           f.titulo AS filme_titulo
    FROM avaliacoes a
    INNER JOIN usuarios u ON u.id = a.usuario_id
    INNER JOIN filmes   f ON f.id = a.filme_id
    ORDER BY a.data_avaliacao DESC
    LIMIT 50
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="header">
    <div class="header-container">
        <h1 class="logo">CINE<span>VORTEX</span> • ADMIN</h1>
        <nav class="header-buttons">
            <span class="user-greet">Olá, <strong><?= e($nomeAdmin) ?></strong></span>
            <a href="../index.php" class="btn-login">Site</a>
            <a href="../logout.php" class="btn-signup">Sair</a>
        </nav>
    </div>
</header>

<main class="main" style="padding:40px 20px;">
    <div style="max-width:1200px;margin:0 auto;">

        <h2 style="color:#e1bee7;margin-bottom:24px;">Painel Administrativo</h2>

        <!-- Stats -->
        <div class="stats-grid" style="margin-bottom:40px;">
            <div class="stat-card"><span class="stat-num"><?= $totUsuarios ?></span><span class="stat-lbl">👥 Usuários</span></div>
            <div class="stat-card"><span class="stat-num"><?= $totFilmes ?></span><span class="stat-lbl">🎬 Filmes</span></div>
            <div class="stat-card"><span class="stat-num"><?= $totAvaliacoes ?></span><span class="stat-lbl">⭐ Avaliações</span></div>
            <div class="stat-card"><span class="stat-num"><?= $mediaGeral ?: '—' ?></span><span class="stat-lbl">📈 Média geral</span></div>
        </div>

        <!-- USUÁRIOS -->
        <section class="admin-section">
            <h3 class="section-title" style="font-size:22px;text-align:left;margin-bottom:16px;">👥 Usuários (<?= count($usuarios) ?>)</h3>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Tipo</th>
                            <th>Avaliações</th>
                            <th>Cadastro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= (int)$u['id'] ?></td>
                                <td><?= e($u['nome']) ?></td>
                                <td><?= e($u['email']) ?></td>
                                <td><span class="badge badge-<?= e($u['tipo']) ?>"><?= e($u['tipo']) ?></span></td>
                                <td><?= (int)$u['total_aval'] ?></td>
                                <td><?= date('d/m/Y', strtotime($u['data_cadastro'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- FILMES -->
        <section class="admin-section" style="margin-top:40px;">
            <h3 class="section-title" style="font-size:22px;text-align:left;margin-bottom:16px;">🎬 Filmes (<?= count($filmes) ?>)</h3>
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Ano</th>
                            <th>Gênero</th>
                            <th>Avaliações</th>
                            <th>Média</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filmes as $f): ?>
                            <tr>
                                <td><?= (int)$f['id'] ?></td>
                                <td><?= e($f['titulo']) ?></td>
                                <td><?= (int)$f['ano'] ?></td>
                                <td><?= e($f['genero'] ?: '—') ?></td>
                                <td><?= (int)$f['total_aval'] ?></td>
                                <td><?= $f['media'] ? '★ ' . number_format((float)$f['media'], 1, ',', '') : '—' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- AVALIAÇÕES -->
        <section class="admin-section" style="margin-top:40px;">
            <h3 class="section-title" style="font-size:22px;text-align:left;margin-bottom:16px;">⭐ Avaliações (últimas 50)</h3>
            <?php if (empty($avaliacoes)): ?>
                <p style="color:#888;">Nenhuma avaliação ainda.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Filme</th>
                                <th>Nota</th>
                                <th>Comentário</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($avaliacoes as $a): ?>
                                <tr>
                                    <td><?= (int)$a['id'] ?></td>
                                    <td><?= e($a['usuario_nome']) ?></td>
                                    <td><?= e($a['filme_titulo']) ?></td>
                                    <td><?= str_repeat('★', (int)$a['nota']) . str_repeat('☆', 5 - (int)$a['nota']) ?></td>
                                    <td><?= e($a['comentario'] ?: '—') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($a['data_avaliacao'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<footer class="footer">
    <p>CineVortex © 2026 • Painel Administrativo</p>
</footer>
</body>
</html>