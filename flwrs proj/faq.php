<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flwrs · FAQ de delivery</title>
  <!-- mesma fonte e ícones -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="css/faq.css">
  <style>
  </style>
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
            Flwrs <strong>·</strong>
          </div>
          <div class="tagline-header">
            “Flowers that feel like felling”
          </div>
        </div>
      </div>
      <nav class="nav-menu">
        <a href="produtos.php">Produtos</a>
        <a href="#" style="color:#c06f8b; border-bottom-color:#f7d5e7;">FAQ de delivery</a>
        <a href="info.php">Sobre nós</a>
        <a href="login.php">Login</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <!-- cabeçalho da página -->
    <section class="faq-header">
      <h1><span>FAQ de delivery</span> · dúvidas frequentes</h1>
      <p>Tudo o que você precisa saber sobre prazos, áreas de entrega, embalagens e cuidados com suas flores.</p>
    </section>

    <!-- busca (opcional, apenas visual) -->
    <div class="faq-busca">
      <span class="material-symbols-outlined">search</span>
      <input type="text" placeholder="buscar por palavra-chave..." onkeyup="filtrarPerguntas(this.value)">
    </div>

    <!-- categorias -->
    <div class="faq-categorias">
      <button class="categoria-btn ativo" onclick="filtrarCategoria('todos', this)">todos</button>
      <button class="categoria-btn" onclick="filtrarCategoria('entrega', this)">entrega</button>
      <button class="categoria-btn" onclick="filtrarCategoria('prazos', this)">prazos</button>
      <button class="categoria-btn" onclick="filtrarCategoria('embalagem', this)">embalagem</button>
      <button class="categoria-btn" onclick="filtrarCategoria('cuidados', this)">cuidados</button>
    </div>

    <!-- lista de perguntas (acordeão) -->
    <section class="faq-lista" id="faqLista">
      <!-- entrega 1 -->
      <div class="faq-item" data-categoria="entrega">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>📍 Quais são as áreas de entrega?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Entregamos em toda a cidade e região metropolitana. Consulte no momento da compra se seu CEP está coberto. Estamos sempre expandindo!</p>
        </div>
      </div>

      <!-- prazos 1 -->
      <div class="faq-item" data-categoria="prazos">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>⏱️ Qual o prazo de entrega?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Entregamos no mesmo dia para pedidos feitos até às 14h (dentro da área de cobertura). Para os demais, o prazo é de 1 a 2 dias úteis.</p>
        </div>
      </div>

      <!-- embalagem 1 -->
      <div class="faq-item" data-categoria="embalagem">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>📦 Como é a embalagem?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Todas as embalagens são ecológicas e feitas com materiais recicláveis. Os buquês vêm protegidos em papel kraft e fitas de algodão.</p>
        </div>
      </div>

      <!-- cuidados 1 -->
      <div class="faq-item" data-categoria="cuidados">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>💧 Como cuidar das flores depois da entrega?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Corte os caules em diagonal, troque a água a cada 2 dias e mantenha em local arejado, longe do sol direto. Incluímos um guia em cada entrega.</p>
        </div>
      </div>

      <!-- entrega 2 -->
      <div class="faq-item" data-categoria="entrega">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>🚚 Posso agendar a entrega para uma data específica?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Sim! No checkout você pode escolher a data desejada. Recomendamos agendar com pelo menos 3 dias de antecedência para datas especiais.</p>
        </div>
      </div>

      <!-- prazos 2 -->
      <div class="faq-item" data-categoria="prazos">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>📅 E se eu precisar alterar a data de entrega?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Entre em contato conosco com até 24h de antecedência. Teremos prazer em remarcar sem custo adicional.</p>
        </div>
      </div>

      <!-- embalagem 2 -->
      <div class="faq-item" data-categoria="embalagem">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>🎁 Posso incluir uma mensagem personalizada?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Claro! Oferecemos cartões escritos à mão ou digitais. Basta escrever sua mensagem durante a compra.</p>
        </div>
      </div>

      <!-- cuidados 2 -->
      <div class="faq-item" data-categoria="cuidados">
        <div class="faq-pergunta" onclick="toggleFaq(this)">
          <h3>🌸 As flores são frescas?</h3>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
        <div class="faq-resposta">
          <p>Todas as flores são selecionadas diariamente e entregues no mesmo dia ou no dia seguinte, garantindo frescor e durabilidade.</p>
        </div>
      </div>
    </section>

    <!-- contato direto -->
    <section class="contato-direto">
      <h2><span>não encontrou</span> o que procurava?</h2>
      <p>Nossa equipe está pronta para ajudar com todas as suas dúvidas</p>
      <div class="contato-botoes">
        <a href="#" class="btn-contato" onclick="alert('WhatsApp: (45) 99999-9999 (simulação)')">
          <span class="material-symbols-outlined">chat</span> whatsapp
        </a>
        <a href="#" class="btn-contato" onclick="alert('E-mail: central.flwrs@gmail.com (simulação)')">
          <span class="material-symbols-outlined">mail</span> e-mail
        </a>
      </div>
    </section>

    <!-- rodapé de contato (reforço) -->
    <div class="info-rodape">
      <span>📞 (45) 99999-9999</span>
      <span>📮 central.flwrs@gmail.com</span>
    </div>
  </main>

  <footer>
    <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
  </footer>

<script src="js/faq.js"></script>
</body>
</html>