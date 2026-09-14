<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getPDO();
$usuarioId   = (int)$_SESSION['usuario_id'];
$usuarioNome = $_SESSION['usuario_nome'] ?? 'Usuário';
$usuarioTipo = $_SESSION['usuario_tipo'] ?? 'user';

// Todos os filmes com média
$filmes = $pdo->query("
    SELECT
        f.id, f.titulo, f.ano, f.descricao, f.genero, f.url_imagem,
        COUNT(a.id)             AS total_avaliacoes,
        ROUND(AVG(a.nota), 2)   AS media_nota
    FROM filmes f
    LEFT JOIN avaliacoes a ON a.filme_id = f.id
    GROUP BY f.id, f.titulo, f.ano, f.descricao, f.genero, f.url_imagem
    ORDER BY f.titulo ASC
")->fetchAll();

// Biblioteca pessoal
$stmt = $pdo->prepare("SELECT filme_id, status, nota, comentario FROM usuarios_filmes WHERE usuario_id = :uid");
$stmt->execute([':uid' => $usuarioId]);
$biblioteca = [];
$stats = ['total' => 0, 'assistidos' => 0, 'favoritos' => 0, 'quero_ver' => 0];
foreach ($stmt->fetchAll() as $row) {
    $biblioteca[(int)$row['filme_id']] = $row;
    $stats['total']++;
    if ($row['status'] === 'assistido') $stats['assistidos']++;
    if ($row['status'] === 'favorito')  $stats['favoritos']++;
    if ($row['status'] === 'quero_ver') $stats['quero_ver']++;
}

// Minhas últimas avaliações
$stmt = $pdo->prepare("
    SELECT a.id, a.nota, a.comentario, a.data_avaliacao,
           f.titulo AS filme_titulo, f.ano AS filme_ano
    FROM avaliacoes a
    INNER JOIN filmes f ON f.id = a.filme_id
    WHERE a.usuario_id = :uid
    ORDER BY a.data_avaliacao DESC
    LIMIT 5
");
$stmt->execute([':uid' => $usuarioId]);
$minhasAvaliacoes = $stmt->fetchAll();

//$placeholderImg = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="300" height="450"><rect fill="#1a1a1a" width="300" height="450"/><text x="50%" y="50%" fill="#666" font-size="20" text-anchor="middle">Sem capa</text></svg>';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Minha Biblioteca</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

<header class="header">
    <div class="header-container">
        <h1 class="logo">CINE<span>VORTEX</span></h1>
        <nav class="header-buttons">
            <span class="user-greet">Olá, <strong><?= e($usuarioNome) ?></strong></span>
            <?php if ($usuarioTipo === 'admin'): ?>
                <a href="admin/index.php" class="btn-login">Admin</a>
            <?php endif; ?>
            <a href="index.php" class="btn-login">Site</a>
            <a href="logout.php" class="btn-signup">Sair</a>
        </nav>
    </div>
</header>

<main class="main">
    <div class="dashboard">

        <!-- ===== Lado esquerdo: Avaliar filme ===== -->
        <section class="dashboard-left">
            <div class="page-title">
                <h2>Olá, <span><?= e($usuarioNome) ?></span></h2>
                <p>Avalie filmes e organize sua biblioteca</p>
            </div>

            <div class="avaliacao">
                <h3 class="section-title">⭐ Avaliar um Filme</h3>
                <form class="avaliacao-container" id="formAvaliacao" onsubmit="return false;">
                    <div class="form-group">
                        <label for="filme">Selecione o filme:</label>
                        <select id="filme" required>
                            <option value="">🎬 Escolha um filme</option>
                            <?php foreach ($filmes as $f): ?>
                                <option value="<?= (int)$f['id'] ?>">
                                    <?= e($f['titulo']) ?> (<?= (int)$f['ano'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="estrelas-container">
                        <label>Sua nota:</label>
                        <div class="estrelas">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="estrela" data-nota="<?= $i ?>">☆</span>
                            <?php endfor; ?>
                        </div>
                        <span class="nota-texto" id="notaTexto">Clique nas estrelas</span>
                    </div>

                    <div class="form-group">
                        <label for="comentario">Comentário (opcional):</label>
                        <textarea id="comentario" placeholder="O que você achou?" rows="3"></textarea>
                    </div>

                    <button type="button" class="btn-submit" id="btnEnviarAvaliacao">Enviar Avaliação</button>
                </form>
            </div>

            <?php if (!empty($minhasAvaliacoes)): ?>
            <div class="ultimas-avaliacoes" style="margin-top:28px;">
                <h3 class="section-title">📝 Minhas Últimas Avaliações</h3>
                <div class="avaliacoes-lista-mini">
                    <?php foreach ($minhasAvaliacoes as $a): ?>
                        <div class="avaliacao-mini">
                            <strong><?= e($a['filme_titulo']) ?></strong>
                            <div class="estrelas-mini">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span><?= $i <= (int)$a['nota'] ? '★' : '☆' ?></span>
                                <?php endfor; ?>
                            </div>
                            <small><?= date('d/m/Y', strtotime($a['data_avaliacao'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>

        <!-- ===== Lado direito: Biblioteca ===== -->
        <section class="dashboard-right">
            <div class="hero-stats">
                <button class="stat" data-filtro="todos">
                    <span class="stat-number" id="statTotal"><?= $stats['total'] ?></span>
                    <span class="stat-label">Na biblioteca</span>
                </button>
                <button class="stat" data-filtro="assistidos">
                    <span class="stat-number" id="statAssistidos"><?= $stats['assistidos'] ?></span>
                    <span class="stat-label">Assistidos</span>
                </button>
                <button class="stat" data-filtro="favoritos">
                    <span class="stat-number" id="statFavoritos"><?= $stats['favoritos'] ?></span>
                    <span class="stat-label">Favoritos</span>
                </button>
                <button class="stat" data-filtro="quero-ver">
                    <span class="stat-number" id="statQueroVer"><?= $stats['quero_ver'] ?></span>
                    <span class="stat-label">Quero Ver</span>
                </button>
            </div>

            <div class="dashboard-actions">
                <button class="btn-primary" id="btnAbrirModalAdicionar">
                    ➕ Adicionar Novo Filme
                </button>
            </div>

            <div class="filtros-rapidos">
                <button class="filtro-btn active" data-filtro="todos">🎬 Todos os filmes</button>
                <button class="filtro-btn" data-filtro="quero-ver">⏰ Quero Ver</button>
                <button class="filtro-btn" data-filtro="favoritos">⭐ Favoritos</button>
                <button class="filtro-btn" data-filtro="assistidos">✅ Assistidos</button>
            </div>

            <div class="filmes-grid" id="todos-filmes-grid">
                <?php foreach ($filmes as $f):
                    $bib    = $biblioteca[(int)$f['id']] ?? null;
                    $status = $bib['status'] ?? '';
                ?>
                    <article class="filme-card"
                             data-id="<?= (int)$f['id'] ?>"
                             data-status="<?= e($status) ?>"
                             data-titulo="<?= e($f['titulo']) ?>"
                             data-ano="<?= (int)$f['ano'] ?>"
                             data-genero="<?= e($f['genero'] ?? '') ?>"
                             data-descricao="<?= e($f['descricao'] ?? '') ?>">
                        <img src="<?= e($f['url_imagem'] ?: $placeholderImg) ?>"
                             alt="<?= e($f['titulo']) ?>" loading="lazy"
                             onerror="this.onerror=null;this.src='<?= $placeholderImg ?>';">
                        <div class="filme-info">
                            <h4><?= e($f['titulo']) ?></h4>
                            <span class="filme-ano"><?= (int)$f['ano'] ?></span>
                            <?php if ($f['media_nota'] !== null): ?>
                                <span class="filme-media">★ <?= number_format((float)$f['media_nota'], 1, ',', '') ?></span>
                            <?php endif; ?>
                            <?php if ($status): ?>
                                <span class="filme-status status-<?= e($status) ?>">
                                    <?php
                                        echo match ($status) {
                                            'assistido' => '✅ Assistido',
                                            'favorito'  => '⭐ Favorito',
                                            default     => '⏰ Quero Ver',
                                        };
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="filme-acoes">
                            <button class="btn-acao btn-status">Status</button>
                            <button class="btn-acao btn-detalhes">Detalhes</button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <!-- =========================================================
         MODAL: Adicionar Filme
         ========================================================= -->
    <div id="modalAdicionarFilme" class="modal" role="dialog" aria-modal="true">
        <div class="modal-content">
            <button type="button" class="modal-close" id="btnFecharModalAdicionar" aria-label="Fechar">&times;</button>

            <h3 class="modal-titulo">🎬 Adicionar Novo Filme</h3>
            <p class="modal-subtitulo">O filme ficará disponível para todos os usuários avaliarem.</p>

            <div id="modalErro" class="error-message" style="display:none;"></div>

            <form class="modal-form" id="formAdicionarFilme" onsubmit="return false;">
                <div class="form-group">
                    <label for="novoTitulo">Título *</label>
                    <input type="text" id="novoTitulo" required placeholder="Ex: Interestelar" maxlength="200">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="novoAno">Ano *</label>
                        <input type="number" id="novoAno" required placeholder="2014" min="1888" max="2100">
                    </div>
                    <div class="form-group">
                        <label for="novoGenero">Gênero</label>
                        <input type="text" id="novoGenero" placeholder="Ação, Drama" maxlength="100">
                    </div>
                </div>

                <div class="form-group">
                    <label for="novaImagem">URL do Pôster</label>
                    <input type="url" id="novaImagem" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label for="novaDescricao">Descrição</label>
                    <textarea id="novaDescricao" rows="3" placeholder="Sinopse do filme..."></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancelar" id="btnCancelarAdicionar">Cancelar</button>
                    <button type="submit" class="btn-submit" id="btnSalvarFilme">Salvar Filme</button>
                </div>
            </form>
        </div>
    </div>

</main>

<footer class="footer">
    <p>CineVortex © 2026</p>
</footer>

<script>window.CINEVORTEX = { logado: true, usuarioId: <?= $usuarioId ?> };</script>
<script src="js/home.js"></script>
</body>
</html>