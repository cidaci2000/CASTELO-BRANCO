<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flwrs · sobre nós</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/info.css">
</head>
<body>
  <header>
    <div class="container header-flex">
      <div class="header-left">
        <!-- Seta de voltar -->
        <a href="home.php" class="back-button" onclick="">
          <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <!-- Logo -->
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
        <a href="produtos.php">Produtos</a>
        <a href="faq.php">FAQ de delivery</a>
        <a href="#" style="color:#c06f8b; border-bottom-color:#f7d5e7;">Sobre nós</a>
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
    <!-- Hero da página sobre -->
    <section class="sobre-hero">
      <h1><span>Afeto que floresce</span> · nossa história</h1>
      <p>“Nascemos do desejo de transformar palavras em texturas, cores e perfume. Cada entrega é feita à mão, com cuidado de quem entende de afeto.”</p>
    </section>

    <!-- cards com a filosofia -->
    <section class="sobre-grid">
      <article class="sobre-card">
        <div class="card-icon">🌸</div>
        <h3>flores com propósito</h3>
        <p>Selecionamos flores da estação e criamos composições únicas, sempre com tons suaves e toques de verde.</p>
      </article>
      <article class="sobre-card">
        <div class="card-icon">💚</div>
        <h3>gestos que importam</h3>
        <p>Acreditamos que pequenos afetos transformam dias. Por isso, cada buquê carrega uma mensagem personalizada.</p>
      </article>
      <article class="sobre-card">
        <div class="card-icon">🌿</div>
        <h3>cuidado sustentável</h3>
        <p>Embalagens ecológicas e parceria com produtores locais para reduzir nossa pegada e espalhar mais verde.</p>
      </article>
    </section>

    <!-- seção história mais detalhada -->
    <section class="historia">
      <div class="historia-texto">
        <h2><strong>Como tudo começou</strong> · 2024</h2>
        <p>Flwrs nasceu em uma pequena estufa urbana, quando duas amigas resolveram unir a paixão por flores e a vontade de criar conexões reais.</p>
        <p>Hoje, temos uma equipe de floristas e entregadores que compartilham o mesmo valor: <em>flores que sentem</em>. Cada arranjo é pensado para traduzir um sentimento — seja amor, amizade, gratidão ou simplesmente "estou pensando em você".</p>
        <p>Nossos clientes viraram amigos, e amigos viraram floristas. Essa é a nossa maior flor.</p>
      </div>
      <div class="historia-imagem">
        <span>“mais que flores,<br>abraços”</span>
      </div>
    </section>

    <!-- nossos valores -->
    <section class="valores">
      <h3>Nossos valores</h3>
      <div class="valores-lista">
        <div class="valor-item">
          <div class="emoji">🤲</div>
          <h4>afeto</h4>
          <p>Em cada detalhe, do atendimento à entrega.</p>
        </div>
        <div class="valor-item">
          <div class="emoji">🌎</div>
          <h4>sustentabilidade</h4>
          <p>Embalagens verdes e flores de comércio justo.</p>
        </div>
        <div class="valor-item">
          <div class="emoji">🎨</div>
          <h4>criatividade</h4>
          <p>Buquês exclusivos como pequenas obras de arte.</p>
        </div>
        <div class="valor-item">
          <div class="emoji">🤝</div>
          <h4>comunidade</h4>
          <p>Parceria com produtores locais e entregas afetivas.</p>
        </div>
      </div>
    </section>

    <!-- contato rápido (mesmo estilo) -->
    <div class="info-rodape">
      <span>🚚 FAQ de delivery — atualizado</span>
      <span>📮 central.flwrs@gmail.com</span>
    </div>
  </main>

<footer>
<p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script src="../js/info.js"></script>
</body>
</html>