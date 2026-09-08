<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flwrs · produtos</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/produtos.css">
</head>
<style>
            /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #fcf8f5;
            color: #3d3835;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2.5rem;
        }

        /* ===== HEADER ===== */
        header {
            padding: 1.8rem 0 1.2rem;
            border-bottom: 1px solid rgba(180, 165, 160, 0.12);
            position: sticky;
            top: 0;
            background: rgba(252, 248, 245, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            z-index: 100;
        }

        .header-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-button {
            color: #5a5552;
            text-decoration: none;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .back-button:hover {
            color: #e94e77;
        }

        .back-button .material-symbols-outlined {
            font-size: 1.8rem;
        }

        .logo-area {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .logo-word {
            font-size: 2.2rem;
            font-weight: 200;
            letter-spacing: 0.04em;
            color: #3d3835;
        }

        .logo-word strong {
            font-weight: 500;
            color: #e94e77;
        }

        .tagline-header {
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #a8958f;
            padding-left: 1rem;
            border-left: 1px solid #e8d5d0;
            font-weight: 300;
        }

        /* ===== NAVIGATION ===== */
        .nav-menu {
            display: flex;
            gap: 2.2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-menu a {
            text-decoration: none;
            color: #5a5552;
            font-size: 0.75rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: #e94e77;
            transition: width 0.3s ease;
        }

        .nav-menu a:hover {
            color: #e94e77;
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .cart-link {
            position: relative;
            display: flex;
            align-items: center;
        }

        .cart-icon-wrapper {
            display: flex;
            align-items: center;
            position: relative;
            padding: 0.3rem 0.5rem;
            border-radius: 30px;
            transition: background 0.3s ease;
        }

        .cart-icon-wrapper:hover {
            background: rgba(184, 122, 142, 0.06);
        }

        .cart-icon-wrapper i {
            font-size: 1.2rem;
            color: #4a4542;
            transition: color 0.3s ease;
        }

        .cart-icon-wrapper:hover i {
            color: #e94e77;
        }

        .cart-count-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #e94e77;
            color: white;
            font-size: 0.6rem;
            font-weight: 600;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', monospace;
            letter-spacing: 0;
            box-shadow: 0 2px 8px rgba(184, 122, 142, 0.25);
        }

        /* ===== PRODUTOS HEADER ===== */
        .produtos-header {
            padding: 4rem 0 2.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(180, 165, 160, 0.06);
        }

        .produtos-header h1 {
            font-size: 2.8rem;
            font-weight: 300;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: #2d2825;
        }

        .produtos-header h1 span {
            color: #f07d9d;
            font-weight: 400;
            position: relative;
        }

        .produtos-header h1 span::before {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0;
            width: 100%;
            height: 6px;
            background: #f7d6e7;
            z-index: -1;
        }

        .produtos-header p {
            margin-top: 1rem;
            font-size: 1.05rem;
            color: #6d6560;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ===== FILTROS ===== */
        .filtros {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            justify-content: center;
            padding: 2.5rem 0 3rem;
        }

        .filtro-btn {
            background: transparent;
            border: 1px solid rgba(180, 165, 160, 0.15);
            padding: 0.5rem 1.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5a5552;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 400;
        }

        .filtro-btn:hover {
            border-color: #e94e77;
            color: #e94e77;
            transform: translateY(-2px);
        }

        .filtro-btn.ativo {
            background: #e94e77;
            border-color: #e94e77;
            color: white;
            box-shadow: 0 4px 15px rgba(233, 78, 119, 0.2);
        }

        /* ===== PRODUTOS GRID ===== */
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            padding: 1rem 0 4rem;
        }

        .produto-card {
            background: white;
            border-radius: 28px;
            padding: 2rem 1.5rem 1.8rem;
            border: 1px solid rgba(180, 165, 160, 0.06);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .produto-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #f5ece9, #e94e77, #f5ece9);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .produto-card:hover {
            transform: translateY(-8px);
            border-color: rgba(184, 122, 142, 0.12);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.04);
        }

        .produto-card:hover::before {
            opacity: 1;
        }

        .produto-imagem {
            font-size: 3.5rem;
            text-align: center;
            padding: 1.5rem 0;
            background: linear-gradient(150deg, #fdf8f5, #f8f0ed);
            border-radius: 20px;
            margin-bottom: 1.2rem;
        }

        .produto-categoria {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #a8958f;
            font-weight: 400;
        }

        .produto-nome {
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: #2d2825;
            margin: 0.3rem 0 0.5rem;
        }

        .produto-descricao {
            font-size: 0.85rem;
            color: #6d6560;
            font-weight: 300;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .produto-preco {
            font-size: 1.2rem;
            font-weight: 500;
            color: #2d2825;
            margin-bottom: 1.2rem;
        }

        .produto-preco small {
            font-size: 0.75rem;
            color: #a8958f;
            font-weight: 300;
        }

        .produto-acoes {
            display: flex;
            gap: 0.8rem;
            align-items: center;
        }

        .btn-comprar {
            flex: 1;
            background: #e94e77;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-comprar:hover {
            background: #d43d66;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 78, 119, 0.2);
        }

        .btn-favoritar {
            background: transparent;
            border: 1px solid rgba(180, 165, 160, 0.15);
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #a8958f;
            font-size: 0;
        }

        .btn-favoritar .material-symbols-outlined {
            font-size: 1.2rem;
        }

        .btn-favoritar:hover {
            border-color: #e94e77;
            color: #e94e77;
            background: rgba(233, 78, 119, 0.04);
        }

        /* ===== DESTAQUE PROMO ===== */
        .destaque-promo {
            background: linear-gradient(150deg, #fde8f3, #fefaf5 50%, #c8e8d8);
            padding: 3.5rem 3rem;
            border-radius: 28px;
            text-align: center;
            margin: 2rem 0 3rem;
            border: 1px solid rgba(180, 165, 160, 0.06);
        }

        .destaque-promo h3 {
            font-size: 1.4rem;
            font-weight: 300;
            letter-spacing: -0.02em;
            color: #2d2825;
            text-transform: none;
        }

        .destaque-promo h3 span {
            color: #f07d9d;
            font-weight: 500;
        }

        .btn-promo {
            display: inline-block;
            background: #e94e77;
            color: white;
            padding: 0.7rem 3rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 1.2rem;
            transition: all 0.3s ease;
        }

        .btn-promo:hover {
            background: #d43d66;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 78, 119, 0.2);
        }

        /* ===== INFO RODAPE ===== */
        .info-rodape {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2.5rem;
            padding: 3rem 0 1rem;
            border-top: 1px solid rgba(180, 165, 160, 0.06);
        }

        .info-rodape span {
            font-size: 0.7rem;
            color: #a8958f;
            letter-spacing: 0.04em;
            font-weight: 300;
        }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 3rem 2rem;
            border-top: 1px solid rgba(180, 165, 160, 0.08);
            margin-top: 1rem;
        }

        footer p {
            font-size: 0.75rem;
            color: #a8958f;
            letter-spacing: 0.04em;
            font-weight: 300;
        }

        footer span {
            color: #91b691;
            font-weight: 400;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .produtos-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .produtos-header h1 {
                font-size: 2.4rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1.5rem;
            }

            .header-flex {
                flex-direction: column;
                text-align: center;
            }

            .header-left {
                flex-direction: column;
                align-items: center;
            }

            .logo-area {
                justify-content: center;
                flex-direction: column;
                align-items: center;
            }

            .tagline-header {
                border-left: none;
                padding-left: 0;
            }

            .nav-menu {
                justify-content: center;
                gap: 1.5rem;
            }

            .produtos-header h1 {
                font-size: 2rem;
            }

            .produtos-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .filtros {
                gap: 0.5rem;
            }

            .filtro-btn {
                padding: 0.4rem 1.2rem;
                font-size: 0.65rem;
            }

            .destaque-promo {
                padding: 2.5rem 1.5rem;
            }

            .destaque-promo h3 {
                font-size: 1.1rem;
            }

            .info-rodape {
                gap: 1.5rem;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }

        @media (max-width: 600px) {
            .nav-menu {
                gap: 1rem;
            }

            .nav-menu a {
                font-size: 0.65rem;
                letter-spacing: 0.08em;
            }

            .produtos-header h1 {
                font-size: 1.6rem;
            }

            .produto-card {
                padding: 1.5rem 1.2rem;
            }

            .produto-imagem {
                font-size: 2.8rem;
                padding: 1rem 0;
            }

            .btn-comprar {
                padding: 0.6rem 1rem;
                font-size: 0.65rem;
            }
        }

        /* ===== MATERIAL SYMBOLS FALLBACK ===== */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            webkit-font-feature-settings: 'liga';
            webkit-font-smoothing: antialiased; 
        }
</style>
<body>
<header>
    <div class="container header-flex">
        <div class="header-left">
            <a href="home.php" class="back-button">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="logo-area">
                <div class="logo-word">
                    flwrs <strong>·</strong>
                </div>
                <div class="tagline-header">
                    “Flowers that feel like feeling”
                </div>
            </div>
        </div>
        <nav class="nav-menu">
            <a href="produtos.php" style="color:#c06f8b; border-bottom-color:#f7d5e7;">Produtos</a>
            <a href="faq.php">FAQ de delivery</a>
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
    <p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script src="../js/produtos.js"></script>
</body>
</html>