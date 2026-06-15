<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Flwrs · Admin Console</title>
    <!-- Google Fonts + smooth base -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100;14..32,200;14..32,300;14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-flex">
            <div class="logo-area">
                <div class="logo-word"><strong>Flwrs</strong> studio</div>
                <div class="tagline-header">admin · cultivate</div>
            </div>
            <div class="nav-menu">
                <a href="#">dashboard</a>
                <a href="#">pedidos</a>
                <a href="#">estoque</a>
                <a href="#">clientes</a>
                <a href="#">configurações</a>
            </div>
            <div class="admin-badge">
                <i class="fas fa-seedling" style="font-size: 0.75rem;"></i> master access
            </div>
        </div>
    </header>

    <!-- admin overview hero (estilo adaptado da home) -->
    <div class="admin-hero">
        <div class="admin-greeting">
            <h2>Olá, <span>gestor floral</span> 🌸<br> Bem-vindo ao painel Flwrs.</h2>
            <div class="hero-sub" style="margin-top: 0.8rem; border-left-color: #deef6e; max-width: 480px;">
                <p>Gerencie pedidos, atualize o catálogo e acompanhe os arranjos com amor e precisão.</p>
            </div>
        </div>
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-title"><i class="far fa-calendar-alt"></i> pedidos esse mês</div>
                <div class="stat-number">147 <span class="stat-unit">un.</span></div>
                <div class="stat-trend"><i class="fas fa-arrow-up" style="color:#88aa6e;"></i> +12% em relação à semana anterior</div>
            </div>
            <div class="stat-card">
                <div class="stat-title"><i class="fas fa-boxes"></i> estoque ativo</div>
                <div class="stat-number">34 <span class="stat-unit">variedades</span></div>
                <div class="stat-trend">rosas · eucalipto · lírios</div>
            </div>
            <div class="stat-card">
                <div class="stat-title"><i class="fas fa-users"></i> clientes fiéis</div>
                <div class="stat-number">892 <span class="stat-unit">+</span></div>
                <div class="stat-trend">última compra nos últimos 30 dias</div>
            </div>
        </div>
    </div>

    <!-- Tabela de pedidos recentes (gestão de admin) -->
    <div class="section-title">
        <i class="fas fa-truck" style="margin-right: 10px; color:#b4849a;"></i> Pedidos em andamento
    </div>
    <div class="orders-table-wrapper">
        <table class="orders-table">
            <thead>
                <tr><th>ID Pedido</th><th>Cliente</th><th>Arranjo</th><th>Valor</th><th>Status</th><th>Ações</th></tr>
            </thead>
            <tbody id="orders-tbody">
                <!-- dados dinâmicos com js para simular gestão -->
                <tr><td>#FL-1024</td><td>Ana Beatriz</td><td>Buquê "Camélia Rosé"</td><td>R$ 189,00</td><td><span class="status-badge">Entregue</span></td><td><button class="action-btn" data-order="FL-1024">Detalhes</button></td></tr>
                <tr><td>#FL-1025</td><td>Carlos Mendes</td><td>Arranjo "Campo Suave"</td><td>R$ 275,00</td><td><span class="status-badge status-pending">Preparando</span></td><td><button class="action-btn" data-order="FL-1025">Atualizar</button></td></tr>
                <tr><td>#FL-1026</td><td>Larissa Fialho</td><td>Cesta "Jardim Secreto"</td><td>R$ 342,00</td><td><span class="status-badge">Enviado</span></td><td><button class="action-btn" data-order="FL-1026">Acompanhar</button></td></tr>
                <tr><td>#FL-1027</td><td>Rafaela Costa</td><td>Rosas do Amor (12 un)</td><td>R$ 129,90</td><td><span class="status-badge status-pending">Pendente</span></td><td><button class="action-btn" data-order="FL-1027">Processar</button></td></tr>
                <tr><td>#FL-1028</td><td>Juliana Tavares</td><td>Orquídea Exótica</td><td>R$ 415,00</td><td><span class="status-badge">Entregue</span></td><td><button class="action-btn" data-order="FL-1028">Nota fiscal</button></td></tr>
            </tbody>
        </table>
    </div>

    <!-- Grid de ações rápidas (espelhando os destaques da home com estilo admin) -->
    <div class="section-title">
        <i class="fas fa-magic" style="margin-right: 8px;"></i> Gestão rápida · florescer
    </div>
    <div class="admin-actions">
        <div class="admin-card">
            <div class="card-icon"><i class="fas fa-plus-circle"></i></div>
            <h3>Novo arranjo</h3>
            <p>Adicione composições florais, defina preços, descrição e estoque. Atualize o catálogo da loja.</p>
            <div class="card-link" id="addArrangementBtn"><i class="fas fa-leaf"></i>Cadastrar produto</div>
        </div>
        <div class="admin-card">
            <div class="card-icon"><i class="fas fa-truck-fast"></i></div>
            <h3>Logística & entregas</h3>
            <p>Acompanhe rotas, altere status de envio e notifique clientes automaticamente.</p>
            <div class="card-link" id="deliveryBtn"><i class="fas fa-shipping-fast"></i> Gerenciar entregas</div>
        </div>
        <div class="admin-card">
            <div class="card-icon"><i class="fas fa-chart-line"></i></div>
            <h3>Relatórios</h3>
            <p>Exporte métricas de vendas, produtos mais amados e fluxo de caixa.</p>
            <div class="card-link" id="reportBtn"><i class="fas fa-download"></i> Gerar relatório</div>
        </div>
    </div>

    <!-- Simulação de seção de clientes recentes / mensagens (com toque da home) -->
    <div class="sobre-preview" style="margin-top: 0.5rem; background: #fefaf5; border-top: 2px dashed #b2e4b3; border-bottom: 2px dashed #deef6e;">
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: center;">
            <div style="flex:1">
                <h4 style="font-size: 1.7rem; font-weight: 300; color:#3d3d3d;">Feedbacks <strong style="color:#b65f82;">recentes</strong></h4>
                <p style="color:#6f625a; margin-bottom: 0.5rem;">“O buquê chegou no timing perfeito, flores fresquíssimas. Administração nota 10!” — Camila R.</p>
                <p style="color:#6f625a;">“Adorei a experiência de compra, entrega rápida e cuidado nos detalhes. Flwrs é amor.” — Mateus S.</p>
                <div class="card-link" style="margin-top: 1rem; display: inline-block; background:#f7d5e7; color:#5e424f;" id="msgBtn">✉️ Responder mensagens</div>
            </div>
            <div style="flex:0.8; background:#f7d5e7; border-radius: 70% 30% 60% 40% / 40% 60% 30% 70%; min-height: 170px; display: flex; align-items: center; justify-content: center; background: linear-gradient(145deg, #deef6e, #b2e4b3, #f7d5e7); background-size: 180% 180%;">
                <span style="background:#fefaf5e0; padding: 1rem 2rem; border-radius: 50px; font-size: 1rem;">🌸 gratidão</span>
            </div>
        </div>
    </div>
</div>

<footer>
    <span>Flwrs studio</span> · cuidado botânico · admin dashboard v1.0<br>
    <p>Flwrs — <span>“Flowers that feel like felling”</span> — pequenos gestos, memórias eternas</p>
</footer>

<!-- toast container -->
<div id="toastMsg" style="position: fixed; bottom: 20px; right: 20px; z-index: 2000;"></div>

<script src="js/admin.js"></script>
</body>
</html>