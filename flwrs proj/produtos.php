<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flwrs · produtos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/produtos.css">
</head>
<body>

<header>
    <div class="container header-flex">
        <div class="header-left">
            <a href="home.php" class="back-button">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="logo-area">
                <div class="logo-word">
                    Flwrs <strong>·</strong>
                </div>
                <div class="tagline-header">
                    “Flowers that feel like felling”
                </div>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="produtos.php" style="color:#c06f8b; border-bottom-color:#f7d5e7;">Produtos</a>
            <a href="#">FAQ de delivery</a>
            <a href="info.php">Sobre nós</a>
            <a href="carrinho.php" class="cart-link">
                <div class="cart-icon-wrapper">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count-badge" id="cartCountBadge">0</span>
                </div>
            </a>
            <a href="login.php">Login</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="produtos-header">
        <h1><span>Nossos buquês</span> · afeto em cada detalhe</h1>
        <p>Selecionamos flores da estação para criar composições únicas. Escolha a sua e envie um pedacinho de você.</p>
    </section>

    <div class="filtros">
        <button class="filtro-btn ativo">todos</button>
        <button class="filtro-btn">buquês</button>
        <button class="filtro-btn">arranjos</button>
        <button class="filtro-btn">presentes</button>
        <button class="filtro-btn">especiais</button>
    </div>

    <section class="produtos-grid">
        <!-- Produto 1 - campos de verão -->
        <article class="produto-card">
            <div class="produto-imagem">🌸🌿</div>
            <div class="produto-categoria">buquê afetivo</div>
            <h3 class="produto-nome">campos de verão</h3>
            <p class="produto-descricao">Girassóis, folhagens e flores do campo em tons de amarelo e verde.</p>
            <div class="produto-preco">R$ 89,90 <small>/ un</small></div>
            <div class="produto-acoes">
                <button class="btn-comprar">comprar</button>
                <button class="btn-favoritar"><span class="material-symbols-outlined">favorite</span></button>
            </div>
        </article>

        <!-- Produto 2 - rosas suaves -->
        <article class="produto-card">
            <div class="produto-imagem">💐🌸</div>
            <div class="produto-categoria">buquê afetivo</div>
            <h3 class="produto-nome">rosas suaves</h3>
            <p class="produto-descricao">Buquê com rosas coral, eucalipto e detalhes em branco.</p>
            <div class="produto-preco">R$ 129,90 <small>/ un</small></div>
            <div class="produto-acoes">
                <button class="btn-comprar">comprar</button>
                <button class="btn-favoritar"><span class="material-symbols-outlined">favorite</span></button>
            </div>
        </article>

        <!-- Produto 3 - minimalismo verde -->
        <article class="produto-card">
            <div class="produto-imagem">🌼🌱</div>
            <div class="produto-categoria">arranjo</div>
            <h3 class="produto-nome">minimalismo verde</h3>
            <p class="produto-descricao">Folhagens selecionadas e flores secas em vaso de cerâmica.</p>
            <div class="produto-preco">R$ 112,00 <small>/ un</small></div>
            <div class="produto-acoes">
                <button class="btn-comprar">comprar</button>
                <button class="btn-favoritar"><span class="material-symbols-outlined">favorite</span></button>
            </div>
        </article>

        <!-- Produto 4 - kit afeto -->
        <article class="produto-card">
            <div class="produto-imagem">💮✨</div>
            <div class="produto-categoria">presente especial</div>
            <h3 class="produto-nome">kit afeto</h3>
            <p class="produto-descricao">Buquê + velas aromáticas + cartão personalizado.</p>
            <div class="produto-preco">R$ 189,90 <small>/ kit</small></div>
            <div class="produto-acoes">
                <button class="btn-comprar">comprar</button>
                <button class="btn-favoritar"><span class="material-symbols-outlined">favorite</span></button>
            </div>
        </article>

        <!-- Produto 5 - sol e mel -->
        <article class="produto-card">
            <div class="produto-imagem">🌻🍯</div>
            <div class="produto-categoria">buquê especial</div>
            <h3 class="produto-nome">sol e mel</h3>
            <p class="produto-descricao">Girassóis com detalhes em amarelo-queimado e embalagem rústica.</p>
            <div class="produto-preco">R$ 99,90 <small>/ un</small></div>
            <div class="produto-acoes">
                <button class="btn-comprar">comprar</button>
                <button class="btn-favoritar"><span class="material-symbols-outlined">favorite</span></button>
            </div>
        </article>

        <!-- Produto 6 - recado florido -->
        <article class="produto-card">
            <div class="produto-imagem">🌸💌</div>
            <div class="produto-categoria">palavras em flor</div>
            <h3 class="produto-nome">recado florido</h3>
            <p class="produto-descricao">Escolha o buquê e inclua um cartão com seu recado.</p>
            <div class="produto-preco">a partir de R$ 79,90</div>
            <div class="produto-acoes">
                <button class="btn-comprar">comprar</button>
                <button class="btn-favoritar"><span class="material-symbols-outlined">favorite</span></button>
            </div>
        </article>
    </section>

    <section class="destaque-promo">
        <h3><span>assinatura mensal</span> · todo mês um afeto surpresa</h3>
        <a href="planos.php" class="btn-promo">conhecer</a>
    </section>

    <div class="info-rodape">
        <span>🚚 FAQ de delivery — atualizado</span>
        <span>📮 central.flwrs@gmail.com</span>
    </div>
</main>

<footer>
    <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script src="js/produtos.js"></script>
</body>
</html>