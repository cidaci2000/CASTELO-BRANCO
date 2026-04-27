<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flwrs · assinatura mensal</title>
  <!-- mesma fonte e ícones -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #fefaf5;
      font-family: 'Inter', sans-serif;
      color: #2a2a2a;
      line-height: 1.5;
    }

    .container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    /* ===== HEADER (com seta de voltar) ===== */
    header {
      padding: 1.5rem 0 1rem 0;
      border-bottom: 1px solid rgba(183, 164, 160, 0.15);
    }

    .header-flex {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .back-button {
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: #b2a19b;
      transition: color 0.2s;
      margin-right: 0.5rem;
    }

    .back-button:hover {
      color: #c06f8b;
    }

    .back-button .material-symbols-outlined {
      font-size: 2rem;
      font-weight: 300;
    }

    .logo-area {
      display: flex;
      align-items: baseline;
      gap: 0.5rem;
    }

    .logo-word {
      font-size: 2rem;
      font-weight: 300;
      letter-spacing: 2px;
      color: #4f4a45;
      text-transform: lowercase;
    }

    .logo-word strong {
      font-weight: 500;
      color: #c0859d;       
    }

    .tagline-header {
      font-size: 0.75rem;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #b2a19b;
      border-left: 1px solid #f7d5e7;
      padding-left: 0.8rem;
      margin-left: 0.3rem;
      font-weight: 300;
    }

    .nav-menu {
      display: flex;
      gap: 2.5rem;
      font-size: 0.9rem;
      font-weight: 400;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      align-items: center;
    }

    .nav-menu a {
      text-decoration: none;
      color: #5e5a55;
      transition: color 0.2s;
      font-size: 0.85rem;
      border-bottom: 1px solid transparent;
      padding-bottom: 4px;
    }

    .nav-menu a:hover {
      color: #c06f8b;
      border-bottom-color: #f7d5e7;
    }

    /* ===== PÁGINA DE ASSINATURA ===== */
    .assinatura-header {
      text-align: center;
      padding: 3rem 0 1rem 0;
      max-width: 800px;
      margin: 0 auto;
    }

    .assinatura-header h1 {
      font-size: 2.8rem;
      font-weight: 300;
      color: #3f3a35;
      line-height: 1.2;
      margin-bottom: 1rem;
    }

    .assinatura-header h1 span {
      color: #c68b9f;
      font-weight: 400;
      background: linear-gradient(120deg, #f7d5e7 0%, #f7d5e7 40%, transparent 80%);
      background-repeat: no-repeat;
      background-size: 100% 0.3em;
      background-position: bottom;
    }

    .assinatura-header p {
      font-size: 1.2rem;
      color: #6b625c;
      font-weight: 300;
      border-left: 4px solid #b2e4b3;
      padding-left: 1.5rem;
      margin: 2rem auto;
      max-width: 600px;
      text-align: left;
    }

    /* cards de planos */
    .planos-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      margin: 4rem 0 3rem;
    }

    .plano-card {
      background: #ffffffd9;
      backdrop-filter: blur(2px);
      border-radius: 32px 16px 32px 16px;
      padding: 2.5rem 1.8rem;
      box-shadow: 0 8px 18px -8px rgba(0, 0, 0, 0.05);
      border: 1px solid rgba(247, 213, 231, 0.5);
      transition: all 0.3s;
      display: flex;
      flex-direction: column;
      position: relative;
      overflow: hidden;
    }

    .plano-card:hover {
      border-color: #deef6e;
      box-shadow: 0 25px 35px -18px #c0859d;
      transform: scale(1.02);
    }

    .plano-card.destaque {
      border: 2px solid #deef6e;
      box-shadow: 0 15px 30px -12px #b2e4b3;
    }

    .plano-card.destaque::before {
      content: "⭐ mais popular";
      position: absolute;
      top: 1.2rem;
      right: -2.5rem;
      background: #deef6e;
      color: #2d3b2d;
      padding: 0.4rem 3rem;
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      transform: rotate(45deg);
      font-weight: 500;
    }

    .plano-icon {
      font-size: 3rem;
      margin-bottom: 1.2rem;
    }

    .plano-nome {
      font-size: 1.8rem;
      font-weight: 320;
      color: #b4849a;
      margin-bottom: 0.8rem;
    }

    .plano-preco {
      font-size: 2.2rem;
      font-weight: 400;
      color: #4a6b4b;
      margin-bottom: 1.5rem;
      border-bottom: 1px dashed #f7d5e7;
      padding-bottom: 1rem;
    }

    .plano-preco small {
      font-size: 0.9rem;
      font-weight: 300;
      color: #8e8077;
    }

    .plano-beneficios {
      list-style: none;
      margin-bottom: 2rem;
      flex: 1;
    }

    .plano-beneficios li {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      margin-bottom: 1rem;
      font-size: 0.95rem;
      color: #6f645c;
    }

    .plano-beneficios .material-symbols-outlined {
      color: #b2e4b3;
      font-size: 1.3rem;
    }

    .btn-assinar {
      background: transparent;
      border: 2px solid #deef6e;
      padding: 1rem;
      border-radius: 60px;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-decoration: none;
      color: #3a3a3a;
      font-weight: 500;
      transition: 0.25s;
      text-align: center;
      cursor: pointer;
      width: 100%;
    }

    .btn-assinar:hover {
      background: #deef6e;
      color: #1e2b1e;
    }

    /* como funciona */
    .como-funciona {
      background: linear-gradient(105deg, #fefaf5 0%, #fefaf5 30%, #f7f1e9 100%);
      padding: 3.5rem;
      border-radius: 60px 20px 60px 20px;
      margin: 3rem 0;
      border: 2px dashed #b2e4b3;
    }

    .como-funciona h2 {
      font-size: 2.2rem;
      font-weight: 300;
      color: #3f3a35;
      margin-bottom: 2rem;
      text-align: center;
    }

    .como-funciona h2 span {
      color: #c68b9f;
      font-weight: 400;
    }

    .passos {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 2rem;
    }

    .passo-item {
      text-align: center;
    }

    .passo-numero {
      background: #f7d5e7;
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      font-size: 1.4rem;
      font-weight: 400;
      color: #855e73;
      border: 2px solid white;
    }

    .passo-item h4 {
      font-size: 1.2rem;
      font-weight: 400;
      color: #b4849a;
      margin-bottom: 0.5rem;
    }

    .passo-item p {
      font-size: 0.9rem;
      color: #6f5b52;
      font-weight: 300;
    }

    /* depoimentos */
    .depoimentos {
      margin: 4rem 0;
    }

    .depoimentos h2 {
      font-size: 2rem;
      font-weight: 300;
      text-align: center;
      color: #b4849a;
      margin-bottom: 2.5rem;
    }

    .depoimentos-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
    }

    .depoimento-card {
      background: #faf3ef;
      border-radius: 32px 16px;
      padding: 2rem;
      border: 1px solid #f7d5e7;
    }

    .depoimento-texto {
      font-size: 0.95rem;
      color: #6f625a;
      font-style: italic;
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .depoimento-autor {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .autor-avatar {
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      background: linear-gradient(145deg, #f7d5e7, #b2e4b3);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #5a4b42;
      font-weight: 400;
      border: 2px solid white;
    }

    .autor-info h5 {
      font-size: 1rem;
      color: #855e73;
      margin-bottom: 0.2rem;
    }

    .autor-info p {
      font-size: 0.8rem;
      color: #b2a19b;
    }

    .info-rodape {
      display: flex;
      gap: 1.5rem;
      justify-content: center;
      padding: 2rem 0 3rem 0;
      flex-wrap: wrap;
    }

    .info-rodape span {
      background: #f7d5e7; 
      color:#5a4b42; 
      padding: 0.3rem 1.5rem; 
      border-radius: 40px; 
      font-size: 0.8rem;
    }

    .info-rodape span:last-child {
      background: #deef6e; 
      color:#2e472f;
    }

    footer {
      text-align: center;
      padding: 2rem 0 3rem 0;
      color: #a48d84;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      border-top: 1px solid rgba(183, 164, 160, 0.1);
    }

    footer span {
      color: #b2e4b3;
      font-weight: 400;
    }

    @media (max-width: 900px) {
      .planos-grid, .passos, .depoimentos-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .planos-grid, .passos, .depoimentos-grid {
        grid-template-columns: 1fr;
      }
      .header-flex {
        flex-direction: column;
        gap: 1rem;
      }
      .nav-menu {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }
    }
  </style>
</head>
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
            Flwrs <strong>·</strong>
          </div>
          <div class="tagline-header">
            “Flowers that feel like felling”
          </div>
        </div>
      </div>
      <nav class="nav-menu">
        <a href="#">Produtos</a>
        <a href="#">FAQ de delivery</a>
        <a href="info.php">Sobre nós</a>
        <a href="#">Login</a>
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
    <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
  </footer>

  <script>
    // pequenas interações (simulação)
    document.querySelectorAll('.btn-assinar').forEach(btn => {
      btn.addEventListener('click', function(e) {
        // o onclick já cuida do alert, mas mantemos pra não duplicar
      });
    });
  </script>
</body>
</html>