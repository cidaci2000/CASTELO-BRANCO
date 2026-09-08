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
            background: rgba(10, 10, 15, 0.5);
            backdrop-filter: blur(10px);
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

        nav {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        nav a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
            transition: 0.3s;
        }

        nav a:hover {
            color: var(--primary-purple);
        }

        .btn-nav {
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            transition: 0.3s;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-nav {
            background: var(--primary-purple);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary-nav:hover {
            background: white;
            color: black;
            transform: translateY(-2px);
        }

        .btn-logout {
            background: transparent;
            color: rgba(255,255,255,0.7);
            border: 1px solid var(--border);
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-logout:hover {
            color: #ef4444;
            border-color: #ef4444;
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

        .btn-group { display: flex; gap: 20px; margin-top: 40px; flex-wrap: wrap; }

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

        .module-card.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
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

        .leaflet-popup-content-wrapper {
            background: #0f0f1a !important;
            color: white !important;
            border: 1px solid var(--primary-purple);
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .leaflet-popup-tip {
            background: #0f0f1a !important;
            border: 1px solid var(--primary-purple);
        }
        .popup-title {
            font-weight: 800;
            color: var(--neon-glow);
            text-transform: uppercase;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        .popup-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
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

        .user-greeting {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 15px 25px;
            border-radius: 10px;
            background: var(--deep-purple);
            border: 1px solid var(--primary-purple);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            z-index: 9999;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.5s ease;
            box-shadow: 0 10px 40px rgba(79, 70, 229, 0.2);
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            border-color: #22c55e;
        }

        .toast.error {
            border-color: #ef4444;
        }

        @media (max-width: 968px) {
            .hero-image { display: none; }
            .modules-grid { grid-template-columns: 1fr; }
            .hero { text-align: center; }
            .btn-group { justify-content: center; }
            nav { justify-content: center; }
            nav a { font-size: 0.7rem; }
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
                gap: 15px;
                padding: 15px 5%;
            }
            nav {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            .btn-nav {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="glow" style="top: -100px; left: -100px;"></div>
    <div class="glow" style="bottom: 0; right: 0;"></div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <header>
        <a href="inicial.html" class="logo">SPORTLIFE</a>
        
        <nav id="mainNav">
            <!-- O conteúdo do nav será renderizado via JavaScript -->
        </nav>
    </header>

    <section class="hero">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1500&q=80" alt="Academia" class="hero-image">
        
        <div class="hero-content">
            <h1>DOMINE A SUA<br><span>EVOLUÇÃO.</span></h1>
            <p style="max-width: 500px; font-size: 1.1rem; color: var(--text-muted);">
                A plataforma definitiva de gestão esportiva com tecnologia de ponta para quem não aceita menos que a excelência.
            </p>
            <div class="btn-group" id="heroButtons">
                <!-- Botões serão renderizados via JavaScript -->
            </div>
        </div>
    </section>

    <section class="modules-grid" id="servicos">
        <a href="php/dashboard.php" class="module-card" id="cardAtletas">
            <span class="icon">🟣</span>
            <h3>Atletas</h3>
            <p>Monitore performance, biometria e frequência com dashboards em tempo real.</p>
        </a>
        <a href="produto.php" class="module-card" id="cardProduto">
            <span class="icon">💎</span>
            <h3>Premium Store</h3>
            <p>Gestão completa de estoque de suplementos e equipamentos de alto nível.</p>
        </a>
        <a href="servico.php" class="module-card" id="cardServico">
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
        // --- Configuração ---
        // CAMINHO PARA O CADASTRO/LOGIN
        const LOGIN_PATH = 'php/cadastro.php';
        
        // Redirecionamentos após login (serão feitos pelo PHP)
        // Mas mantemos para referência
        const REDIRECTS = {
            'admin': 'admin/dashboard.php',
            'instrutor': 'instrutor/dashboard.php',
            'usuario': 'aluno/dashboard.php'
        };

        // --- Funções de gerenciamento de sessão ---
        function getCurrentUser() {
            try {
                const userData = localStorage.getItem('sportlife_user');
                if (userData) {
                    return JSON.parse(userData);
                }
                return null;
            } catch (e) {
                return null;
            }
        }

        function setCurrentUser(userData) {
            localStorage.setItem('sportlife_user', JSON.stringify(userData));
        }

        function clearCurrentUser() {
            localStorage.removeItem('sportlife_user');
        }

        function isLoggedIn() {
            return getCurrentUser() !== null;
        }

        function getUserType() {
            const user = getCurrentUser();
            return user ? user.tipo : null;
        }

        // --- Toast notification ---
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + type;
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        // --- Função de logout ---
        function logout() {
            clearCurrentUser();
            
            // Fazer logout no backend
            fetch('php/logout.php', {
                method: 'POST',
                credentials: 'same-origin'
            }).catch(() => {});
            
            showToast('Logout realizado com sucesso!', 'success');
            setTimeout(() => {
                location.reload();
            }, 500);
        }

        // --- Renderização do menu baseado no usuário ---
        function renderNavigation() {
            const nav = document.getElementById('mainNav');
            const heroButtons = document.getElementById('heroButtons');
            const user = getCurrentUser();
            const isLogged = isLoggedIn();

            let navHTML = '';
            let heroHTML = '';

            if (isLogged && user) {
                // --- Usuário logado ---
                const tipo = user.tipo;
                const nome = user.nome || 'Usuário';
                
                // Menu para usuário logado
                navHTML = `
                    <a href="index.php">Home</a>
                    <a href="planos.php">Planos</a>
                    <a href="agenda.php">Agendar</a>
                    <span class="user-greeting">👋 ${nome}</span>
                    <button class="btn-logout" onclick="logout()">Sair</button>
                    <a href="${REDIRECTS[tipo] || '#'}" class="btn-nav btn-primary-nav">
                        ${tipo === 'admin' ? '⚙️ Admin' : tipo === 'instrutor' ? '📋 Instrutor' : '🏋️ Aluno'}
                    </a>
                `;

                // Botões do Hero para usuário logado
                heroHTML = `
                    <a href="${REDIRECTS[tipo] || '#'}" class="btn btn-primary">
                        ${tipo === 'admin' ? 'Painel Admin' : tipo === 'instrutor' ? 'Painel Instrutor' : 'Meu Treino'}
                    </a>
                    <a href="#servicos" class="btn" style="border: 1px solid var(--border); color: white;">Conheça Nosso Espaço</a>
                `;

                // Mostrar/Esconder cards baseado no tipo
                const cardProduto = document.getElementById('cardProduto');
                const cardServico = document.getElementById('cardServico');
                const cardAtletas = document.getElementById('cardAtletas');
                
                if (tipo === 'admin' || tipo === 'instrutor') {
                    cardProduto.style.display = 'block';
                    cardServico.style.display = 'block';
                    cardProduto.classList.remove('disabled');
                    cardServico.classList.remove('disabled');
                } else {
                    cardProduto.style.display = 'none';
                    cardServico.style.display = 'none';
                }
                cardAtletas.style.display = 'block';

            } else {
                // --- Usuário não logado - TODOS OS BOTÕES VÃO PARA php/cadastro.php ---
                navHTML = `
                    <a href="index.php">Home</a>
                    <a href="php/dashboard.php">Dashboard</a>
                    <a href="planos.php">Planos</a>
                    <a href="agenda.php">Agendar</a>
                    <a href="${LOGIN_PATH}" class="btn-nav btn-primary-nav">Acessar/Cadastrar</a>
                `;

                heroHTML = `
                    <a href="${LOGIN_PATH}" class="btn btn-primary">Iniciar Agora</a>
                    <a href="#servicos" class="btn" style="border: 1px solid var(--border); color: white;">Conheça Nosso Espaço</a>
                `;

                // Esconder cards para usuário não logado
                document.getElementById('cardProduto').style.display = 'none';
                document.getElementById('cardServico').style.display = 'none';
                document.getElementById('cardAtletas').style.display = 'block';
            }

            nav.innerHTML = navHTML;
            heroButtons.innerHTML = heroHTML;
        }

        // --- Inicialização do mapa ---
        function initMap() {
            const map = L.map('map').setView([-14.235, -51.925], 4);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© SportLife'
            }).addTo(map);

            const unidadesSportLife = [
                { nome: "SportLife - Unidade Cascavel (PR)", lat: -24.9555, lng: -53.4552, desc: "Centro de Treinamento de Rendimento" },
                { nome: "SportLife - Unidade Rio de Janeiro (RJ)", lat: -22.9068, lng: -43.1729, desc: "Arena Black High Performance" },
                { nome: "SportLife - Unidade Joinville (SC)", lat: -26.3044, lng: -48.8456, desc: "Estúdio de Biometria e Fisiologia" },
                { nome: "SportLife - Unidade Amazonas (AM)", lat: -3.1190, lng: -60.0217, desc: "Complexo de Performance do Norte" },
                { nome: "SportLife - Unidade Belo Horizonte (MG)", lat: -19.9167, lng: -43.9345, desc: "Intelligent Training Lab" },
                { nome: "SportLife - Unidade Brasília (DF)", lat: -15.7942, lng: -47.8822, desc: "Unidade Corporativa & Recovery 24h" }
            ];

            unidadesSportLife.forEach(unidade => {
                const popupContent = `
                    <div class="popup-title">${unidade.nome}</div>
                    <div class="popup-desc">${unidade.desc}</div>
                `;
                L.marker([unidade.lat, unidade.lng])
                    .addTo(map)
                    .bindPopup(popupContent);
            });
        }

        // --- Verificação de sessão no PHP ---
        function checkPHPSession() {
            fetch('php/check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in && data.usuario) {
                        // Sincronizar com localStorage
                        const currentUser = getCurrentUser();
                        if (!currentUser || currentUser.id !== data.usuario.id) {
                            setCurrentUser({
                                id: data.usuario.id,
                                nome: data.usuario.nome,
                                tipo: data.usuario.tipo,
                                email: data.usuario.email
                            });
                            // Mostrar toast de boas-vindas
                            showToast(`Bem-vindo(a) ${data.usuario.nome}!`, 'success');
                        }
                        renderNavigation();
                    } else if (data.logged_in === false) {
                        // Usuário não está logado no PHP
                        if (isLoggedIn()) {
                            clearCurrentUser();
                        }
                        renderNavigation();
                    }
                })
                .catch((error) => {
                    console.warn('Erro ao verificar sessão PHP:', error);
                    // Fallback: usar localStorage apenas
                    renderNavigation();
                });
        }

        // --- Inicialização ---
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            checkPHPSession();

            // Verificar se o usuário acabou de fazer login (via redirect do PHP)
            const urlParams = new URLSearchParams(window.location.search);
            const loginSuccess = urlParams.get('login');
            if (loginSuccess === 'success') {
                // Remove o parâmetro da URL
                window.history.replaceState({}, document.title, window.location.pathname);
                // Recarregar o estado do usuário
                checkPHPSession();
            }
        });

        // --- Expor funções globalmente ---
        window.logout = logout;
        window.REDIRECTS = REDIRECTS;
        window.getCurrentUser = getCurrentUser;
        window.isLoggedIn = isLoggedIn;
        window.getUserType = getUserType;
        window.LOGIN_PATH = LOGIN_PATH;
    </script>
</body>
</html>