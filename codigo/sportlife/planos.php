<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syncopate:wght@700&family=Plus+Jakarta+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">

<title>Planos Sportlife</title>

<style>
:root {
    --primary: #818cf8;
    --primary-dark: #6366f1;
    --card: rgba(15, 15, 15, 0.8);
    --border: rgba(255,255,255,0.15);
    --text-muted: #94a3b8;
    --success: #22c55e;
}

* {
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans', sans-serif;
}

body {
    background:#000;
    color:white;
    overflow-x: hidden;
}

/* FUNDO */
.pricing-section {
    min-height:100vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:100px 5%;
    position:relative;
    background: 
        radial-gradient(circle at top right, rgba(129, 140, 248, 0.15), transparent),
        linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.95)),
        url("https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1600");
    background-size:cover;
    background-position:center;
    background-attachment: fixed;
}

/* LOGO TRANSPARENTE */
.bg-logo {
    position:fixed;
    bottom:20px;
    left:50%;
    transform:translateX(-50%);
    font-family:'Syncopate';
    font-size:6rem;
    opacity:0.03;
    letter-spacing:20px;
    pointer-events:none;
    white-space: nowrap;
}

/* HEADER */
.pricing-header {
    text-align:center;
    margin-bottom:80px;
    z-index: 2;
}

.pricing-header h2 {
    font-family:'Syncopate';
    font-size:clamp(1.5rem, 5vw, 2.8rem);
    text-transform: uppercase;
}

.pricing-header span {
    color:var(--primary);
}

.pricing-header p {
    color:var(--text-muted);
    margin-top:15px;
    font-weight: 300;
    letter-spacing: 2px;
}

/* SLIDER */
.slider-container {
    width:100%;
    max-width:1600px;
    z-index: 2;
}

.slider {
    display:flex;
    gap:30px;
    overflow-x:auto;
    scroll-snap-type:x mandatory;
    padding:40px 20px;
    scrollbar-width: thin;
    scrollbar-color: var(--primary) transparent;
}

.slider::-webkit-scrollbar {
    height:6px;
}

.slider::-webkit-scrollbar-thumb {
    background:var(--primary);
    border-radius:10px;
}

/* CARDS MAIORES E MAIS ALTOS */
.card {
    min-width:380px;
    min-height: 650px; /* Aumentado para comportar mais info */
    background:var(--card);
    border: 1px solid var(--border);
    border-radius:30px;
    padding:50px 40px;
    backdrop-filter:blur(20px);
    scroll-snap-align:center;
    transition:all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    flex-direction: column;
}

.card:hover {
    transform:translateY(-15px);
    border-color:var(--primary);
    box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 20px rgba(129, 140, 248, 0.2);
}

/* DESTAQUE */
.featured {
    border: 2px solid var(--primary);
    background: rgba(20, 20, 30, 0.85);
    transform: scale(1.05);
    position: relative;
}

.featured::before {
    content: "MAIS VENDIDO";
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--primary);
    padding: 5px 20px;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 1px;
}

/* TAG */
.tag {
    background:var(--primary);
    display:inline-block;
    padding:6px 16px;
    border-radius:50px;
    font-size:0.7rem;
    font-weight: 800;
    margin-bottom:20px;
    width: fit-content;
}

/* TEXTO */
.card h3 {
    font-size:1.8rem;
    font-family: 'Bebas Neue', sans-serif;
    letter-spacing: 1px;
}

.price {
    margin: 30px 0;
}

.price h1 {
    font-size:3.5rem;
    line-height: 1;
}

.old {
    text-decoration:line-through;
    color:#666;
    font-size: 1.1rem;
}

.discount {
    color:var(--success);
    font-size:0.9rem;
    font-weight: 700;
    display: block;
    margin-top: 5px;
}

/* LISTA DE INFORMAÇÕES */
.benefits-list {
    list-style:none;
    margin-top: 30px;
    flex-grow: 1; /* Empurra o botão para o rodapé */
}

.benefits-list li {
    margin-bottom:15px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #e2e8f0;
}

.benefits-list li.disabled {
    color:#555;
    text-decoration: line-through;
}

.check-icon {
    color: var(--primary);
    font-weight: bold;
}

/* BOTÃO */
.btn {
    display:block;
    text-align:center;
    padding:18px;
    margin-top:30px;
    background:var(--primary);
    border-radius:15px;
    text-decoration:none;
    color:white;
    font-weight:800;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition:0.3s;
}

.btn:hover {
    background:white;
    color:black;
    transform: scale(1.02);
}

/* RESPONSIVO */
@media(max-width:768px){
    .card {
        min-width:320px;
        min-height: 600px;
        padding:35px 25px;
    }
    .featured {
        transform: scale(1);
    }
    .pricing-header h2 { font-size: 2rem; }
}
</style>
</head>

<body>

<section class="pricing-section">

    <div class="pricing-header">
        <h2>Escolha seu <span>Desafio</span></h2>
        <p>ARRASTE PARA CONHECER OS DETALHES →</p>
    </div>

    <div class="slider-container">
        <div class="slider">

            <div class="card featured">
                <div class="tag">PREMIUM</div>
                <h3>Plano Black</h3>
                <div class="price">
                    <span class="old">R$159,90</span>
                    <h1>R$99,90</h1>
                    <span class="discount">MELHOR CUSTO-BENEFÍCIO</span>
                </div>
                <ul class="benefits-list">
                    <li><span class="check-icon">✓</span> Acesso a todas as unidades</li>
                    <li><span class="check-icon">✓</span> Leve 5 amigos por mês</li>
                    <li><span class="check-icon">✓</span> Área VIP e Relax (Cadeiras de massagem)</li>
                    <li><span class="check-icon">✓</span> Camiseta exclusiva da academia</li>
                    <li><span class="check-icon">✓</span> Acesso total ao app de treinos</li>
                    <li><span class="check-icon">✓</span> Bebida isotônica liberada</li>
                </ul>
                <a href="#" class="btn">Assinar Agora</a>
            </div>

            <div class="card">
                <div class="tag">RESULTADO</div>
                <h3>Performance</h3>
                <div class="price">
                    <h1>R$179,90</h1>
                </div>
                <ul class="benefits-list">
                    <li><span class="check-icon">✓</span> Avaliação Bioimpedância mensal</li>
                    <li><span class="check-icon">✓</span> Consultoria Nutricional</li>
                    <li><span class="check-icon">✓</span> Personal Trainer 2x na semana</li>
                    <li><span class="check-icon">✓</span> Plano de suplementação</li>
                    <li><span class="check-icon">✓</span> Armário fixo incluso</li>
                    <li><span class="check-icon">✓</span> Acesso a treinos de alta performance</li>
                </ul>
                <a href="#" class="btn">Começar Treino</a>
            </div>

            <div class="card">
                <div class="tag">ESSENCIAL</div>
                <h3>Plano Smart</h3>
                <div class="price">
                    <h1>R$139,90</h1>
                </div>
                <ul class="benefits-list">
                    <li><span class="check-icon">✓</span> Área de musculação completa</li>
                    <li><span class="check-icon">✓</span> Acesso à unidade escolhida</li>
                    <li><span class="check-icon">✓</span> Suporte de instrutores</li>
                    <li><span class="check-icon">✓</span> Chuveiros e vestiários</li>
                    <li class="disabled"><span>✕</span> Acesso a outras unidades</li>
                    <li class="disabled"><span>✕</span> Convite para acompanhante</li>
                </ul>
                <a href="#" class="btn">Selecionar</a>
            </div>

            <div class="card">
                <div class="tag">EXPERIENCE</div>
                <h3>Plano Elite</h3>
                <div class="price">
                    <h1>R$249,90</h1>
                </div>
                <ul class="benefits-list">
                    <li><span class="check-icon">✓</span> Acesso Ilimitado 24h</li>
                    <li><span class="check-icon">✓</span> Toalhas e Kit banho incluso</li>
                    <li><span class="check-icon">✓</span> Aulas de Yoga e Pilates</li>
                    <li><span class="check-icon">✓</span> Estacionamento gratuito VIP</li>
                    <li><span class="check-icon">✓</span> Networking Lounge</li>
                    <li><span class="check-icon">✓</span> Todos os benefícios do Plano Black</li>
                </ul>
                <a href="#" class="btn">Seja Elite</a>
            </div>

        </div>
    </div>

    <div class="bg-logo">SPORTLIFE</div>

</section>

</body>
</html>