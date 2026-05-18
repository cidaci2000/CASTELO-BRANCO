<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportLife | Elite Performance</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syncopate:wght@700&family=Plus+Jakarta+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary-purple: #4f46e5;
            --deep-purple: #1e1b4b;
            --neon-glow: #818cf8;
            --bg-black: #0a0a0f;
            --card-gray: rgba(255, 255, 255, 0.03);
            --border: rgba(79, 70, 229, 0.15); 
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-black);
            color: white;
            overflow-x: hidden;
            line-height: 1.6;
        }

        .glow {
            position: fixed;
            width: 400px;
            height: 400px;
            background: #3730a3;
            filter: blur(150px);
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
        }

        header {
            padding: 30px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: absolute;
            width: 100%;
            z-index: 100;
        }

        .logo {
            font-family: 'Syncopate', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 4px;
            background: linear-gradient(to right, #e2e8f0, var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        nav a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            margin-left: 35px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
            transition: 0.3s;
        }

        nav a:hover {
            color: var(--primary-purple);
        }

        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 5%;
            background: radial-gradient(circle at 80% 20%, var(--deep-purple) 0%, transparent 50%);
        }

        .hero-content {
            flex: 1;
            z-index: 10;
        }

        .hero-content h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(4.5rem, 12vw, 9rem);
            line-height: 0.85;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: white;
        }

        .hero-content h1 span {
            display: block;
            background: linear-gradient(90deg, #fff, var(--primary-purple), #fff);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 3s linear infinite;
        }

        @keyframes shine {
            to { background-position: 200% center; }
        }

        .hero-image {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 50%;
            height: 90%;
            object-fit: cover;
            mask-image: linear-gradient(to left, black 70%, transparent 100%),
                        linear-gradient(to top, black 80%, transparent 100%);
            mask-composite: intersect;
            filter: grayscale(1) brightness(0.6) contrast(1.1);
            z-index: 1;
        }

        .btn-group { display: flex; gap: 20px; margin-top: 40px; }

        .btn {
            padding: 18px 40px;
            border-radius: 4px;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 2px;
            transition: 0.4s;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary-purple);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            background: white;
            color: black;
            transform: translateY(-5px);
        }

        .modules-grid {
            padding: 100px 5%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .module-card {
            background: var(--card-gray);
            border: 1px solid var(--border);
            padding: 50px 40px;
            border-radius: 20px;
            transition: 0.5s;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .module-card:hover {
            background: rgba(79, 70, 229, 0.05);
            border-color: var(--primary-purple);
            transform: translateY(-10px);
        }

        .map-section {
            padding: 100px 5%;
            background: linear-gradient(to bottom, var(--bg-black), #0f0f1a);
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 20px;
            border: 1px solid var(--border);
            filter: hue-rotate(220deg) brightness(0.6) invert(1) contrast(1.2);
        }

        footer {
            padding: 100px 5% 40px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .footer-logo {
            font-family: 'Syncopate', sans-serif;
            font-size: 4vw;
            color: var(--primary-purple);
            opacity: 0.1;
        }

        @media (max-width: 968px) {
            .hero-image { display: none; }
            .modules-grid { grid-template-columns: 1fr; }
            .hero { text-align: center; }
            .btn-group { justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="glow" style="top: -100px; left: -100px;"></div>
    <div class="glow" style="bottom: 0; right: 0;"></div>

    <header>
        <a href="inicial.html" class="logo">SPORTLIFE</a>
        
        <nav>
            <a href="inicial.html">Home</a>
            
            <a href="adm.php">Administrador</a>
            <a href="produto.php">Produtos</a>
            <a href="servico.php">Serviços</a>
            <a href="planos.php">Planos</a>
            <a href="agenda.php">Agendar</a>
            <a href="cadastro.php" class="btn-primary" style="padding: 10px 20px; border-radius: 50px;">Acessar/Cadastrar</a>
        </nav>
    </header>

    <section class="hero">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1500&q=80" alt="Academia" class="hero-image">
        
        <div class="hero-content">
            <h1>DOMINE A SUA<br><span>EVOLUÇÃO.</span></h1>
            <p style="max-width: 500px; font-size: 1.1rem; color: var(--text-muted);">
                A plataforma definitiva de gestão esportiva com tecnologia de ponta para quem não aceita menos que a excelência.
            </p>
            <div class="btn-group">
                <a href="cadastro.html" class="btn btn-primary">Iniciar Agora</a>
                <a href="#servicos" class="btn" style="border: 1px solid var(--border); color: white;">Conheça Nosso Espaço </a>
            </div>
        </div>
    </section>

    <section class="modules-grid" id="servicos">
        <a href="usuario.html" class="module-card">
            <span class="icon">🟣</span>
            <h3>Atletas</h3>
            <p>Monitore performance, biometria e frequência com dashboards em tempo real.</p>
        </a>
        <a href="produto.html" class="module-card">
            <span class="icon">💎</span>
            <h3>Premium Store</h3>
            <p>Gestão completa de estoque de suplementos e equipamentos de alto nível.</p>
        </a>
        <a href="servico.html" class="module-card">
            <span class="icon">⚡</span>
            <h3>Intelligent Training</h3>
            <p>Agendamentos e rotinas de treino otimizadas por algoritmos de performance.</p>
        </a>
    </section>

    <section class="map-section">
        <div id="map"></div>
    </section>

    <footer>
        <div class="footer-logo">SPORTLIFE ELITE</div>
        <p style="color: #334155; letter-spacing: 5px; font-size: 0.7rem; font-weight: 800; margin-top: 20px;">EST. 2026 | PREMIUM INTERFACE</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-25.45, -49.27], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© SportLife'
        }).addTo(map);

        // --- Controle de acesso por tipo de usuário ---
        const tipoUsuario = localStorage.getItem('tipoUsuario') || 'usuario'; // Simulação

        // Menu
        const linkProduto = document.querySelector('nav a[href="produto.html"]');
        const linkServico = document.querySelector('nav a[href="servico.html"]');

        if(tipoUsuario !== 'admin'){
            linkProduto.style.display = 'none';
            linkServico.style.display = 'none';
        }

        // Cards na home
        const cardProduto = document.querySelector('.modules-grid a[href="produto.html"]');
        const cardServico = document.querySelector('.modules-grid a[href="servico.html"]');

        if(tipoUsuario !== 'admin'){
            cardProduto.style.display = 'none';
            cardServico.style.display = 'none';
        }
    </script>
</body>
</html>