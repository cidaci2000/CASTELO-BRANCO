<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flwrs · assinatura mensal</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/planos.css">
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

  /* ===== ASSINATURA HEADER ===== */
  .assinatura-header {
      padding: 4rem 0 2.5rem;
      text-align: center;
      border-bottom: 1px solid rgba(180, 165, 160, 0.06);
  }

  .assinatura-header h1 {
      font-size: 2.8rem;
      font-weight: 300;
      line-height: 1.15;
      letter-spacing: -0.03em;
      color: #2d2825;
  }

  .assinatura-header h1 span {
      color: #f07d9d;
      font-weight: 400;
      position: relative;
  }

  .assinatura-header h1 span::before {
      content: '';
      position: absolute;
      bottom: 2px;
      left: 0;
      width: 100%;
      height: 6px;
      background: #f7d6e7;
      z-index: -1;
  }

  .assinatura-header p {
      margin-top: 1rem;
      font-size: 1.05rem;
      color: #6d6560;
      font-weight: 300;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
  }

  /* ===== PLANOS GRID ===== */
  .planos-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      padding: 4rem 0;
  }

  .plano-card {
      background: white;
      padding: 2.5rem 2rem 2rem;
      border-radius: 28px;
      border: 1px solid rgba(180, 165, 160, 0.06);
      text-align: center;
      transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      position: relative;
      overflow: hidden;
  }

  .plano-card::before {
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

  .plano-card:hover {
      transform: translateY(-8px);
      border-color: rgba(184, 122, 142, 0.12);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.04);
  }

  .plano-card:hover::before {
      opacity: 1;
  }

  .plano-card.destaque {
      border-color: #e94e77;
      transform: scale(1.02);
      box-shadow: 0 20px 60px -15px rgba(233, 78, 119, 0.15);
  }

  .plano-card.destaque::before {
      opacity: 1;
      background: linear-gradient(90deg, #e94e77, #f07d9d, #e94e77);
  }

  .plano-card.destaque::after {
      content: 'MAIS POPULAR';
      position: absolute;
      top: 1rem;
      right: -2.5rem;
      background: #e94e77;
      color: white;
      font-size: 0.55rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      padding: 0.25rem 2.5rem;
      transform: rotate(45deg);
      text-transform: uppercase;
  }

  .plano-icon {
      font-size: 3rem;
      display: block;
      margin-bottom: 0.8rem;
  }

  .plano-nome {
      font-size: 1.3rem;
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #2d2825;
      margin-bottom: 0.5rem;
  }

  .plano-preco {
      font-size: 2rem;
      font-weight: 500;
      color: #e94e77;
      margin-bottom: 1.5rem;
  }

  .plano-preco small {
      font-size: 0.8rem;
      font-weight: 300;
      color: #a8958f;
  }

  .plano-beneficios {
      list-style: none;
      padding: 0;
      margin-bottom: 2rem;
      text-align: left;
  }

  .plano-beneficios li {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.4rem 0;
      font-size: 0.85rem;
      color: #6d6560;
      font-weight: 300;
  }

  .plano-beneficios li .material-symbols-outlined {
      color: #91b691;
      font-size: 1.2rem;
  }

  .btn-assinar {
      width: 100%;
      background: #e94e77;
      color: white;
      border: none;
      padding: 0.8rem 1.5rem;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      cursor: pointer;
      transition: all 0.3s ease;
  }

  .btn-assinar:hover {
      background: #d43d66;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(233, 78, 119, 0.2);
  }

  .plano-card.destaque .btn-assinar {
      background: linear-gradient(135deg, #e94e77, #f07d9d);
  }

  .plano-card.destaque .btn-assinar:hover {
      background: linear-gradient(135deg, #d43d66, #e94e77);
      box-shadow: 0 8px 30px rgba(233, 78, 119, 0.3);
  }

  /* ===== COMO FUNCIONA ===== */
  .como-funciona {
      padding: 4rem 0;
      border-top: 1px solid rgba(180, 165, 160, 0.08);
      border-bottom: 1px solid rgba(180, 165, 160, 0.08);
  }

  .como-funciona h2 {
      font-size: 2.2rem;
      font-weight: 300;
      letter-spacing: -0.02em;
      color: #2d2825;
      text-align: center;
      margin-bottom: 3rem;
  }

  .como-funciona h2 span {
      color: #f07d9d;
      font-weight: 400;
  }

  .passos {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 2rem;
  }

  .passo-item {
      text-align: center;
      padding: 1.5rem;
      transition: all 0.3s ease;
  }

  .passo-item:hover {
      transform: translateY(-4px);
  }

  .passo-numero {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #fde8f3, #f7d6e7);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 500;
      color: #e94e77;
      margin: 0 auto 1rem;
      transition: all 0.3s ease;
  }

  .passo-item:hover .passo-numero {
      transform: scale(1.1);
      box-shadow: 0 8px 25px rgba(233, 78, 119, 0.15);
  }

  .passo-item h4 {
      font-size: 1rem;
      font-weight: 500;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      color: #2d2825;
      margin-bottom: 0.5rem;
  }

  .passo-item p {
      font-size: 0.85rem;
      color: #6d6560;
      font-weight: 300;
      line-height: 1.5;
  }

  /* ===== DEPOIMENTOS ===== */
  .depoimentos {
      padding: 4rem 0;
  }

  .depoimentos h2 {
      font-size: 2rem;
      font-weight: 300;
      letter-spacing: -0.02em;
      color: #2d2825;
      text-align: center;
      margin-bottom: 3rem;
  }

  .depoimentos-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
  }

  .depoimento-card {
      background: white;
      padding: 2rem;
      border-radius: 20px;
      border: 1px solid rgba(180, 165, 160, 0.06);
      transition: all 0.3s ease;
  }

  .depoimento-card:hover {
      transform: translateY(-4px);
      border-color: rgba(184, 122, 142, 0.12);
      box-shadow: 0 15px 40px -12px rgba(0, 0, 0, 0.03);
  }

  .depoimento-texto {
      font-size: 0.95rem;
      color: #6d6560;
      font-weight: 300;
      line-height: 1.7;
      font-style: italic;
      margin-bottom: 1.5rem;
  }

  .depoimento-texto::before {
      content: '"';
      font-size: 2rem;
      color: #f07d9d;
      font-weight: 400;
  }

  .depoimento-texto::after {
      content: '"';
      font-size: 2rem;
      color: #f07d9d;
      font-weight: 400;
  }

  .depoimento-autor {
      display: flex;
      align-items: center;
      gap: 1rem;
  }

  .autor-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: linear-gradient(135deg, #fde8f3, #f7d6e7);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 500;
      color: #e94e77;
      font-size: 0.85rem;
      flex-shrink: 0;
  }

  .autor-info h5 {
      font-size: 0.9rem;
      font-weight: 500;
      color: #2d2825;
      margin-bottom: 0.1rem;
  }

  .autor-info p {
      font-size: 0.7rem;
      color: #a8958f;
      font-weight: 300;
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

  /* ===== MATERIAL SYMBOLS ===== */
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
      -webkit-font-feature-settings: 'liga';
      -webkit-font-smoothing: antialiased;
  }

  /* ===== ANIMAÇÕES ===== */
  @keyframes fadeInUp {
      from {
          opacity: 0;
          transform: translateY(30px);
      }
      to {
          opacity: 1;
          transform: translateY(0);
      }
  }

  .plano-card {
      animation: fadeInUp 0.6s ease forwards;
  }

  .plano-card:nth-child(2) { animation-delay: 0.1s; }
  .plano-card:nth-child(3) { animation-delay: 0.2s; }

  .passo-item {
      animation: fadeInUp 0.6s ease forwards;
  }

  .passo-item:nth-child(2) { animation-delay: 0.1s; }
  .passo-item:nth-child(3) { animation-delay: 0.2s; }
  .passo-item:nth-child(4) { animation-delay: 0.3s; }

  .depoimento-card {
      animation: fadeInUp 0.6s ease forwards;
  }

  .depoimento-card:nth-child(2) { animation-delay: 0.1s; }
  .depoimento-card:nth-child(3) { animation-delay: 0.2s; }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1024px) {
      .assinatura-header h1 {
          font-size: 2.4rem;
      }
      
      .planos-grid {
          grid-template-columns: repeat(2, 1fr);
      }
      
      .plano-card.destaque {
          transform: scale(1);
      }
      
      .plano-card.destaque::after {
          top: 0.5rem;
          right: -2.5rem;
          font-size: 0.5rem;
          padding: 0.2rem 2.5rem;
      }
      
      .passos {
          grid-template-columns: repeat(2, 1fr);
      }
      
      .depoimentos-grid {
          grid-template-columns: repeat(2, 1fr);
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
      
      .assinatura-header h1 {
          font-size: 2rem;
      }
      
      .planos-grid {
          grid-template-columns: 1fr;
          max-width: 400px;
          margin: 0 auto;
          padding: 3rem 0;
      }
      
      .plano-card.destaque {
          transform: scale(1);
          order: -1;
      }
      
      .plano-card.destaque::after {
          top: 0.5rem;
          right: -2.5rem;
          font-size: 0.5rem;
          padding: 0.2rem 2.5rem;
      }
      
      .passos {
          grid-template-columns: 1fr 1fr;
          gap: 1.5rem;
      }
      
      .depoimentos-grid {
          grid-template-columns: 1fr;
          max-width: 400px;
          margin: 0 auto;
      }
      
      .como-funciona h2 {
          font-size: 1.8rem;
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
      
      .assinatura-header h1 {
          font-size: 1.6rem;
      }
      
      .assinatura-header p {
          font-size: 0.95rem;
      }
      
      .passos {
          grid-template-columns: 1fr;
          max-width: 300px;
          margin: 0 auto;
      }
      
      .plano-card {
          padding: 2rem 1.5rem;
      }
      
      .plano-preco {
          font-size: 1.8rem;
      }
      
      .depoimento-card {
          padding: 1.5rem;
      }
  }
</style>
<body>
  <header>
    <div class="container header-flex">
      <div class="header-left">
        <!-- Seta de voltar -->
        <a href="produtos.php" class="back-button" onclick="">
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
    <!-- cabeçalho da assinatura -->
    <section class="assinatura-header">
      <h1><span>assinatura mensal</span> · flores todo mês</h1>
      <p>Todo mês um pequeno gesto. Flores surpresa com a curadoria Flwrs entregues na sua casa ou para presentear alguém especial.</p>
    </section>

    <!-- planos de assinatura -->
    <section class="planos-grid">
      <!-- plano básico -->
      <article class="plano-card">
        <div class="plano-icon">🌸</div>
        <h3 class="plano-nome">essencial</h3>
        <div class="plano-preco">R$ 49,90 <small>/mês</small></div>
        <ul class="plano-beneficios">
          <li><span class="material-symbols-outlined">check_circle</span> 1 buquê surpresa por mês</li>
          <li><span class="material-symbols-outlined">check_circle</span> Flores da estação</li>
          <li><span class="material-symbols-outlined">check_circle</span> Cartão digital incluso</li>
          <li><span class="material-symbols-outlined">check_circle</span> Entrega gratuita</li>
        </ul>
        <button class="btn-assinar" onclick="alert('Plano essencial selecionado (simulação)')">assinar</button>
      </article>

      <!-- plano intermediário (destaque) -->
      <article class="plano-card destaque">
        <div class="plano-icon">💐</div>
        <h3 class="plano-nome">afeto completo</h3>
        <div class="plano-preco">R$ 89,90 <small>/mês</small></div>
        <ul class="plano-beneficios">
          <li><span class="material-symbols-outlined">check_circle</span> 1 buquê especial por mês</li>
          <li><span class="material-symbols-outlined">check_circle</span> Flores premium + folhagens</li>
          <li><span class="material-symbols-outlined">check_circle</span> Cartão escrito à mão</li>
          <li><span class="material-symbols-outlined">check_circle</span> Entrega express</li>
          <li><span class="material-symbols-outlined">check_circle</span> 10% off em compras adicionais</li>
        </ul>
        <button class="btn-assinar" onclick="alert('Plano afeto completo selecionado (simulação)')">assinar</button>
      </article>

      <!-- plano presente -->
      <article class="plano-card">
        <div class="plano-icon">🎁</div>
        <h3 class="plano-nome">presente anônimo</h3>
        <div class="plano-preco">R$ 99,90 <small>/mês</small></div>
        <ul class="plano-beneficios">
          <li><span class="material-symbols-outlined">check_circle</span> Assinatura para presentear</li>
          <li><span class="material-symbols-outlined">check_circle</span> 3, 6 ou 12 meses</li>
          <li><span class="material-symbols-outlined">check_circle</span> Mensagem secreta opcional</li>
          <li><span class="material-symbols-outlined">check_circle</span> Embalagem especial de presente</li>
          <li><span class="material-symbols-outlined">check_circle</span> Aviso no primeiro mês</li>
        </ul>
        <button class="btn-assinar" onclick="alert('Plano presente anônimo selecionado (simulação)')">assinar</button>
      </article>
    </section>

    <!-- como funciona -->
    <section class="como-funciona">
      <h2><span>como funciona</span> · simples e afetivo</h2>
      <div class="passos">
        <div class="passo-item">
          <div class="passo-numero">1</div>
          <h4>escolha seu plano</h4>
          <p>Selecione a assinatura que mais combina com você ou com quem você quer presentear.</p>
        </div>
        <div class="passo-item">
          <div class="passo-numero">2</div>
          <h4>personalize</h4>
          <p>Defina o endereço, a frequência e inclua uma mensagem se desejar.</p>
        </div>
        <div class="passo-item">
          <div class="passo-numero">3</div>
          <h4>receba surpresas</h4>
          <p>Todo mês um buquê diferente, selecionado com carinho pela nossa equipe.</p>
        </div>
        <div class="passo-item">
          <div class="passo-numero">4</div>
          <h4>cancele quando quiser</h4>
          <p>Sem fidelidade. Você pode pausar ou cancelar a qualquer momento.</p>
        </div>
      </div>
    </section>

    <!-- depoimentos de assinantes -->
    <section class="depoimentos">
      <h2>quem já assina · 💚</h2>
      <div class="depoimentos-grid">
        <div class="depoimento-card">
          <p class="depoimento-texto">“Assino o plano afeto completo há 6 meses. Todo mês é uma surpresa linda que ilumina minha sala e meu coração.”</p>
          <div class="depoimento-autor">
            <div class="autor-avatar">AM</div>
            <div class="autor-info">
              <h5>Ana Moraes</h5>
              <p>assinante desde 2024</p>
            </div>
          </div>
        </div>
        <div class="depoimento-card">
          <p class="depoimento-texto">“Presenteei minha mãe com a assinatura de presente anônimo. Ela ama receber flores todo mês e não sabe que sou eu!”</p>
          <div class="depoimento-autor">
            <div class="autor-avatar">LP</div>
            <div class="autor-info">
              <h5>Lucas Prado</h5>
              <p>presenteador fiel</p>
            </div>
          </div>
        </div>
        <div class="depoimento-card">
          <p class="depoimento-texto">“Como presente para mim mesma, a assinatura essencial é meu momento de autocuidado mensal. Recomendo demais.”</p>
          <div class="depoimento-autor">
            <div class="autor-avatar">CF</div>
            <div class="autor-info">
              <h5>Carla Farias</h5>
              <p>assinante essencial</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- contato rápido -->
    <div class="info-rodape">
      <span>🚚 FAQ de delivery — atualizado</span>
      <span>📮 central.flwrs@gmail.com</span>
    </div>
  </main>

<footer>
<p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

  <script src="../js/planos.js"></script>
</body>
</html>