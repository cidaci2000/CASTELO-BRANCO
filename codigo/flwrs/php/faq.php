<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flwrs · FAQ de delivery</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/faq.css">
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

    /* ===== FAQ HEADER ===== */
    .faq-header {
        padding: 4rem 0 2.5rem;
        text-align: center;
        border-bottom: 1px solid rgba(180, 165, 160, 0.06);
    }

    .faq-header h1 {
        font-size: 2.8rem;
        font-weight: 300;
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: #2d2825;
    }

    .faq-header h1 span {
        color: #f07d9d;
        font-weight: 400;
        position: relative;
    }

    .faq-header h1 span::before {
        content: '';
        position: absolute;
        bottom: 2px;
        left: 0;
        width: 100%;
        height: 6px;
        background: #f7d6e7;
        z-index: -1;
    }

    .faq-header p {
        margin-top: 1rem;
        font-size: 1.05rem;
        color: #6d6560;
        font-weight: 300;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ===== FAQ BUSCA ===== */
    .faq-busca {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid rgba(180, 165, 160, 0.12);
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        margin: 2.5rem auto 2rem;
        max-width: 500px;
        transition: all 0.3s ease;
    }

    .faq-busca:focus-within {
        border-color: #e94e77;
        box-shadow: 0 0 0 3px rgba(233, 78, 119, 0.08);
    }

    .faq-busca .material-symbols-outlined {
        color: #a8958f;
        font-size: 1.3rem;
    }

    .faq-busca input {
        border: none;
        background: transparent;
        padding: 0.5rem 0.8rem;
        font-size: 0.9rem;
        width: 100%;
        color: #3d3835;
        outline: none;
        font-family: inherit;
    }

    .faq-busca input::placeholder {
        color: #a8958f;
        font-weight: 300;
    }

    /* ===== FAQ CATEGORIAS ===== */
    .faq-categorias {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        justify-content: center;
        padding: 1rem 0 3rem;
    }

    .categoria-btn {
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

    .categoria-btn:hover {
        border-color: #e94e77;
        color: #e94e77;
        transform: translateY(-2px);
    }

    .categoria-btn.ativo {
        background: #e94e77;
        border-color: #e94e77;
        color: white;
        box-shadow: 0 4px 15px rgba(233, 78, 119, 0.2);
    }

    /* ===== FAQ LISTA (ACORDEÃO) ===== */
    .faq-lista {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        padding: 1rem 0 3rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .faq-item {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(180, 165, 160, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        border-color: rgba(180, 165, 160, 0.15);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    .faq-item.escondido {
        display: none;
    }

    .faq-pergunta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem 1.8rem;
        cursor: pointer;
        transition: background 0.3s ease;
        user-select: none;
    }

    .faq-pergunta:hover {
        background: rgba(184, 122, 142, 0.03);
    }

    .faq-pergunta h3 {
        font-size: 1rem;
        font-weight: 500;
        color: #2d2825;
        margin: 0;
        text-transform: none;
        letter-spacing: 0.02em;
    }

    .faq-pergunta .material-symbols-outlined {
        color: #a8958f;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .faq-item.aberto .faq-pergunta .material-symbols-outlined {
        transform: rotate(180deg);
    }

    .faq-item.aberto .faq-pergunta h3 {
        color: #e94e77;
    }

    .faq-resposta {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .faq-item.aberto .faq-resposta {
        max-height: 300px;
    }

    .faq-resposta p {
        padding: 0 1.8rem 1.5rem;
        color: #6d6560;
        font-weight: 300;
        line-height: 1.7;
        font-size: 0.95rem;
        margin: 0;
    }

    /* ===== CONTATO DIRETO ===== */
    .contato-direto {
        background: linear-gradient(150deg, #fde8f3, #fefaf5 50%, #c8e8d8);
        padding: 3.5rem 3rem;
        border-radius: 28px;
        text-align: center;
        margin: 2rem 0 3rem;
        border: 1px solid rgba(180, 165, 160, 0.06);
    }

    .contato-direto h2 {
        font-size: 2rem;
        font-weight: 300;
        letter-spacing: -0.02em;
        color: #2d2825;
    }

    .contato-direto h2 span {
        color: #f07d9d;
        font-weight: 500;
    }

    .contato-direto p {
        color: #6d6560;
        margin: 0.8rem 0 2rem;
        font-weight: 300;
    }

    .contato-botoes {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }

    .btn-contato {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        background: white;
        padding: 0.7rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        color: #2d2825;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border: 1px solid rgba(180, 165, 160, 0.08);
        transition: all 0.3s ease;
    }

    .btn-contato:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
        border-color: #e94e77;
        color: #e94e77;
    }

    .btn-contato .material-symbols-outlined {
        font-size: 1.2rem;
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
        .faq-header h1 {
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
        
        .faq-header h1 {
            font-size: 2rem;
        }
        
        .faq-pergunta {
            padding: 1rem 1.2rem;
        }
        
        .faq-pergunta h3 {
            font-size: 0.9rem;
        }
        
        .faq-resposta p {
            padding: 0 1.2rem 1.2rem;
            font-size: 0.9rem;
        }
        
        .contato-direto {
            padding: 2.5rem 1.5rem;
        }
        
        .contato-direto h2 {
            font-size: 1.6rem;
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
        
        .faq-header h1 {
            font-size: 1.6rem;
        }
        
        .faq-busca {
            padding: 0.4rem 1.2rem;
            margin: 2rem auto 1.5rem;
        }
        
        .faq-busca input {
            font-size: 0.8rem;
        }
        
        .categoria-btn {
            padding: 0.4rem 1.2rem;
            font-size: 0.65rem;
        }
        
        .contato-botoes {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-contato {
            width: 100%;
            justify-content: center;
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
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
    }

    /* ===== ANIMAÇÃO DE ENTRADA ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .faq-item {
        animation: fadeInUp 0.5s ease forwards;
    }

    .faq-item:nth-child(2) { animation-delay: 0.05s; }
    .faq-item:nth-child(3) { animation-delay: 0.1s; }
    .faq-item:nth-child(4) { animation-delay: 0.15s; }
    .faq-item:nth-child(5) { animation-delay: 0.2s; }
    .faq-item:nth-child(6) { animation-delay: 0.25s; }
    .faq-item:nth-child(7) { animation-delay: 0.3s; }
    .faq-item:nth-child(8) { animation-delay: 0.35s; }
</style>
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
        <a href="#" style="color:#c06f8b; border-bottom-color:#f7d5e7;">FAQ de delivery</a>
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
<p>flwrs — <span>“Flowers that feel like feeling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<script>
  function toggleFaq(element) {
    const faqItem = element.closest('.faq-item');
    // Alterna entre as classes 'aberto' e 'fechado'
    faqItem.classList.toggle('aberto');
}

// Função para filtrar por categoria
function filtrarCategoria(categoria, botao) {
    // Atualizar botão ativo
    document.querySelectorAll('.categoria-btn').forEach(btn => {
        btn.classList.remove('ativo');
    });
    botao.classList.add('ativo');

    // Mostrar/esconder itens
    const itens = document.querySelectorAll('.faq-item');
    if (categoria === 'todos') {
        itens.forEach(item => {
            item.style.display = 'block';
            // Remove a classe escondido se existir
            item.classList.remove('escondido');
        });
    } else {
        itens.forEach(item => {
            if (item.dataset.categoria === categoria) {
                item.style.display = 'block';
                item.classList.remove('escondido');
            } else {
                item.style.display = 'none';
                item.classList.add('escondido');
            }
        });
    }
}

// Função de busca simples
function filtrarPerguntas(texto) {
    const termo = texto.toLowerCase().trim();
    const itens = document.querySelectorAll('.faq-item');
    let temResultado = false;
    
    itens.forEach(item => {
        const pergunta = item.querySelector('.faq-pergunta h3').innerText.toLowerCase();
        if (pergunta.includes(termo) || termo === '') {
            item.style.display = 'block';
            item.classList.remove('escondido');
            temResultado = true;
        } else {
            item.style.display = 'none';
            item.classList.add('escondido');
        }
    });
}

// Inicialização quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    // Abrir o primeiro item por padrão
    const primeiroItem = document.querySelector('.faq-item');
    if (primeiroItem) {
        primeiroItem.classList.add('aberto');
    }
    
    // Adicionar evento de busca com debounce
    const buscaInput = document.querySelector('.faq-busca input');
    if (buscaInput) {
        buscaInput.addEventListener('keyup', function() {
            filtrarPerguntas(this.value);
            
            // Resetar botões de categoria
            document.querySelectorAll('.categoria-btn').forEach(btn => {
                btn.classList.remove('ativo');
            });
            const btnTodos = document.querySelector('.categoria-btn[onclick*="todos"]');
            if (btnTodos) {
                btnTodos.classList.add('ativo');
            }
        });
    }
});
</script>
</body>
</html>