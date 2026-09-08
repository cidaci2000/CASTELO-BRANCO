<?php
require_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎪 Receitas de Festa Junina - Candy Love's</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFF9E6 0%, #FFE6CC 50%, #FFD9B3 100%);
            color: #5a4a42;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Bandeiras de festa junina fixas */
        .bunting-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            pointer-events: none;
            z-index: 10;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            padding: 10px 20px;
            background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }

        .bunting {
            width: 30px;
            height: 40px;
            border-radius: 0 0 8px 8px;
            position: relative;
        }

        .bunting::before {
            content: '';
            position: absolute;
            width: 2px;
            height: 50px;
            background: #d4a574;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
        }

        .bunting-red { background: linear-gradient(135deg, #E8988F 0%, #D9847B 100%); }
        .bunting-green { background: linear-gradient(135deg, #B8DBC5 0%, #A8D4B9 100%); }
        .bunting-yellow { background: linear-gradient(135deg, #F5E6B3 0%, #E6D9A6 100%); }
        .bunting-blue { background: linear-gradient(135deg, #B8D4E8 0%, #A8C9DB 100%); }
        .bunting-purple { background: linear-gradient(135deg, #D9B8E8 0%, #CCA8DB 100%); }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(232, 152, 143, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(168, 212, 185, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(245, 230, 179, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }
        
        .header {
            background: linear-gradient(135deg, #E8988F 0%, #D9B8A6 100%);
            padding: 80px 40px 60px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border-bottom: 6px dashed #F5E6B3;
            position: relative;
            overflow: hidden;
            margin-top: 80px;
        }

        .header::before, .header::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            opacity: 0.08;
        }

        .header::before {
            left: -50px;
            top: -50px;
            background: #fff;
        }

        .header::after {
            right: -50px;
            bottom: -50px;
            background: #fff;
        }

        .header-content {
            position: relative;
            z-index: 2;
            animation: slideDown 0.8s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.15);
            margin-bottom: 15px;
        }
        
        .header p {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
            position: relative;
            z-index: 2;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title {
            color: #A86A5D;
            font-size: 2.8rem;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 15px;
        }

        .section-subtitle {
            color: #8b7a75;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .decorative-divider {
            width: 120px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #D9B8A6, transparent);
            margin: 20px auto;
        }
        
        .receitas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 35px;
        }
        
        .receita-card {
            background: linear-gradient(135deg, #FFFCF7 0%, #FFF5E6 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            border: 2px solid #E8D4C4;
            position: relative;
            cursor: pointer;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .receita-card:nth-child(1) { animation: slideUp 0.5s ease 0.1s both; }
        .receita-card:nth-child(2) { animation: slideUp 0.5s ease 0.2s both; }
        .receita-card:nth-child(3) { animation: slideUp 0.5s ease 0.3s both; }
        .receita-card:nth-child(4) { animation: slideUp 0.5s ease 0.4s both; }
        .receita-card:nth-child(5) { animation: slideUp 0.5s ease 0.5s both; }
        .receita-card:nth-child(6) { animation: slideUp 0.5s ease 0.6s both; }
        
        .receita-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
            border-color: #D9B8A6;
        }

        .receita-image-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #F5E6B3 0%, #E8D4C4 100%);
        }
        
        .receita-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .receita-card:hover img {
            transform: scale(1.05);
        }

        .receita-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(232, 152, 143, 0.9);
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .receita-content {
            padding: 25px;
        }
        
        .receita-content h3 {
            color: #A86A5D;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .receita-emoji {
            font-size: 1.6rem;
            display: inline-block;
        }
        
        .receita-content p {
            color: #7a6f6a;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .receita-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 18px;
            font-size: 0.9rem;
            color: #8b7a75;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, rgba(232, 152, 143, 0.1) 0%, rgba(216, 212, 196, 0.1) 100%);
            padding: 6px 12px;
            border-radius: 12px;
        }

        .meta-item i {
            color: #D9B8A6;
            font-weight: 600;
        }

        .receita-card:hover .meta-item {
            background: linear-gradient(135deg, rgba(232, 152, 143, 0.15) 0%, rgba(216, 212, 196, 0.15) 100%);
        }
        
        .receita-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #E8988F 0%, #D9B8A6 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(232, 152, 143, 0.2);
        }

        .receita-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(232, 152, 143, 0.3);
        }

        .receita-btn:active {
            transform: translateY(-1px);
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #F5E6B3 0%, #E8D4C4 100%);
            color: #A86A5D;
            padding: 12px 30px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 2px solid #E8D4C4;
            font-size: 1rem;
        }
        
        .back-btn:hover {
            transform: translateX(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            background: linear-gradient(135deg, #E8D4C4 0%, #F5E6B3 100%);
        }

        footer {
            text-align: center;
            padding: 30px;
            background: rgba(232, 152, 143, 0.05);
            color: #A86A5D;
            border-top: 4px dashed #F5E6B3;
            margin-top: 60px;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
            z-index: 2;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .header {
                padding: 60px 20px 40px;
                margin-top: 80px;
            }

            .header h1 {
                font-size: 2.2rem;
            }

            .header p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .receitas-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .bunting-container {
                height: 60px;
                padding: 5px 10px;
            }

            .bunting {
                width: 20px;
                height: 30px;
            }
        }
    </style>
</head>
<body>

<!-- Bandeiras de festa junina -->
<div class="bunting-container">
    <div class="bunting bunting-red"></div>
    <div class="bunting bunting-yellow"></div>
    <div class="bunting bunting-green"></div>
    <div class="bunting bunting-blue"></div>
    <div class="bunting bunting-red"></div>
    <div class="bunting bunting-yellow"></div>
    <div class="bunting bunting-green"></div>
    <div class="bunting bunting-blue"></div>
    <div class="bunting bunting-red"></div>
    <div class="bunting bunting-yellow"></div>
</div>

<div class="header">
    <div class="header-content">
        <h1>🎪 FESTA JUNINA 🎪</h1>
        <p>🌽 Receitas típicas para celebrar as festas juninas! 🌽</p>
    </div>
</div>

<div class="container">
    <a href="cliente-dashboard.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> 
        <span>Voltar</span>
    </a>
    
    <div class="section-header">
        <h2 class="section-title">Receitas Típicas de Festa Junina</h2>
        <div class="decorative-divider"></div>
        <p class="section-subtitle">Explore nossas deliciosas receitas tradicionais</p>
    </div>
    
    <div class="receitas-grid">
        <div class="receita-card">
            <div class="receita-badge">👑 Clássico</div>
            <div class="receita-image-wrapper">
                <img src="https://www.bakels.com.br/wp-content/uploads/sites/33/2020/05/IMG_0049-scaled.jpg" alt="Bolo de Milho">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🌽</span> Bolo de Milho</h3>
                <p>Clássico bolo de milho cremoso. Uma delícia tipicamente junina!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 45min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Fácil</div>
                </div>
                <button class="receita-btn">🎪 Ver Receita</button>
            </div>
        </div>
        
        <div class="receita-card">
            <div class="receita-badge">⭐ Favorito</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRWg4CRs0f9zuqU1ulgcLeUkK-vOdevqjnrOQ&s" alt="Arroz Doce">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🍚</span> Arroz Doce</h3>
                <p>Doce típico de festa junina. Tradicional e irresistível!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 30min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Fácil</div>
                </div>
                <button class="receita-btn">🎪 Ver Receita</button>
            </div>
        </div>
        
        <div class="receita-card">
            <div class="receita-badge">🌟 Top Pick</div>
            <div class="receita-image-wrapper">
                <img src="https://p2.trrsf.com/image/fget/cf/942/530/images.terra.com/2023/05/22/pamonha-r1ak8eh2lcfr.jpg" alt="Pamonha">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🌾</span> Pamonha</h3>
                <p>Clássica pamonha junina feita com milho fresco. Sensacional!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 50min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Médio</div>
                </div>
                <button class="receita-btn">🎪 Ver Receita</button>
            </div>
        </div>
        
        <div class="receita-card">
            <div class="receita-badge">🥖 Pão</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTX_AeP8ktxZkQatnW1-zV8IZeiZbg11hhxGQ&s" alt="Broa de Milho">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🍞</span> Broa de Milho</h3>
                <p>Pão tradicional de festa junina. Perfeito com manteiga!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 40min</div>
                    <div class="meta-item"><i class="fas fa-fire"></i> Médio</div>
                </div>
                <button class="receita-btn">🎪 Ver Receita</button>
            </div>
        </div>
        
        <div class="receita-card">
            <div class="receita-badge">🔥 Popular</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbQg6FroCieqPFXo5UWWRQMw5wmXy2kgulcw&s" alt="Milho Cozido">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🌽</span> Milho na Manteiga</h3>
                <p>Milho cozido quentinho com manteiga. Simples e delicioso!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 20min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Fácil</div>
                </div>
                <button class="receita-btn">🎪 Ver Receita</button>
            </div>
        </div>
        
        <div class="receita-card">
            <div class="receita-badge">🍲 Sopa</div>
            <div class="receita-image-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTizOrZ5LhPLZiyKpsYShI0PjejLpAuWaFS_Q&s" alt="Caldo de Milho">
            </div>
            <div class="receita-content">
                <h3><span class="receita-emoji">🍲</span> Caldo de Milho</h3>
                <p>Quentinha e reconfortante. Perfeita para noites de festa!</p>
                <div class="receita-meta">
                    <div class="meta-item"><i class="fas fa-clock"></i> 35min</div>
                    <div class="meta-item"><i class="fas fa-star"></i> Fácil</div>
                </div>
                <button class="receita-btn">🎪 Ver Receita</button>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>🎪 Aproveite a festa! Que suas receitas sejam deliciosas! 🎪</p>
</footer>

</body>
</html>