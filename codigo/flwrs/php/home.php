<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flwrs · afeto em flor</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/home.css"> 
</head>
<body>
  <header>
    <div class="container header-flex">
      <div class="logo-area">
        <div class="logo-word">
          flwrs <strong>·</strong>
        </div>
        <div class="tagline-header">
          “Flowers that feel like feeling”
        </div>
      </div>
      <nav class="nav-menu">
        <a href="produtos.php">Produtos</a>
        <a href="faq.php">FAQ de delivery</a>
        <a href="info.php">Sobre nós</a>
        <a href="carrinho.php" class="cart-link" id="cartNavLink">
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
    <!-- Hero conforme 2.jpg -->
    <section class="hero">
      <div class="hero-text">
        <h2>
          Criamos pequenos <span>gestos de afeto</span> para tornar seu dia mais bonito.
        </h2>
        <div class="hero-sub">
          <p>Transformamos suas palavras em flores e entregamos em mãos.</p>
        </div>
        <a href="info.php" class="btn-saiba">Saiba Mais</a>
      </div>
      <div class="hero-image">
        <div class="message">
          “pequenos <em>afetos</em> <br> em cada flor”
        </div>
      </div>
    </section>

    <!-- Cards de produtos / gestos (base solta mas usando cores) -->
    <section class="destaques">
      <article class="card">
        <div class="card-icon">🌸</div>
        <h3>buquês afetivos</h3>
        <p>Seleção especial de flores da estação com fitas em tom rosado e verde menta.</p>
        <a href="produtos.php" class="card-link">VER PRODUTOS</a>
      </article>
      <article class="card">
        <div class="card-icon">💌</div>
        <h3>palavras em flor</h3>
        <p>Entregamos seu recado junto com um arranjo que traduz exatamente o que você sente.</p>
        <a href="palavras.php" class="card-link">PERSONIFICAR</a>
      </article>
      <article class="card">
        <div class="card-icon">✨</div>
        <h3>assinatura mensal</h3>
        <p>Todo mês um pequeno gesto. Flores surpresa com a curadoria flwrs.</p>
        <a href="planos.php" class="card-link">ASSINAR</a>
      </article>
    </section>

    <!-- Sobre nós preview (extra) -->
    <section class="sobre-preview">
      <div class="sobre-flex">
        <div class="sobre-texto">
          <h4><strong>sobre nós</strong> · flores que sentem</h4>
          <p>Nascemos do desejo de transformar palavras em texturas, cores e perfume. Cada entrega é feita à mão, com cuidado de quem entende de afeto. Nossos floristas criam composições únicas usando sempre tons suaves e toques de verde.</p>
          <a href="info.php">conheça nossa história →</a>
        </div>
        <div class="sobre-imagem">
          <span>mais que flores,<br> sentimentos</span>
        </div>
      </div>
    </section>

    <!-- FAQ de delivery + contato (cards) -->
    <section class="faq-delivery">
      <div class="faq-item">
        <h5>🚚 FAQ de delivery</h5>
        <p>Entregamos todos os dias, com embalagem ecológica e mensagem personalizada. Áreas de cobertura sempre em expansão.</p>
        <small><a href="#">consulte prazos</a></small>
      </div>
      <div class="faq-item">
        <h5>📮 contato</h5>
        <p>central.flwrs@gmail.com / Tiramos suas dúvidas de modo eficiente e rápido.</p>
        <small><a href="#">fale conosco</a></small>
      </div>
    </section>
  </main>

<footer>
<p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script src="../js/home.js"></script>
</body>
</html>