<?php
require_once '../config/conexao.php';

// Verificar se usuário está logado
if (!estaLogado()) {
    redirecionar('login.php');
}

// Verificar se é cliente
if (!temPermissao(['cliente'])) {
    redirecionar('login.php');
}

iniciarSessao();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Candy Love's</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
            color: #3b2c2c;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            padding: 25px 40px;
            box-shadow: 0 8px 32px rgba(184, 59, 94, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            border-radius: 0 0 20px 20px;
        }

        .header h1 {
            font-size: 2.2rem;
            color: #fff;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 25px;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 25px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
        }

        .user-info i {
            font-size: 1.3rem;
        }

        .logout-btn {
            background: linear-gradient(135deg, #fcd5ce 0%, #fcc0b0 100%);
            color: #b83b5e;
            padding: 12px 28px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.15);
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(184, 59, 94, 0.25);
            background: linear-gradient(135deg, #ffb3a3 0%, #ffa08b 100%);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 20px;
            padding: 50px 40px;
            margin-bottom: 60px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(90, 70, 70, 0.08);
            border: 2px solid rgba(184, 59, 94, 0.1);
            animation: fadeInDown 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-card h2 {
            color: #b83b5e;
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.5px;
        }

        .welcome-card p {
            color: #8b7575;
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Section Title */
        .section-title {
            color: #b83b5e;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 35px;
            padding-left: 15px;
            border-left: 5px solid #b83b5e;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Playfair Display', serif;
        }

        .section-title i {
            font-size: 2.2rem;
        }

        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
            animation: fadeInUp 0.6s ease 0.2s both;
        }

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

        .feature-card {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(90, 70, 70, 0.08);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 320px;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(184, 59, 94, 0.1) 0%, rgba(217, 167, 177, 0.05) 100%);
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 16px 48px rgba(184, 59, 94, 0.15);
            border-color: #b83b5e;
        }

        .feature-card > * {
            position: relative;
            z-index: 1;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 20px;
            transition: transform 0.4s ease;
            filter: drop-shadow(0 4px 8px rgba(184, 59, 94, 0.2));
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .feature-icon-box {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f9d5e5 0%, #f3e1dd 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon-box {
            background: linear-gradient(135deg, #f3e1dd 0%, #edd0d9 100%);
            transform: rotate(-5deg);
        }

        .feature-card h3 {
            color: #b83b5e;
            font-size: 1.5rem;
            margin-bottom: 12px;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.3px;
        }

        .feature-card p {
            color: #8b7575;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .feature-badge {
            display: inline-block;
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fff;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: auto;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-badge {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.3);
        }

        /* Quick Stats */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
            animation: fadeInUp 0.6s ease 0.4s both;
        }

        .stat-card {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 6px 18px rgba(90, 70, 70, 0.08);
            border-left: 5px solid #b83b5e;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 28px rgba(184, 59, 94, 0.12);
        }

        .stat-number {
            font-size: 2.5rem;
            color: #b83b5e;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            color: #8b7575;
            font-size: 0.95rem;
            margin-top: 5px;
            font-weight: 500;
        }

        /* SEÇÃO SAZONAIS BETA */
        .sazonais-section {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(90, 70, 70, 0.08);
            margin-bottom: 60px;
            animation: fadeInUp 0.6s ease 0.6s both;
            border: 2px solid #b83b5e;
            position: relative;
            overflow: hidden;
        }

        .sazonais-section::before {
            content: '✨ BETA ✨';
            position: absolute;
            top: -10px;
            right: 30px;
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fff;
            padding: 5px 15px;
            border-radius: 0 0 15px 15px;
            font-weight: 700;
            font-size: 0.8rem;
            transform: rotate(2deg);
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.3);
        }

        .sazonais-section h3 {
            color: #b83b5e;
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sazonais-subtitle {
            color: #8b7575;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .sazonais-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .sazonais-card {
            background: linear-gradient(135deg, #f9d5e5 0%, #f3e1dd 100%);
            border-radius: 15px;
            padding: 30px 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            box-shadow: 0 6px 20px rgba(90, 70, 70, 0.1);
            position: relative;
            overflow: hidden;
        }

        .sazonais-card::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(184, 59, 94, 0.1) 0%, transparent 70%);
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .sazonais-card:hover::before {
            opacity: 1;
        }

        .sazonais-card:hover {
            transform: translateY(-12px) scale(1.05);
            box-shadow: 0 12px 36px rgba(184, 59, 94, 0.2);
            border-color: #b83b5e;
        }

        .sazonais-card > * {
            position: relative;
            z-index: 1;
        }

        .sazonais-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
        }

        .sazonais-card h4 {
            color: #b83b5e;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .sazonais-card p {
            color: #8b7575;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .sazonais-btn {
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .sazonais-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(184, 59, 94, 0.3);
        }

        /* Featured Section */
        .featured-section {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(90, 70, 70, 0.08);
            margin-bottom: 60px;
            animation: fadeInUp 0.6s ease 0.6s both;
        }

        .featured-section h3 {
            color: #b83b5e;
            font-size: 1.6rem;
            margin-bottom: 25px;
            font-family: 'Playfair Display', serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .featured-section i {
            font-size: 1.8rem;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .featured-item {
            background: linear-gradient(135deg, #f9d5e5 0%, #f3e1dd 100%);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .featured-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(184, 59, 94, 0.15);
        }

        .featured-item p {
            color: #b83b5e;
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px 30px;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fffaf7;
            border-radius: 20px 20px 0 0;
            margin-top: 80px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .container {
                padding: 30px 20px;
            }

            .welcome-card {
                padding: 40px 30px;
                margin-bottom: 50px;
            }

            .welcome-card h2 {
                font-size: 2rem;
            }

            .features {
                gap: 25px;
            }

            .feature-card {
                min-height: 280px;
                padding: 30px 20px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px;
                gap: 12px;
            }

            .header h1 {
                font-size: 1.6rem;
                width: 100%;
            }

            .user-info {
                width: 100%;
                flex-direction: column;
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .logout-btn {
                width: 100%;
                justify-content: center;
                padding: 12px 20px;
            }

            .container {
                padding: 25px 15px;
            }

            .welcome-card {
                padding: 30px 20px;
                margin-bottom: 40px;
                border-radius: 15px;
            }

            .welcome-card h2 {
                font-size: 1.8rem;
            }

            .welcome-card p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
                margin-bottom: 25px;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 20px;
                margin-bottom: 40px;
            }

            .feature-card {
                min-height: 260px;
                padding: 30px 20px;
            }

            .feature-icon {
                width: 70px;
                height: 70px;
            }

            .feature-card h3 {
                font-size: 1.3rem;
            }

            .stats-section {
                grid-template-columns: 1fr;
                gap: 15px;
                margin-bottom: 40px;
            }

            .sazonais-section {
                padding: 30px 20px;
                margin-bottom: 40px;
            }

            .sazonais-section h3 {
                font-size: 1.4rem;
            }

            .sazonais-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .featured-section {
                padding: 30px 20px;
                margin-bottom: 40px;
            }

            .featured-section h3 {
                font-size: 1.4rem;
                margin-bottom: 20px;
            }

            footer {
                margin-top: 50px;
                padding: 30px 20px;
                border-radius: 15px 15px 0 0;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 15px;
            }

            .header h1 {
                font-size: 1.4rem;
            }

            .user-info {
                padding: 10px 12px;
                font-size: 0.85rem;
                gap: 8px;
            }

            .logout-btn {
                padding: 10px 15px;
                font-size: 0.8rem;
            }

            .container {
                padding: 15px;
            }

            .welcome-card {
                padding: 25px 15px;
                margin-bottom: 30px;
            }

            .welcome-card h2 {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }

            .welcome-card p {
                font-size: 0.95rem;
            }

            .section-title {
                font-size: 1.3rem;
                margin-bottom: 20px;
                padding-left: 10px;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 15px;
                margin-bottom: 30px;
            }

            .feature-card {
                min-height: 240px;
                padding: 20px 15px;
            }

            .feature-icon-box {
                width: 80px;
                height: 80px;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 15px;
            }

            .feature-card h3 {
                font-size: 1.1rem;
                margin-bottom: 10px;
            }

            .feature-card p {
                font-size: 0.85rem;
                margin-bottom: 15px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-number {
                font-size: 2rem;
            }

            .sazonais-section {
                padding: 25px 15px;
            }

            .sazonais-section h3 {
                font-size: 1.2rem;
            }

            .sazonais-grid {
                grid-template-columns: 1fr;
            }

            .featured-section {
                padding: 25px 15px;
            }

            .featured-section h3 {
                font-size: 1.2rem;
            }

            .featured-grid {
                grid-template-columns: 1fr;
            }

            footer {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🍓 Candy Love's</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i>
            <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></strong></span>
        </div>
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Sair
        </a>
    </div>

    <!-- Container -->
    <div class="container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2>Bem-vindo(a) ao Candy Love's! 💗</h2>
            <p>Explore nossas receitas exclusivas, descubra novos sabores e compartilhe momentos deliciosos com quem você ama.</p>
        </div>

        <!-- Main Features -->
        <h2 class="section-title">
            <i class="fas fa-star"></i> Navegação Principal
        </h2>
        <div class="features">
            <div class="feature-card" onclick="window.location.href='receitas.php'">
                <div class="feature-icon-box">
                    <i class="fas fa-book" style="font-size: 3.5rem; color: #b83b5e;"></i>
                </div>
                <h3>Receitas</h3>
                <p>Explore nossas receitas exclusivas, desde clássicos até criações inovadoras</p>
                <span class="feature-badge">Acessar →</span>
            </div>

            <div class="feature-card" onclick="window.location.href='receitas-salvas.html'">
                <div class="feature-icon-box">
                    <i class="fas fa-bookmark" style="font-size: 3.5rem; color: #b83b5e;"></i>
                </div>
                <h3>Minhas Receitas</h3>
                <p>Acesse suas receitas guardadas e mantenha suas favoritas em um só lugar</p>
                <span class="feature-badge">Ver Salvas →</span>
            </div>

            <div class="feature-card" onclick="window.location.href='categorias.html'">
                <div class="feature-icon-box">
                    <i class="fas fa-th" style="font-size: 3.5rem; color: #b83b5e;"></i>
                </div>
                <h3>Categorias</h3>
                <p>Navegue por categorias para encontrar exatamente o que você procura</p>
                <span class="feature-badge">Explorar →</span>
            </div>

            <div class="feature-card" onclick="window.location.href='livros.html'">
                <div class="feature-icon-box">
                    <i class="fas fa-book-open" style="font-size: 3.5rem; color: #b83b5e;"></i>
                </div>
                <h3>Nossos Livros</h3>
                <p>Conheça nossas publicações exclusivas com as melhores receitas da vovó</p>
                <span class="feature-badge">Conferir →</span>
            </div>
        </div>

        <!-- Quick Stats -->
        <h2 class="section-title">
            <i class="fas fa-chart-bar"></i> Seus Destaques
        </h2>
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">127</div>
                <div class="stat-label">Receitas Disponíveis</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">32</div>
                <div class="stat-label">Categorias</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">4.9★</div>
                <div class="stat-label">Avaliação Média</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">📚</div>
                <div class="stat-label">Livros Publicados</div>
            </div>
        </div>

        <!-- SEÇÃO SAZONAIS BETA -->
        <div class="sazonais-section">
            <h3><i class="fas fa-sparkles"></i> Receitas Sazonais - BETA</h3>
            <p class="sazonais-subtitle">Explore nossas coleções especiais decoradas para diferentes épocas do ano!</p>
            
            <div class="sazonais-grid">
                <!-- NATAL -->
                <div class="sazonais-card">
                    <div class="sazonais-icon">🎄</div>
                    <h4>Receitas de Natal</h4>
                    <p>Receitas festivas decoradas com cores natalinas. Perfeito para sua ceia!</p>
                    <a href="receitas-sazonais-natal.php" class="sazonais-btn">Ver Beta →</a>
                </div>

                <!-- PÁSCOA -->
                <div class="sazonais-card">
                    <div class="sazonais-icon">🐰</div>
                    <h4>Receitas de Páscoa</h4>
                    <p>Receitas especiais com decoração pascalina. Tema pastel e ovos!</p>
                    <a href="receitas-sazonais-pascoa.php" class="sazonais-btn">Ver Beta →</a>
                </div>

                <!-- FESTA JUNINA -->
                <div class="sazonais-card">
                    <div class="sazonais-icon">🎪</div>
                    <h4>Festa Junina</h4>
                    <p>Receitas típicas com decoração de festa junina. São João é aqui!</p>
                    <a href="receitas-sazonais-junina.php" class="sazonais-btn">Ver Beta →</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer>
        <p>© 2026 Candy Love's — Receitas da Vovó • Feito com ❤️</p>
    </footer>
</body>
</html>