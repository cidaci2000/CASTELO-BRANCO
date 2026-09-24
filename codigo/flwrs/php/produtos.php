<?php
require_once '../includes/functions.php';

/* ====== FILTROS E PAGINAÇÃO ====== */
$busca  = trim($_GET['q'] ?? '');
$modelo = $_GET['modelo'] ?? '';
$ordem  = $_GET['ordem'] ?? 'recentes';
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 9;
$offset = ($pagina - 1) * $porPagina;

$filtros = [
    'busca'  => $busca,
    'modelo' => $modelo,
    'ordem'  => $ordem,
    'limite' => $porPagina,
    'offset' => $offset,
];

$produtos = getProdutosFiltrados($filtros);
$total    = contarProdutos(['busca' => $busca, 'modelo' => $modelo]);
$paginas  = max(1, (int)ceil($total / $porPagina));
$flash    = getFlash();

/* ====== MONTA QUERY STRING PARA PAGINAÇÃO ====== */
function urlPagina($p) {
    $params = $_GET;
    $params['pagina'] = $p;
    return '?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flwrs · nossas flores</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/carrinho.css">
    <style>
        /* ===== VARIÁVEIS ===== */
        :root {
            --coral: #e8857d;
            --pink: #f4a8a0;
            --light-pink: #f5d5d0;
            --soft: #fdf6f5;
            --dark: #333;
            --gray: #777;
            --shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: var(--soft);
            color: var(--dark);
            margin: 0;
            line-height: 1.5;
        }

        /* ===== HEADER ===== */
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .header-left { display: flex; align-items: center; gap: 1rem; }
        .back-button {
            display: flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--soft); color: var(--dark);
            text-decoration: none; transition: background .2s;
        }
        .back-button:hover { background: var(--light-pink); }
        .logo-word {
            font-size: 1.6rem; font-weight: 300; letter-spacing: 2px;
        }
        .logo-word strong { color: var(--coral); }
        .tagline-header {
            font-size: .75rem; color: var(--gray); font-style: italic;
        }
        .nav-menu {
            display: flex; align-items: center; gap: 1.2rem; flex-wrap: wrap;
        }
        .nav-menu a {
            color: var(--dark); text-decoration: none;
            font-size: .95rem; transition: color .2s;
        }
        .nav-menu a:hover { color: var(--coral); }
        .cart-icon-wrapper {
            position: relative; display: inline-flex;
            align-items: center; justify-content: center;
            width: 40px; height: 40px;
            background: var(--soft); border-radius: 50%;
        }
        .cart-count-badge {
            position: absolute; top: -4px; right: -4px;
            min-width: 20px; height: 20px;
            background: var(--coral); color: #fff;
            border-radius: 50%; font-size: .7rem;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; padding: 0 5px;
        }

        /* ===== HERO ===== */
        .page-hero {
            text-align: center;
            padding: 2.5rem 1rem 1.5rem;
        }
        .page-hero h1 {
            font-size: 2rem; font-weight: 400; margin: 0 0 .5rem;
        }
        .page-hero h1 span { color: var(--coral); font-weight: 600; }
        .page-hero p { color: var(--gray); margin: 0; }

        /* ===== FILTROS ===== */
        .filtros-bar {
            background: #fff;
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 1rem;
            align-items: center;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: .8rem 1rem .8rem 2.6rem;
            border: 2px solid var(--light-pink);
            border-radius: 50px;
            outline: none;
            font-size: .95rem;
            font-family: inherit;
            transition: border-color .2s;
        }
        .search-box input:focus { border-color: var(--coral); }
        .search-box .material-symbols-outlined {
            position: absolute; left: .8rem; top: 50%;
            transform: translateY(-50%); color: var(--gray);
        }
        .filtros-bar select {
            padding: .8rem 2.5rem .8rem 1rem;
            border: 2px solid var(--light-pink);
            border-radius: 50px;
            background: #fff;
            font-family: inherit;
            font-size: .95rem;
            cursor: pointer;
            outline: none;
        }
        .filtros-bar select:focus { border-color: var(--coral); }
        .filtros-bar button {
            padding: .8rem 1.5rem;
            background: linear-gradient(135deg, var(--coral), var(--pink));
            color: #fff; border: none; border-radius: 50px;
            font-weight: 600; cursor: pointer; font-family: inherit;
            font-size: .95rem;
            transition: transform .2s;
        }
        .filtros-bar button:hover { transform: translateY(-2px); }

        /* ===== CHIPS DE MODELO ===== */
        .chips {
            display: flex; gap: .5rem; flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        .chip {
            padding: .5rem 1rem;
            background: #fff;
            border: 2px solid var(--light-pink);
            border-radius: 50px;
            color: var(--dark);
            text-decoration: none;
            font-size: .85rem;
            transition: all .2s;
        }
        .chip:hover { border-color: var(--coral); color: var(--coral); }
        .chip.ativo {
            background: linear-gradient(135deg, var(--coral), var(--pink));
            color: #fff; border-color: transparent;
        }

        /* ===== GRID DE PRODUTOS ===== */
        .produtos-info {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1rem; color: var(--gray); font-size: .9rem;
        }
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .produto-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            transition: transform .25s;
        }
        .produto-card:hover { transform: translateY(-4px); }
        .produto-imagem {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: linear-gradient(135deg, var(--light-pink), var(--pink));
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem;
            overflow: hidden;
            position: relative;
        }
        .produto-imagem img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .produto-badge {
            position: absolute; top: .8rem; left: .8rem;
            background: rgba(255,255,255,.95);
            color: var(--coral);
            padding: .3rem .8rem;
            border-radius: 50px;
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .produto-info {
            padding: 1.2rem;
            display: flex; flex-direction: column;
            gap: .5rem;
            flex: 1;
        }
        .produto-nome {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: var(--dark);
        }
        .produto-descricao {
            color: var(--gray);
            font-size: .85rem;
            line-height: 1.4;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .produto-preco {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--coral);
            margin-top: auto;
        }
        .produto-actions {
            padding: 0 1.2rem 1.2rem;
        }
        .btn-add {
            width: 100%;
            padding: .75rem;
            background: linear-gradient(135deg, var(--coral), var(--pink));
            color: #fff; border: none; border-radius: 50px;
            font-weight: 600; cursor: pointer; font-family: inherit;
            font-size: .9rem;
            display: flex; align-items: center; justify-content: center;
            gap: .4rem;
            transition: transform .2s, box-shadow .2s;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
        .btn-add:disabled {
            opacity: .6; cursor: not-allowed; transform: none;
        }

        /* ===== PAGINAÇÃO ===== */
        .paginacao {
            display: flex; justify-content: center; gap: .5rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        .paginacao a, .paginacao span {
            min-width: 40px; height: 40px;
            padding: 0 1rem;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50px;
            background: #fff;
            color: var(--dark);
            text-decoration: none;
            border: 2px solid var(--light-pink);
            font-size: .9rem;
            transition: all .2s;
        }
        .paginacao a:hover { border-color: var(--coral); color: var(--coral); }
        .paginacao .atual {
            background: linear-gradient(135deg, var(--coral), var(--pink));
            color: #fff; border-color: transparent;
        }
        .paginacao .disabled {
            opacity: .4; pointer-events: none;
        }

        /* ===== VAZIO ===== */
        .vazio {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem 1rem;
            background: #fff;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }
        .vazio .icon { font-size: 4rem; display: block; margin-bottom: 1rem; }
        .vazio h3 { font-weight: 400; margin: 0 0 .5rem; }
        .vazio p { color: var(--gray); margin: 0 0 1.5rem; }

        /* ===== NOTIFICAÇÃO ===== */
        .notificacao {
            position: fixed; top: 90px; right: 20px;
            padding: 1rem 1.5rem; border-radius: 10px;
            color: #fff; font-weight: 600; z-index: 2000;
            box-shadow: var(--shadow);
            animation: slideIn .3s ease-out;
            max-width: 400px;
        }
        .notificacao.sucesso { background: linear-gradient(135deg, #5FA86D, #4A8A58); }
        .notificacao.erro    { background: linear-gradient(135deg, #D64545, #B83535); }
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--gray);
            font-size: .85rem;
        }
        footer span { color: var(--coral); font-style: italic; }

        /* ===== RESPONSIVO ===== */
        @media (max-width: 768px) {
            .filtros-bar {
                grid-template-columns: 1fr;
            }
            .produtos-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 1rem;
            }
            .produto-info { padding: 1rem; }
            .produto-nome { font-size: 1rem; }
            .produto-preco { font-size: 1.1rem; }
            .notificacao { left: 10px; right: 10px; max-width: none; }
            .nav-menu { gap: .8rem; }
            .nav-menu a:not(.cart-link) { font-size: .85rem; }
        }
    </style>
</head>
<body>

<?php if ($flash): ?>
    <div class="notificacao <?= e($flash['tipo']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<header>
    <div class="container header-flex">
        <div class="header-left">
            <a href="home.php" class="back-button">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="logo-area">
                <div class="logo-word">flwrs <strong>·</strong></div>
                <div class="tagline-header">"Flowers that feel like feeling"</div>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="produtos.php">Produtos</a>
            <a href="faq.php">FAQ de delivery</a>
            <a href="info.php">Sobre nós</a>
            <a href="carrinho.php" class="cart-link">
                <div class="cart-icon-wrapper">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count-badge"><?= countCarrinho() ?></span>
                </div>
            </a>
            <?php if (isLogged()): ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container">

    <section class="page-hero">
        <h1><span>nossas flores</span> · escolhidas com afeto</h1>
        <p>Cada buquê conta uma história. Encontre a sua.</p>
    </section>

    <!-- FILTROS -->
    <form method="GET" class="filtros-bar">
        <div class="search-box">
            <span class="material-symbols-outlined">search</span>
            <input type="text" name="q" placeholder="Buscar flores..." value="<?= e($busca) ?>">
        </div>

        <select name="ordem">
            <option value="recentes"     <?= $ordem === 'recentes'     ? 'selected' : '' ?>>Mais recentes</option>
            <option value="menor_preco"  <?= $ordem === 'menor_preco'  ? 'selected' : '' ?>>Menor preço</option>
            <option value="maior_preco"  <?= $ordem === 'maior_preco'  ? 'selected' : '' ?>>Maior preço</option>
            <option value="nome"         <?= $ordem === 'nome'         ? 'selected' : '' ?>>Nome (A-Z)</option>
        </select>

        <button type="submit">
            <span class="material-symbols-outlined" style="vertical-align:middle;font-size:1.1rem;">filter_alt</span>
            Filtrar
        </button>
    </form>

    <!-- CHIPS DE MODELO -->
    <div class="chips">
        <?php
        $chipBase = $_GET;
        unset($chipBase['modelo'], $chipBase['pagina']);
        ?>
        <a href="?<?= http_build_query($chipBase) ?>" class="chip <?= $modelo === '' ? 'ativo' : '' ?>">
            Todos
        </a>
        <?php foreach (modelosMap() as $k => $v):
            $params = $chipBase; $params['modelo'] = $k;
        ?>
            <a href="?<?= http_build_query($params) ?>" class="chip <?= $modelo === $k ? 'ativo' : '' ?>">
                <?= e($v) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- INFO RESULTADOS -->
    <div class="produtos-info">
        <span>
            <?= $total ?> <?= $total === 1 ? 'produto encontrado' : 'produtos encontrados' ?>
            <?= $busca ? " para \"<strong>" . e($busca) . "</strong>\"" : '' ?>
        </span>
        <?php if ($busca || $modelo): ?>
            <a href="produtos.php" style="color:var(--coral);text-decoration:none;font-size:.85rem;">✕ limpar filtros</a>
        <?php endif; ?>
    </div>

    <!-- GRID DE PRODUTOS -->
    <div class="produtos-grid">
        <?php if (empty($produtos)): ?>
            <div class="vazio">
                <span class="icon">🌷</span>
                <h3>Nenhuma flor encontrada</h3>
                <p>Tente ajustar os filtros ou buscar por outro nome.</p>
                <a href="produtos.php" class="chip ativo">ver todas as flores</a>
            </div>
        <?php else: ?>
            <?php foreach ($produtos as $p): ?>
                <div class="produto-card">
                    <div class="produto-imagem">
                        <?php if (!empty($p['imagem']) && file_exists($p['imagem'])): ?>
                            <img src="<?= e(imagemUrl($p['imagem'])) ?>" alt="<?= e($p['nome']) ?>" loading="lazy">
                        <?php elseif (!empty($p['imagem'])): ?>
                            <img src="<?= e(imagemUrl($p['imagem'])) ?>" alt="<?= e($p['nome']) ?>" loading="lazy"
                                 onerror="this.style.display='none';this.parentNode.innerHTML='🌸';">
                        <?php else: ?>
                            🌸
                        <?php endif; ?>
                        <span class="produto-badge"><?= e(modeloNome($p['modelo'])) ?></span>
                    </div>

                    <div class="produto-info">
                        <h3 class="produto-nome"><?= e($p['nome']) ?></h3>
                        <p class="produto-descricao"><?= e($p['descricao']) ?></p>
                        <div class="produto-preco">R$ <?= number_format($p['preco'], 2, ',', '.') ?></div>
                    </div>

                    <form method="POST" action="carrinho_action.php" class="produto-actions">
                        <input type="hidden" name="acao" value="adicionar">
                        <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="quantidade" value="1">
                        <button type="submit" class="btn-add">
                            <span class="material-symbols-outlined" style="font-size:1.1rem;">add_shopping_cart</span>
                            adicionar ao carrinho
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- PAGINAÇÃO -->
    <?php if ($paginas > 1): ?>
        <nav class="paginacao">
            <?php if ($pagina > 1): ?>
                <a href="<?= urlPagina($pagina - 1) ?>">←</a>
            <?php else: ?>
                <span class="disabled">←</span>
            <?php endif; ?>

            <?php
            $inicio = max(1, $pagina - 2);
            $fim    = min($paginas, $pagina + 2);

            if ($inicio > 1) {
                echo '<a href="' . urlPagina(1) . '">1</a>';
                if ($inicio > 2) echo '<span class="disabled">…</span>';
            }

            for ($i = $inicio; $i <= $fim; $i++):
            ?>
                <?php if ($i === $pagina): ?>
                    <span class="atual"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= urlPagina($i) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($fim < $paginas): ?>
                <?php if ($fim < $paginas - 1) echo '<span class="disabled">…</span>'; ?>
                <a href="<?= urlPagina($paginas) ?>"><?= $paginas ?></a>
            <?php endif; ?>

            <?php if ($pagina < $paginas): ?>
                <a href="<?= urlPagina($pagina + 1) ?>">→</a>
            <?php else: ?>
                <span class="disabled">→</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

</main>

<footer>
    <p>flwrs — <span>"Flowers that feel like feeling"</span> — pequenos gestos, memórias eternas</p>
</footer>

<script>
    // Auto-esconde notificação após 4s
    setTimeout(() => {
        const n = document.querySelector('.notificacao');
        if (n) {
            n.style.transition = 'opacity .4s, transform .4s';
            n.style.opacity = '0';
            n.style.transform = 'translateX(120%)';
            setTimeout(() => n.remove(), 500);
        }
    }, 4000);
</script>

</body>
</html>