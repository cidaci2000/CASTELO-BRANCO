<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flwrs · palavras em flor</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/palavras.css">
</head>
<body>
  <header>
    <div class="container header-flex">
      <div class="header-left">
        <a href="home.php" class="back-button" aria-label="Voltar">
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
    <!-- Título da página -->
    <div class="page-title">
      <h1>palavras em <span>flor</span></h1>
      <p>Escolha um recado pronto ou crie o seu — cada flor leva um sentimento.</p>
    </div>

    <!-- Grade de recados prontos -->
    <section class="messages-grid">
      <!-- Card 1 -->
      <div class="message-card">
        <span class="card-badge">romântico</span>
        <span class="msg-icon">🌹</span>
        <blockquote>“Seu sorriso é a flor mais bonita que eu já vi. E olha que eu vivo rodeado de flores.”</blockquote>
        <span class="msg-author">— flwrs</span>
        <span class="msg-tag">#amor</span>
        <button class="btn-select" data-message="Seu sorriso é a flor mais bonita que eu já vi. E olha que eu vivo rodeado de flores.">usar este recado</button>
      </div>

      <!-- Card 2 -->
      <div class="message-card">
        <span class="card-badge">amizade</span>
        <span class="msg-icon">🌸</span>
        <blockquote>“Você é daquelas pessoas que faz a vida ter mais cor. Como um buquê de primavera.”</blockquote>
        <span class="msg-author">— flwrs</span>
        <span class="msg-tag">#gratidão</span>
        <button class="btn-select" data-message="Você é daquelas pessoas que faz a vida ter mais cor. Como um buquê de primavera.">usar este recado</button>
      </div>

      <!-- Card 3 -->
      <div class="message-card">
        <span class="card-badge">saudade</span>
        <span class="msg-icon">🍃</span>
        <blockquote>“Nem todas as flores estão no jardim. Algumas moram na memória e florescem na saudade.”</blockquote>
        <span class="msg-author">— flwrs</span>
        <span class="msg-tag">#memória</span>
        <button class="btn-select" data-message="Nem todas as flores estão no jardim. Algumas moram na memória e florescem na saudade.">usar este recado</button>
      </div>

      <!-- Card 4 -->
      <div class="message-card">
        <span class="card-badge">carinho</span>
        <span class="msg-icon">💐</span>
        <blockquote>“Pegue estas flores como um abraço que não coube em palavras. Você merece todo o afeto do mundo.”</blockquote>
        <span class="msg-author">— flwrs</span>
        <span class="msg-tag">#afeto</span>
        <button class="btn-select" data-message="Pegue estas flores como um abraço que não coube em palavras. Você merece todo o afeto do mundo.">usar este recado</button>
      </div>

      <!-- Card 5 -->
      <div class="message-card">
        <span class="card-badge">inspiração</span>
        <span class="msg-icon">✨</span>
        <blockquote>“Que cada pétala te lembre que a vida é feita de pequenos gestos que viram eternidade.”</blockquote>
        <span class="msg-author">— flwrs</span>
        <span class="msg-tag">#poesia</span>
        <button class="btn-select" data-message="Que cada pétala te lembre que a vida é feita de pequenos gestos que viram eternidade.">usar este recado</button>
      </div>

      <!-- Card 6 -->
      <div class="message-card">
        <span class="card-badge">paixão</span>
        <span class="msg-icon">❤️‍🔥</span>
        <blockquote>“Se o amor fosse uma flor, eu a colheria todos os dias só para te ver sorrir.”</blockquote>
        <span class="msg-author">— flwrs</span>
        <span class="msg-tag">#entregue</span>
        <button class="btn-select" data-message="Se o amor fosse uma flor, eu a colheria todos os dias só para te ver sorrir.">usar este recado</button>
      </div>
    </section>

    <!-- Área de recado personalizado -->
    <div class="custom-message-area">
      <div>
        <h3><i class="fas fa-pen-fancy"></i> seu próprio recado</h3>
        <p>Escreva uma mensagem única e ela será entregue junto com o buquê. Nossos floristas cuidam para que cada palavra chegue com carinho.</p>
        <textarea id="customMessage" placeholder="Digite aqui sua mensagem personalizada..."></textarea>
        <button class="btn-enviar" id="sendCustomMsg">usar este recado</button>
      </div>
      <div class="preview-box">
        <span class="preview-label">prévia do recado</span>
        <div class="preview-content" id="previewContent">
          <i class="fas fa-feather-alt"></i>
          <span>sua mensagem aparecerá aqui</span>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
  </footer>

<script src="../js/palavras.js"></script>
</body>
</html>