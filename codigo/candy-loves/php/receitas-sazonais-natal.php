<?php
require_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎄 Receitas de Natal - Candy Love's</title>
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
            background: linear-gradient(180deg, #FFFFFF 0%, #F5F5F5 50%, #FFFFFF 100%);
            color: #3a3a3a;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Efeito de fundo com brilho suave */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(220, 20, 60, 0.02) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(220, 20, 60, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Flocos de neve AZUIS caindo */
        .snowflake {
            position: fixed;
            z-index: 1;
            user-select: none;
            cursor: default;
            font-size: 1.8em;
            animation: snowfall linear infinite;
            opacity: 0.85;
            color: #4A90E2;
            text-shadow: 0 0 10px rgba(74, 144, 226, 0.8);
            filter: drop-shadow(0 0 6px rgba(74, 144, 226, 0.6));
        }

        @keyframes snowfall {
            0% {
                transform: translateY(-50px) translateX(0) rotate(0deg);
                opacity: 0.95;
            }
            100% {
                transform: translateY(100vh) translateX(var(--sway-amount)) rotate(360deg);
                opacity: 0;
            }
        }

        /* Estrela de Natal Dourada */
        .christmas-star {
            position: fixed;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            font-size: 3.8rem;
            animation: star-twinkle 2.5s ease-in-out infinite;
            text-shadow: 0 0 25px rgba(255, 215, 0, 0.8), 0 0 40px rgba(255, 215, 0, 0.5);
            filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.7));
        }

        @keyframes star-twinkle {
            0%, 100% { opacity: 1; transform: translateX(-50%) scale(1); }
            50% { opacity: 0.7; transform: translateX(-50%) scale(1.2); }
        }

        /* Luzes Pisca-Pisca AZUIS e VERMELHAS */
        .light {
            position: fixed;
            z-index: 1;
            border-radius: 50%;
            box-shadow: 0 0 20px currentColor;
            animation: blink 1.6s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.3); }
        }

        .light.red {
            color: #DC143C;
            box-shadow: 0 0 25px #DC143C, inset 0 0 10px rgba(220,20,60,0.5);
        }
        .light.blue {
            color: #4A90E2;
            box-shadow: 0 0 25px #4A90E2, inset 0 0 10px rgba(74,144,226,0.5);
        }
        .light.white {
            color: #FFFFFF;
            box-shadow: 0 0 20px #FFFFFF, inset 0 0 8px rgba(255,255,255,0.6);
        }
       
        /* Header - Branco com detalhes Vermelho */
        .header {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F8F8 50%, #FFFFFF 100%);
            padding: 70px 40px 50px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(220, 20, 60, 0.15);
            border-bottom: 6px solid #DC143C;
            position: relative;
            overflow: hidden;
            margin-top: 0;
        }

        /* Decoração com flocos no header */
        .header::before {
            content: '❄️';
            position: absolute;
            font-size: 180px;
            opacity: 0.05;
            left: -60px;
            top: -80px;
            color: #DC143C;
        }

        .header::after {
            content: '❄️';
            position: absolute;
            font-size: 180px;
            opacity: 0.05;
            right: -60px;
            bottom: -80px;
            color: #DC143C;
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
            color: #DC143C;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
       
        .header p {
            font-size: 1.25rem;
            color: #4A90E2;
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
            color: #DC143C;
            font-size: 3rem;
            font-weight: 800;
            font-family: 'Fredoka', sans-serif;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .section-subtitle {
            color: #4A90E2;
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Divisor decorativo - Vermelho e Azul */
        .decorative-divider {
            width: 150px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #DC143C, #4A90E2, #DC143C, transparent);
            margin: 20px auto;
            border-radius: 2px;
            box-shadow: 0 2px 8px rgba(220, 20, 60, 0.1);
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
       
        /* Cards Brancos com Bordas Vermelhas */
        .receita-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #FAFAFA 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(220, 20, 60, 0.12);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 3px solid #DC143C;
            position: relative;
            cursor: pointer;
        }

        /* Decoração com fita vermelha */
        .receita-card::before {
            content: '🎀';
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 2.5rem;
            z-index: 5;
            opacity: 0.9;
            animation: ribbon-sway 2.5s ease-in-out infinite;
        }

        @keyframes ribbon-sway {
            0%, 100% { transform: rotate(0deg) translateY(0); }
            50% { transform: rotate(5deg) translateY(-8px); }
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
            box-shadow: 0 18px 55px rgba(220, 20, 60, 0.2);
            transform: translateY(-10px);
            border-color: #4A90E2;
        }

        /* Imagem com fundo branco */
        .receita-image-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #F0F0F0 0%, #FFFFFF 100%);
        }

        .receita-image-wrapper::before {
            content: '❄️';
            position: absolute;
            font-size: 5rem;
            opacity: 0.06;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            color: #DC143C;
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

        /* Badge Vermelho */
        .receita-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #DC143C 0%, #C41E3A 100%);
            color: #FFFFFF;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            z-index: 4;
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.35);
            border: 2px solid #FFFFFF;
            font-family: 'Fredoka', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .receita-content {
            padding: 25px;
            color: #3a3a3a;
        }
       
        .receita-content h3 {
            color: #DC143C;
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
       
        /* Metadata com Vermelho e Azul */
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
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.08) 0%, rgba(74, 144, 226, 0.08) 100%);
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 600;
            color: #DC143C;
            border-left: 3px solid #4A90E2;
        }

        .meta-item i {
            color: #4A90E2;
            font-size: 0.9rem;
        }
       
        /* Botão com Vermelho e Azul */
        .receita-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #DC143C 0%, #C41E3A 50%, #4A90E2 100%);
            color: #FFFFFF;
            border: 2px solid #DC143C;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.25);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .receita-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(220, 20, 60, 0.35);
            border-color: #4A90E2;
        }

        .receita-btn:active {
            transform: translateY(-1px);
        }
       
        /* Botão Voltar - Vermelho */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #DC143C 0%, #C41E3A 100%);
            color: #FFFFFF;
            padding: 12px 30px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 700;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(220, 20, 60, 0.3);
            border: 2px solid #4A90E2;
            font-size: 1rem;
            font-family: 'Fredoka', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
       
        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(220, 20, 60, 0.4);
            background: linear-gradient(135deg, #C41E3A 0%, #A01830 100%);
        }

        /* Footer - Branco com detalhes Vermelho */
        footer {
            text-align: center;
            padding: 35px 20px;
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.06) 0%, rgba(74, 144, 226, 0.06) 100%);
            color: #DC143C;
            border-top: 5px solid #DC143C;
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

            .christmas-star {
                font-size: 3rem;
                top: 20px;
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

<!-- Estrela de Natal com brilho -->
<div class="christmas-star">⭐</div>

<!-- Flocos de neve AZUIS e luzes pisca-pisca -->
<script>
    // Criar luzes pisca-pisca VERMELHAS e AZUIS
    function createLights() {
        const colors = ['red', 'blue', 'white'];
        const positions = [
            { top: '12%', left: '5%' },
            { top: '18%', right: '6%' },
            { top: '25%', left: '8%' },
            { top: '32%', right: '9%' },
            { bottom: '18%', left: '7%' },
            { bottom: '12%', right: '5%' },
            { bottom: '25%', left: '10%' },
            { bottom: '20%', right: '8%' }
        ];

        positions.forEach((pos, index) => {
            const light = document.createElement('div');
            light.className = `light ${colors[index % colors.length]}`;
            light.style.width = '16px';
            light.style.height = '16px';
            Object.assign(light.style, pos);
            light.style.animationDelay = (index * 0.22) + 's';
            document.body.appendChild(light);
        });
    }

    createLights();

    // Criar flocos de neve AZUIS
    function createSnowflake() {
        const snowflake = document.createElement('div');
        snowflake.className = 'snowflake';
       
        const emojis = ['❄️', '❅', '❆', '✿', '⛄'];
        snowflake.innerHTML = emojis[Math.floor(Math.random() * emojis.length)];
       
        const posX = Math.random() * window.innerWidth;
        snowflake.style.left = posX + 'px';
       
        const duration = Math.random() * 10 + 12;
        const swayAmount = (Math.random() - 0.5) * 200;
        snowflake.style.setProperty('--sway-amount', swayAmount + 'px');
        snowflake.style.animationDuration = duration + 's';
       
        document.body.appendChild(snowflake);
       
        setTimeout(() => snowflake.remove(), duration * 1000);
    }
   
    // Criar flocos a cada 350ms
    setInterval(createSnowflake, 350);
</script>

<div class="header">
    <div class="header-content">
        <h1>🎄 RECEITAS DE NATAL 🎄</h1>
        <p>✨ Deliciosas receitas para sua ceia natalina ✨</p>
    </div>
</div>

<div class="container">
    <a href="cliente-dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        <span>Voltar</span>
    </a>
   
    <div class="section-header">
        <h2 class="section-title">Receitas Natalinas Especiais</h2>
        <div class="decorative-divider"></div>
        <p class="section-subtitle">Explore nossas deliciosas receitas tradicionais de Natal 🎁</p>
    </div>
   
    <div class="receitas-grid">
        <div class="receita-card">
            <div class="receita-badge">🎄 Favorito</div>
            <div class="receita-image-wrapper">
                <img src="https://vocegastro.com.br/app/uploads/2021/12/salpicao-de-Natal.jpeg" alt=" Natal">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🎄</span> Salpicão</h3>
                <p>Uma das estrelas da ceia de Natal, o salpicão .</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 45min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Médio</div>
                </div>
                <button class="receita-btn">🎁 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">⭐ Top Pick</div>
            <div class="receita-image-wrapper">
                <img src="https://sabores-new.s3.amazonaws.com/public/2024/11/Lasanha_tradicional-1024x606.jpg" alt="Lasanha">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji"></span> 🎅 Lasanha</h3>
                <p> Tradicional de Natal com sabor inigualável.</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 60min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Médio</div>
                </div>
                <button class="receita-btn">🎁 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🔔 Clássico</div>
            <div class="receita-image-wrapper">
                <img src="https://grupobembarato.com.br/wp-content/uploads/2019/12/images.jpg" alt="Peru natalino">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🎅</span> Peru de Natal Recheado</h3>
                <p>Peru festivo com ingredientes especiais.</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 70min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Médio</div>
                </div>
                <button class="receita-btn">🎁 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🍮 Sobremesa</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSMJSQci-H6H3-biw42bIHZGBW7ZuhJRw_rng&s" alt="bolo Natalino">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🔴</span> Bolo de Frutas</h3>
                <p>Bolo na Travessa com Creme de Ninho e Frutas</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 60min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Fácil</div>
                </div>
                <button class="receita-btn">🎁 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🔥 Popular</div>
            <div class="receita-image-wrapper">
                <img src="https://teamodoce.com.br/wp-content/uploads/2025/12/Bombom-de-Chocotone-na-Travessa-Sobremesa-Chocolatuda-de-Natal.webp" alt="chocotone Natalino">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">⛄</span> Bombom Chocotone</h3>
                <p>Bombom de Chocotone na Travessa: Sobremesa Chocolatuda de Natal.</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 45min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Fácil</div>
                </div>
                <button class="receita-btn">🎁 Ver Receita</button>
            </div>
        </div>
       
        <div class="receita-card">
            <div class="receita-badge">🎁 Especial</div>
            <div class="receita-image-wrapper">
                <img src="https://www.receitasnestle.com.br/sites/default/files/srh_recipes/e5c263368897a6c69ef04684d00ac9a8.jpg" alt="Pudim">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🎀</span> Pudim de Ouro</h3>
                <p>Pudim de ouro natalino.</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 60min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Médio</div>
                </div>
                <button class="receita-btn">🎁 Ver Receita</button>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>🎄 Feliz Natal! Que suas receitas sejam deliciosas! 🎄</p>
</footer>

</body>
</html>