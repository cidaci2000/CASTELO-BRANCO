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
  <link rel="stylesheet" href="css/planos.css">
  <style>
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

  <script src="js/planos.js"></script>
</body>
</html>