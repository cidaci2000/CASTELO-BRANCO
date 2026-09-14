<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$logado      = !empty($_SESSION['usuario_id']);
$usuarioNome = $_SESSION['usuario_nome'] ?? null;

$filmes = [];
$avaliacoes = [];
$stats = [
    'total_filmes'     => 0,
    'total_usuarios'   => 0,
    'total_avaliacoes' => 0,
    'media_geral'      => null,
];
$erroBanco = null;

try {
    $pdo = getPDO();

    // Todos os filmes com média
    $filmes = $pdo->query("
        SELECT
            f.id, f.titulo, f.ano, f.genero, f.url_imagem,
            COUNT(a.id)             AS total_avaliacoes,
            ROUND(AVG(a.nota), 2)   AS media_nota
        FROM filmes f
        LEFT JOIN avaliacoes a ON a.filme_id = f.id
        GROUP BY f.id, f.titulo, f.ano, f.genero, f.url_imagem
        ORDER BY f.titulo ASC
    ")->fetchAll();

    // Últimas 10 avaliações da comunidade
    $avaliacoes = $pdo->query("
        SELECT
            a.id, a.nota, a.comentario, a.data_avaliacao,
            f.titulo AS filme_titulo, f.ano AS filme_ano, f.url_imagem AS filme_imagem,
            u.nome   AS usuario_nome
        FROM avaliacoes a
        INNER JOIN filmes   f ON f.id = a.filme_id
        INNER JOIN usuarios u ON u.id = a.usuario_id
        ORDER BY a.data_avaliacao DESC
        LIMIT 10
    ")->fetchAll();

    // Estatísticas gerais
    $stats = $pdo->query("
        SELECT
            (SELECT COUNT(*) FROM filmes)               AS total_filmes,
            (SELECT COUNT(*) FROM usuarios)             AS total_usuarios,
            (SELECT COUNT(*) FROM avaliacoes)           AS total_avaliacoes,
            (SELECT ROUND(AVG(nota),1) FROM avaliacoes) AS media_geral
    ")->fetch();

} catch (Throwable $e) {
    $erroBanco = $e->getMessage();
    error_log('[CineVortex index] ' . $erroBanco);
}

//$placeholderImg = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="300" height="450"><rect fill="#1a1a1a" width="300" height="450"/><text x="50%" y="50%" fill="#666" font-size="20" text-anchor="middle"></text></svg>';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Filmes em Destaque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php if ($erroBanco): ?>
    <div style="background:#7b1fa2;color:#fff;padding:12px 20px;font-family:monospace;font-size:13px;">
        ⚠️ Erro de banco: <?= e($erroBanco) ?>
    </div>
<?php endif; ?>

<header class="header">
    <div class="header-container">
        <h1 class="logo">CINE<span>VORTEX</span></h1>
        <nav class="nav-links">
            <a href="index.php" class="nav-link active">Filmes</a>

            <?php if ($logado): ?>
                <span class="user-greet">Olá, <strong><?= e($usuarioNome) ?></strong></span>
                <?php if (($_SESSION['usuario_tipo'] ?? '') === 'admin'): ?>
                    <a href="admin/index.php" class="nav-link">Admin</a>
                <?php endif; ?>
                <a href="home.php" class="nav-link">Minha Biblioteca</a>
                <a href="logout.php" class="nav-link">Sair</a>
            <?php else: ?>
                <a href="#login" class="nav-link">Entrar</a>
                <a href="cadastro.php" class="nav-link">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Hero Banner -->
<section class="hero-banner">
    <div class="banner-overlay"></div>
    <div class="banner-content">
        <div class="banner-text">
            <h2>CineVortex</h2>
            <h3>Sua comunidade de críticas cinematográficas</h3>
            <p>Compartilhe sua opinião, descubra novos favoritos e faça parte da maior rede de amantes do cinema!</p>

            <div class="banner-stats">
                <div class="stat">
                    <span class="stat-number"><?= (int)$stats['total_filmes'] ?></span>
                    <span class="stat-label">Filmes</span>
                </div>
                <div class="stat">
                    <span class="stat-number"><?= (int)$stats['total_avaliacoes'] ?></span>
                    <span class="stat-label">Avaliações</span>
                </div>
                <div class="stat">
                    <span class="stat-number">⭐ <?= $stats['media_geral'] ?: '—' ?></span>
                    <span class="stat-label">Média Global</span>
                </div>
                <div class="stat">
                    <span class="stat-number"><?= (int)$stats['total_usuarios'] ?></span>
                    <span class="stat-label">Críticos</span>
                </div>
            </div>

            <?php if (!$logado): ?>
                <div class="banner-button">
                    <a href="#login" class="btn-primary">📝 Entrar e Avaliar</a>
                </div>
            <?php else: ?>
                <div class="banner-button">
                    <a href="home.php" class="btn-primary">🎬 Ir para Minha Biblioteca</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Catálogo completo -->
<section class="destaques">
    <h2 class="section-title">🎬 Todos os Filmes</h2>

    <?php if (empty($filmes)): ?>
        <p style="text-align:center;color:#888;padding:40px;">Nenhum filme cadastrado ainda.</p>
    <?php else: ?>
        <div class="destaques-grid">
            <?php foreach ($filmes as $f): ?>
                <article class="filme-destaque-card">
                    <img src="<?= e($f['url_imagem'] ?: $placeholderImg) ?>"
                         alt="Pôster de <?= e($f['titulo']) ?>"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='<?= $placeholderImg ?>';">
                    <div class="filme-destaque-info">
                        <h3><?= e($f['titulo']) ?></h3>
                        <span class="filme-destaque-ano"><?= (int)$f['ano'] ?></span>
                        <?php if (!empty($f['media_nota'])): ?>
                            <span class="filme-destaque-media">
                                ★ <?= number_format((float)$f['media_nota'], 1, ',', '') ?>
                                <small>(<?= (int)$f['total_avaliacoes'] ?>)</small>
                            </span>
                        <?php else: ?>
                            <span class="filme-destaque-media" style="color:#666;">Sem avaliações</span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Avaliações recentes da comunidade -->
<section class="avaliacoes-publicas">
    <h2 class="section-title">⭐ Avaliações da Comunidade</h2>

    <?php if (empty($avaliacoes)): ?>
        <p style="text-align:center;color:#888;padding:40px;">
            Nenhuma avaliação ainda. Seja o primeiro a avaliar!
        </p>
    <?php else: ?>
        <div class="avaliacoes-lista">
            <?php foreach ($avaliacoes as $a): ?>
                <article class="avaliacao-publica-card">
                    <img src="<?= e($a['filme_imagem'] ?: $placeholderImg) ?>"
                         alt="" loading="lazy"
                         onerror="this.onerror=null;this.src='<?= $placeholderImg ?>';">
                    <div class="avaliacao-publica-info">
                        <h4><?= e($a['filme_titulo']) ?> <small>(<?= (int)$a['filme_ano'] ?>)</small></h4>

                        <div class="estrelas-mini">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span><?= $i <= (int)$a['nota'] ? '★' : '☆' ?></span>
                            <?php endfor; ?>
                            <strong><?= (int)$a['nota'] ?>/5</strong>
                        </div>

                        <p class="autor">
                            por <strong><?= e($a['usuario_nome']) ?></strong>
                            em <?= date('d/m/Y', strtotime($a['data_avaliacao'])) ?>
                        </p>

                        <?php if (!empty($a['comentario'])): ?>
                            <p class="comentario-item">"<?= e($a['comentario']) ?>"</p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- Login inline (só pra visitantes) -->
<?php if (!$logado): ?>
<section class="login-section" id="login">
    <div class="auth-container">
        <div class="auth-header">
            <h2>🎬 Bem-vindo de volta!</h2>
            <p>Faça login para avaliar e montar sua biblioteca</p>
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
        </div>
    </div>
</section>
<?php endif; ?>

<footer class="footer">
    <p>CineVortex © 2026 - Tudo sobre cinema em um só lugar</p>
</footer>

<script src="js/auth.js"></script>
</body>
</html>