<?php
require_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🐰 Receitas de Páscoa - Candy Love's</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
       
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #FFFFFF 0%, #F8F9FB 50%, #FFFFFF 100%);
            color: #3a3a3a;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Efeito de fundo suave */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(135, 206, 250, 0.02) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(255, 192, 203, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Ovos de Páscoa flutuantes */
        .easter-egg {
            position: fixed;
            z-index: 1;
            user-select: none;
            cursor: default;
            font-size: 2.5em;
            animation: float-egg linear infinite;
            opacity: 0.6;
        }

        @keyframes float-egg {
            0% {
                transform: translateY(-100px) translateX(0) rotate(0deg);
                opacity: 0.5;
            }
            100% {
                transform: translateY(100vh) translateX(var(--sway-amount)) rotate(360deg);
                opacity: 0;
            }
        }

        /* Coelhinhos flutuando */
        .bunny {
            position: fixed;
            z-index: 1;
            user-select: none;
            cursor: default;
            font-size: 2em;
            animation: float-bunny ease-in-out infinite;
            opacity: 0.7;
        }

        @keyframes float-bunny {
            0%, 100% {
                transform: translateY(0px) translateX(0px);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-30px) translateX(20px);
                opacity: 0.5;
            }
        }
       
        /* Header - Branco com detalhes Azul e Rosa Pastel */
        .header {
            background: linear-gradient(135deg, #FFFFFF 0%, #F0F8FF 50%, #FFF0F5 100%);
            padding: 70px 40px 50px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(135, 206, 250, 0.15);
            border-bottom: 5px solid #87CEEB;
            position: relative;
            overflow: hidden;
            margin-top: 0;
        }

        /* Decoração com ovos no header */
        .header::before {
            content: '🥚';
            position: absolute;
            font-size: 150px;
            opacity: 0.08;
            left: -40px;
            top: -60px;
            transform: rotate(-25deg);
        }

        .header::after {
            content: '🐰';
            position: absolute;
            font-size: 150px;
            opacity: 0.08;
            right: -40px;
            bottom: -60px;
            transform: rotate(25deg);
        }

        .header-content {
            position: relative;
            z-index: 2;
            animation: slideDown 0.8s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
       
        .header h1 {
            font-size: 3.8rem;
            font-weight: 800;
            font-family: 'Fredoka', sans-serif;
            color: #87CEEB;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.08);
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
       
        .header p {
            font-size: 1.25rem;
            color: #FF69B4;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }
       
        .container {
            max-width: 1150px;
            margin: 0 auto;
            padding: 50px 20px;
            position: relative;
            z-index: 2;
        }

        .section-header {
            text-align: center;
            margin-bottom: 55px;
            animation: fadeInUp 1s ease 0.3s both;
        }
       
        .section-title {
            color: #87CEEB;
            font-size: 3rem;
            font-weight: 800;
            font-family: 'Fredoka', sans-serif;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .section-subtitle {
            color: #FF69B4;
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Divisor decorativo */
        .decorative-divider {
            width: 150px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #87CEEB, #FFB6D9, #87CEEB, transparent);
            margin: 20px auto;
            border-radius: 2px;
            box-shadow: 0 2px 8px rgba(135, 206, 250, 0.1);
        }

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
       
        .receitas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
            gap: 35px;
            margin-bottom: 50px;
        }
       
        /* Cards Brancos com bordas Azul/Rosa Pastel */
        .receita-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #FAFBFD 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(135, 206, 250, 0.1);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 2px solid #E0E8F0;
            position: relative;
            cursor: pointer;
        }

        /* Decoração com ovinho */
        .receita-card::before {
            content: '🥚';
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 2rem;
            z-index: 5;
            opacity: 0.7;
            animation: egg-bounce 2.5s ease-in-out infinite;
        }

        @keyframes egg-bounce {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.85) translateY(30px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .receita-card:nth-child(1) { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both; }
        .receita-card:nth-child(2) { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both; }
        .receita-card:nth-child(3) { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s both; }
        .receita-card:nth-child(4) { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both; }
        .receita-card:nth-child(5) { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.5s both; }
        .receita-card:nth-child(6) { animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.6s both; }
       
        .receita-card:hover {
            box-shadow: 0 18px 55px rgba(135, 206, 250, 0.15);
            transform: translateY(-10px);
            border-color: #87CEEB;
        }

        /* Imagem com fundo suave */
        .receita-image-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #F0F8FF 0%, #FFF0F5 100%);
        }

        .receita-image-wrapper::before {
            content: '🐰';
            position: absolute;
            font-size: 4rem;
            opacity: 0.06;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
        }
       
        .receita-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            position: relative;
            z-index: 2;
        }

        .receita-card:hover img {
            transform: scale(1.1);
        }

        /* Badge com cores suaves */
        .receita-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #87CEEB 0%, #87CEEB 50%, #FFB6D9 100%);
            color: #FFFFFF;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            z-index: 4;
            box-shadow: 0 4px 12px rgba(135, 206, 250, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.8);
            font-family: 'Fredoka', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .receita-content {
            padding: 25px;
            color: #3a3a3a;
        }
       
        .receita-content h3 {
            color: #87CEEB;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            font-family: 'Fredoka', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .receita-emoji {
            font-size: 1.6rem;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
       
        .receita-content p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }
       
        /* Metadata com Azul e Rosa Pastel */
        .receita-meta {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
            font-size: 0.85rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, rgba(135, 206, 250, 0.08) 0%, rgba(255, 192, 203, 0.08) 100%);
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 600;
            color: #87CEEB;
            border-left: 3px solid #FFB6D9;
        }

        .meta-item i {
            color: #FFB6D9;
            font-size: 0.9rem;
        }
       
        /* Botão com Azul e Rosa */
        .receita-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #87CEEB 0%, #B0E0E6 50%, #FFB6D9 100%);
            color: #FFFFFF;
            border: 2px solid #E0E8F0;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(135, 206, 250, 0.15);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .receita-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(135, 206, 250, 0.25);
            border-color: #87CEEB;
        }

        .receita-btn:active {
            transform: translateY(-1px);
        }
       
        /* Botão Voltar */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #87CEEB 0%, #B0E0E6 100%);
            color: #FFFFFF;
            padding: 12px 30px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 700;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(135, 206, 250, 0.2);
            border: 2px solid #FFB6D9;
            font-size: 1rem;
            font-family: 'Fredoka', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
       
        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(135, 206, 250, 0.3);
            background: linear-gradient(135deg, #6CB4E8 0%, #9FD8E5 100%);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 35px 20px;
            background: linear-gradient(135deg, rgba(135, 206, 250, 0.06) 0%, rgba(255, 192, 203, 0.06) 100%);
            color: #87CEEB;
            border-top: 4px solid #FFB6D9;
            margin-top: 60px;
            font-weight: 700;
            font-size: 1.15rem;
            position: relative;
            z-index: 2;
            font-family: 'Fredoka', sans-serif;
        }

        footer p {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .header {
                padding: 50px 20px 40px;
            }

            .header h1 {
                font-size: 2.5rem;
            }

            .header p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .receitas-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .receita-card {
                border-radius: 18px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .receita-content h3 {
                font-size: 1.2rem;
            }

            .receita-btn {
                padding: 11px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<!-- Ovos de Páscoa caindo -->
<script>
    // Criar ovos de Páscoa flutuantes
    function createEasterEgg() {
        const egg = document.createElement('div');
        egg.className = 'easter-egg';
       
        const posX = Math.random() * window.innerWidth;
        egg.style.left = posX + 'px';
       
        const duration = Math.random() * 12 + 14;
        const swayAmount = (Math.random() - 0.5) * 150;
        egg.style.setProperty('--sway-amount', swayAmount + 'px');
        egg.style.animationDuration = duration + 's';
        egg.innerHTML = '🥚';
       
        document.body.appendChild(egg);
       
        setTimeout(() => egg.remove(), duration * 1000);
    }
   
    setInterval(createEasterEgg, 500);

    // Coelhinhos flutuando nos cantos
    function createBunnies() {
        const positions = [
            { top: '15%', left: '5%' },
            { top: '40%', right: '4%' },
            { bottom: '20%', left: '6%' },
            { bottom: '35%', right: '5%' }
        ];

        positions.forEach((pos, index) => {
            const bunny = document.createElement('div');
            bunny.className = 'bunny';
            bunny.innerHTML = '🐰';
            Object.assign(bunny.style, pos);
            bunny.style.animationDelay = (index * 2) + 's';
            document.body.appendChild(bunny);
        });
    }

    createBunnies();
</script>

<div class="header">
    <div class="header-content">
        <h1>🐰 RECEITAS DE PÁSCOA 🐰</h1>
        <p> Receitas especiais para decorar sua Páscoa </p>
    </div>
</div>

<div class="container">
    <a href="cliente-dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        <span>Voltar</span>
    </a>
   
    <div class="section-header">
        <h2 class="section-title">Receitas Pascais Deliciosas</h2>
        <div class="decorative-divider"></div>
        <p class="section-subtitle">Explore nossas deliciosas receitas tradicionais de Páscoa </p>
    </div>
   
    <div class="receitas-grid">
        <div class="receita-card">
            <div class="receita-badge">🐰 Clássico</div>
            <div class="receita-image-wrapper">
                <img src="https://claudia.abril.com.br/wp-content/uploads/2016/09/passo-a-passo-cupcake-pascoa_0.jpg?quality=70&strip=info&w=414&h=280&crop=1" alt="cupcake de Páscoa">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🧁</span> Cupcake de Páscoa</h3>
                <p>Cupcake fofinho e macio decorado com ovos de chocolate. Receita clássica de Páscoa!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 45min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Médio</div>
                </div>
                <button class="receita-btn">🥚 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🥚 Favorito</div>
            <div class="receita-image-wrapper">
                <img src="https://receitatodahora.com.br/wp-content/uploads/2023/04/4-receitas-de-ovo-de-colher-31-03-1-1200x675.jpg" alt="Ovo de Chocolate">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🍫</span> Ovos de Colher</h3>
                <p>Ovos de chocolate caseiro recheados. Perfeito para presentear nesta Páscoa!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 50min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Médio</div>
                </div>
                <button class="receita-btn">🥚 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🌸 Especial</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSDZUIB0NSjf_EsBrGJxF-TPuOzJS7hfesxXw&s" alt="coelinho Páscoa">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">💜</span> Coelhinho Pascalino</h3>
                <p>Mouse com cores pastel e decoração temática. Ideal para presentear!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 30min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Fácil</div>
                </div>
                <button class="receita-btn">🥚 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🍮 Doce</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRI6McRUlgJoe1nH87CGaqQt6wK2bRROAJh8g&s" alt="Pudim de Páscoa">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🍮</span> Pudim Pascalino</h3>
                <p>Pudim cremoso com decoração de Páscoa. Sobremesa refrescante e deliciosa!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 40min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Fácil</div>
                </div>
                <button class="receita-btn">🥚 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🐣 Colorido</div>
            <div class="receita-image-wrapper">
                <img src="https://i.ytimg.com/vi/C2ib95kn3kI/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAiCKNXjC03NdhdM3W-cXkf7RJFEw" alt="Bolo de Cenoura">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🥕</span> Bolo de Cenoura</h3>
                <p>Bolo úmido de cenoura com chocolate. Tradicional e perfeito para Páscoa!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 45min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Médio</div>
                </div>
                <button class="receita-btn">🥚 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🍪 Biscoito</div>
            <div class="receita-image-wrapper">
                <img src="https://guiadacozinha.com.br/wp-content/uploads/2019/11/biscoitinhos-de-pascoa.jpg" alt="Biscoitos Pascalinos">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🐰</span> Biscoitos Pascalinos</h3>
                <p>Biscoitos decorados com formatos de Páscoa. Perfeitos para acompanhar café!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 25min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Fácil</div>
                </div>
                <button class="receita-btn">🥚 Ver Receita</button>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>🐰 Feliz Páscoa! Que suas receitas sejam deliciosas! 🐰</p>
</footer>

</body>
</html>
