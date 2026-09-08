<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candy's Love's</title>
    <link rel="stylesheet" href="pinicial.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <style>

/* Reset básico */
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
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
    color: #fff;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 8px 24px rgba(184, 59, 94, 0.15);
}

.header h1 {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: -0.5px;
    font-family: 'Playfair Display', serif;
}

.header nav {
    display: flex;
    gap: 25px;
    align-items: center;
}

.header nav a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.header nav a:hover {
    transform: translateY(-2px);
    font-weight: 700;
}

.header nav button {
    background: linear-gradient(135deg, #fcd5ce 0%, #fcc0b0 100%);
    color: #b83b5e;
    border: none;
    padding: 12px 22px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(184, 59, 94, 0.1);
}

.header nav button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(184, 59, 94, 0.15);
}

/* Hero Section com Imagem de Fundo */
.hero {
    position: relative;
    height: 500px;
    background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), 
                url('https://www.receiteria.com.br/wp-content/uploads/bolo-confeitado-de-morango-730x480.jpeg') center/cover;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.hero-content {
    text-align: center;
    color: #fff;
    z-index: 2;
    animation: fadeInDown 0.8s ease;
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

.hero h2 {
    font-size: 4rem;
    margin-bottom: 30px;
    font-weight: 700;
    letter-spacing: -1px;
    text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    font-family: 'Playfair Display', serif;
}

/* Search Bar */
.search-container {
    position: relative;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.search-bar {
    width: 100%;
    padding: 18px 25px;
    padding-right: 55px;
    border: none;
    border-radius: 50px;
    font-size: 1rem;
    background: #fff;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.search-bar:focus {
    outline: none;
    box-shadow: 0 12px 36px rgba(184, 59, 94, 0.2);
    transform: translateY(-2px);
}

.search-bar::placeholder {
    color: #b0a0a0;
}

.search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
    color: #fff;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 16px rgba(184, 59, 94, 0.3);
}

/* Main Content Layout */
.main-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
    margin-bottom: 60px;
    animation: fadeInUp 0.8s ease 0.3s both;
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

/* Seção de Receitas Populares */
.receitas {
    width: 100%;
}

.section-title {
    color: #b83b5e;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 25px;
    padding-left: 10px;
    border-left: 5px solid #b83b5e;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Playfair Display', serif;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.card {
    background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(90, 70, 70, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    display: flex;
    flex-direction: column;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 16px 40px rgba(184, 59, 94, 0.15);
    border-color: #b83b5e;
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card:hover img {
    transform: scale(1.05);
}

.card h3 {
    padding: 18px 18px 8px;
    color: #b83b5e;
    font-size: 1.3rem;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
}

.card p {
    padding: 0 18px 15px;
    color: #6e5f5f;
    line-height: 1.6;
    font-size: 0.95rem;
    flex-grow: 1;
}

.card button {
    margin: 0 18px 20px;
    background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
    color: #fffaf7;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.card button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(184, 59, 94, 0.3);
}

/* Sidebar - Categorias */
.sidebar-categorias {
    position: sticky;
    top: 20px;
    height: fit-content;
}

.sidebar-categorias h3 {
    color: #b83b5e;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    padding-left: 10px;
    border-left: 5px solid #b83b5e;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Playfair Display', serif;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar-categorias h3:hover {
    transform: translateX(5px);
    color: #a02d50;
}

.categorias-sidebar {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.categoria-card {
    background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
    padding: 18px;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 6px 16px rgba(90, 70, 70, 0.08);
    border-left: 5px solid transparent;
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 140px;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.categoria-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    background: linear-gradient(135deg, rgba(184, 59, 94, 0.1) 0%, transparent 100%);
    transition: all 0.3s ease;
}

.categoria-card:hover {
    transform: translateX(8px) translateY(-4px);
    box-shadow: 0 12px 28px rgba(184, 59, 94, 0.15);
    border-left-color: #b83b5e;
    background: linear-gradient(135deg, #fff0e8 0%, #ffe8e0 100%);
}

.categoria-card:hover::before {
    width: 100px;
    height: 100px;
}

.categoria-header {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 2;
}

.categoria-card-icon {
    font-size: 2rem;
    min-width: 45px;
    text-align: center;
    transition: transform 0.3s ease;
}

.categoria-card:hover .categoria-card-icon {
    transform: scale(1.15) rotate(5deg);
}

.categoria-card-text h4 {
    color: #b83b5e;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    font-family: 'Playfair Display', serif;
}

.categoria-card-text p {
    color: #8b7575;
    font-size: 0.85rem;
    margin: 0;
}

.categoria-stats {
    display: flex;
    gap: 10px;
    font-size: 0.75rem;
    color: #9b8888;
    position: relative;
    z-index: 2;
    padding-top: 8px;
    border-top: 1px solid rgba(184, 59, 94, 0.1);
}

.categoria-stat {
    display: flex;
    align-items: center;
    gap: 4px;
}

.categoria-stat i {
    color: #b83b5e;
}

/* Carrossel de Destaques */
.destaques {
    margin-top: 60px;
    animation: fadeInUp 0.8s ease 0.4s both;
}

.destaques h2 {
    color: #b83b5e;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 25px;
    padding-left: 10px;
    border-left: 5px solid #b83b5e;
    font-family: 'Playfair Display', serif;
}

.carousel-container {
    position: relative;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px 15px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.carousel-track {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding-bottom: 10px;
    scroll-behavior: smooth;
}

.carousel-track::-webkit-scrollbar {
    height: 8px;
}

.carousel-track::-webkit-scrollbar-track {
    background: rgba(184, 59, 94, 0.1);
    border-radius: 10px;
}

.carousel-track::-webkit-scrollbar-thumb {
    background: #b83b5e;
    border-radius: 10px;
}

.recipe-card {
    min-width: 260px;
    max-width: 260px;
    background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(90, 70, 70, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: 2px solid transparent;
    position: relative;
    flex-shrink: 0;
}

.recipe-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 12px 32px rgba(184, 59, 94, 0.15);
    border-color: #b83b5e;
}

.recipe-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.recipe-card:hover img {
    transform: scale(1.05);
}

.recipe-card h3 {
    font-size: 1.1rem;
    padding: 15px;
    text-align: center;
    color: #b83b5e;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
}

.carousel-controls {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 10px;
    pointer-events: none;
    z-index: 10;
}

.carousel-btn {
    background: rgba(184, 59, 94, 0.8);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    pointer-events: all;
    z-index: 10;
}

.carousel-btn:hover {
    background: #b83b5e;
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(184, 59, 94, 0.3);
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
    font-size: 1rem;
}

/* Responsividade */
@media (max-width: 1024px) {
    .main-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .sidebar-categorias {
        position: static;
    }

    .hero h2 {
        font-size: 2.5rem;
    }

    .cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        padding: 15px 20px;
    }

    .header h1 {
        font-size: 1.6rem;
    }

    .header nav {
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .header nav button {
        padding: 10px 18px;
        font-size: 0.9rem;
    }

    .hero {
        height: 400px;
        margin-bottom: 40px;
    }

    .hero h2 {
        font-size: 2.5rem;
    }

    .search-bar {
        padding: 15px 20px;
        padding-right: 50px;
        font-size: 1rem;
    }

    .main-content {
        grid-template-columns: 1fr;
        gap: 25px;
    }

    .section-title,
    .sidebar-categorias h3 {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }

    .cards {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .card h3 {
        font-size: 1.2rem;
    }

    .categorias-sidebar {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .categoria-card {
        padding: 12px;
        min-height: auto;
    }

    .categoria-card-text h4 {
        font-size: 1rem;
    }

    .recipe-card {
        min-width: 220px;
        max-width: 220px;
    }

    .recipe-card img {
        height: 160px;
    }

    footer {
        margin-top: 50px;
        padding: 30px 20px;
    }
}

@media (max-width: 480px) {
    .header {
        padding: 12px 15px;
    }

    .header h1 {
        font-size: 1.3rem;
    }

    .header nav {
        gap: 8px;
    }

    .header nav a {
        display: none;
    }

    .header nav button {
        flex: 1;
        padding: 8px 10px;
        font-size: 0.8rem;
    }

    .hero {
        height: 300px;
        margin-bottom: 30px;
        border-radius: 10px;
    }

    .hero h2 {
        font-size: 2rem;
    }

    .search-bar {
        padding: 12px 15px;
        padding-right: 45px;
        font-size: 0.95rem;
    }

    .main-content {
        gap: 20px;
        margin-bottom: 40px;
    }

    .section-title,
    .sidebar-categorias h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .cards {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .card h3 {
        font-size: 1.1rem;
    }

    .categorias-sidebar {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .categoria-card {
        padding: 12px;
        min-height: auto;
    }

    .recipe-card {
        min-width: 180px;
        max-width: 180px;
    }

    .recipe-card img {
        height: 140px;
    }

    footer {
        margin-top: 40px;
        padding: 20px 15px;
        border-radius: 15px 15px 0 0;
    }
}

</style>

<body>

<div class="container">
    <header class="header">
        <h1>🍓 Candy's Love's</h1>
        <nav>
            <a href="cliente-dashboard.php">Início</a>
            <a href="categorias.html">Ver Todas</a>
            <button id="loginBtn" onclick="window.location.href='login.php'">Login</button>
            <button id="cartBtn" onclick="window.location.href='receitas-salvas.html'">Guardar Receita</button>
        </nav>
    </header>

    <!-- Hero Section com Busca -->
    <section class="hero">
        <div class="hero-content">
            <h2>Receitas</h2>
            <div class="search-container">
                <input type="text" class="search-bar" placeholder="Buscar receita...">
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content Layout -->
    <div class="main-content">
        <!-- Receitas Populares (esquerda) -->
        <section class="receitas">
            <h2 class="section-title">
                <i class="fas fa-heart"></i> Receitas Populares
            </h2>
            <div class="cards">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587" alt="Bolo">
                    <h3>Bolo de Chocolate</h3>
                    <p>Bolo fofinho da vovó com cobertura cremosa, uma receita que une gerações.</p>
                    <button onclick="alert('Ver receita: Bolo de Chocolate')">Ver Receita</button>
                </div>
                <div class="card">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnLSfrVpl7G8H6uNkRv34qqcZbDP_tzLysUg&s" alt="Pizza">
                    <h3>Pizza Margherita</h3>
                    <p>Pizza caseira tradicional italiana, perfeita para momentos em família.</p>
                    <button onclick="alert('Ver receita: Pizza Margherita')">Ver Receita</button>
                </div>
                <div class="card">
                    <img src="https://revistaoeste.com/oestegeral/wp-content/uploads/2026/01/panqueca.jpg" alt="Panqueca">
                    <h3>Panqueca da Vovó</h3>
                    <p>Panquecas macias para o café da manhã, a receita típica dos filmes.</p>
                    <button onclick="alert('Ver receita: Panqueca da Vovó')">Ver Receita</button>
                </div>
                <div class="card">
                    <img src="https://docesdajessica.com.br/wp-content/uploads/2023/07/Imagem-destacada-Blog-2-750x394.png.webp" alt="Pudim">
                    <h3>Pudim de Leite</h3>
                    <p>Uma verdadeira delícia que faz parte da memória afetiva: o clássico Pudim de Leite</p>
                    <button onclick="alert('Ver receita: Pudim de Leite')">Ver Receita</button>
                </div>
                <div class="card">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcReT8cAQ_4utcpOlfdVhVBC2I55FYj8tWRRhg&s" alt="bombom morango">
                    <h3>Bombom de Travessa</h3>
                    <p>Brigadeiro branco cremoso, ganache, morangos frescos e suspiros.</p>
                    <button onclick="alert('Ver receita: Bombom de Travessa')">Ver Receita</button>
                </div>
                <div class="card">
                    <img src="https://docesdajessica.com.br/wp-content/uploads/2020/06/Imagem-destacada-blog-16-300x158.png.webp" alt="arroz doce">
                    <h3>Arroz Doce</h3>
                    <p>Doce típico de festa junina, perfeito para eventos e comemorações especiais.</p>
                    <button onclick="alert('Ver receita: Arroz Doce')">Ver Receita</button>
                </div>
            </div>
        </section>

        <!-- Sidebar - Categorias (direita) -->
        <aside class="sidebar-categorias">
            <h3 onclick="window.location.href='categorias.html'">
                <i class="fas fa-th"></i> Categorias
            </h3>
            <div class="categorias-sidebar">
                <div class="categoria-card">
                    <div class="categoria-header">
                        <div class="categoria-card-icon">🍽️</div>
                        <div class="categoria-card-text">
                            <h4>Salgados</h4>
                            <p>Petiscos e acompanhamentos</p>
                        </div>
                    </div>
                    <div class="categoria-stats">
                        <div class="categoria-stat">
                            <i class="fas fa-utensils"></i>
                            <span>24 receitas</span>
                        </div>
                        <div class="categoria-stat">
                            <i class="fas fa-star"></i>
                            <span>4.8★</span>
                        </div>
                    </div>
                </div>

                <div class="categoria-card">
                    <div class="categoria-header">
                        <div class="categoria-card-icon">🍰</div>
                        <div class="categoria-card-text">
                            <h4>Doces</h4>
                            <p>Sobremesas deliciosas</p>
                        </div>
                    </div>
                    <div class="categoria-stats">
                        <div class="categoria-stat">
                            <i class="fas fa-utensils"></i>
                            <span>32 receitas</span>
                        </div>
                        <div class="categoria-stat">
                            <i class="fas fa-star"></i>
                            <span>4.9★</span>
                        </div>
                    </div>
                </div>

                <div class="categoria-card">
                    <div class="categoria-header">
                        <div class="categoria-card-icon">🥧</div>
                        <div class="categoria-card-text">
                            <h4>Tortas</h4>
                            <p>Salgadas e doces</p>
                        </div>
                    </div>
                    <div class="categoria-stats">
                        <div class="categoria-stat">
                            <i class="fas fa-utensils"></i>
                            <span>18 receitas</span>
                        </div>
                        <div class="categoria-stat">
                            <i class="fas fa-star"></i>
                            <span>4.7★</span>
                        </div>
                    </div>
                </div>

                <div class="categoria-card">
                    <div class="categoria-header">
                        <div class="categoria-card-icon">☕</div>
                        <div class="categoria-card-text">
                            <h4>Café da Manhã</h4>
                            <p>Comece o dia bem</p>
                        </div>
                    </div>
                    <div class="categoria-stats">
                        <div class="categoria-stat">
                            <i class="fas fa-utensils"></i>
                            <span>20 receitas</span>
                        </div>
                        <div class="categoria-stat">
                            <i class="fas fa-star"></i>
                            <span>4.8★</span>
                        </div>
                    </div>
                </div>

                <div class="categoria-card">
                    <div class="categoria-header">
                        <div class="categoria-card-icon">🍹</div>
                        <div class="categoria-card-text">
                            <h4>Bebidas</h4>
                            <p>Drinks e sucos</p>
                        </div>
                    </div>
                    <div class="categoria-stats">
                        <div class="categoria-stat">
                            <i class="fas fa-utensils"></i>
                            <span>16 receitas</span>
                        </div>
                        <div class="categoria-stat">
                            <i class="fas fa-star"></i>
                            <span>4.6★</span>
                        </div>
                    </div>
                </div>

                <div class="categoria-card">
                    <div class="categoria-header">
                        <div class="categoria-card-icon">🏋🏽‍♀️</div>
                        <div class="categoria-card-text">
                            <h4>Fitness</h4>
                            <p>Saudável e leve</p>
                        </div>
                    </div>
                    <div class="categoria-stats">
                        <div class="categoria-stat">
                            <i class="fas fa-utensils"></i>
                            <span>28 receitas</span>
                        </div>
                        <div class="categoria-stat">
                            <i class="fas fa-star"></i>
                            <span>4.9★</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Carrossel de Destaques -->
    <section class="destaques">
        <h2>⭐ Em Destaque</h2>
        <div class="carousel-container">
            <div class="carousel-track" id="mainCarousel">
                <div class="recipe-card" onclick="alert('Bolo de Cenoura')">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSDgdwkjxrDOmMIg9UJmk0_b8YepaWsPI6TA&s" alt="Bolo de Cenoura">
                    <h3>Bolo de Cenoura</h3>
                </div>
                <div class="recipe-card" onclick="alert('Lasanha da Vovó')">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRVKK-9OZDsONLj4NikWXeQsKGji6zQu2PGTA&s" alt="Lasanha Da Vovó">
                    <h3>Lasanha da Vovó</h3>
                </div>
                <div class="recipe-card" onclick="alert('Torta de Morango')">
                    <img src="https://anamariabrogui.com.br/assets/uploads/receitas/fotos/usuario-3405-d7c50f8f1e1d16ef3a40d31fd288775d.jpg" alt="Torta de Morango">
                    <h3>Torta de Morango</h3>
                </div>
                <div class="recipe-card" onclick="alert('Torta Salgada')">
                    <img src="https://i.pinimg.com/736x/22/9c/87/229c87c3daa5787325c8533da0e5f54e.jpg" alt="Torta Salgada">
                    <h3>Torta Salgada</h3>
                </div>
                <div class="recipe-card" onclick="alert('Suspiro Caseiro')">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1w2k5YojbhBEjEqOzqnvP_TYIP67CnJMhZA&s" alt="Suspiro Caseiro">
                    <h3>Suspiro Caseiro</h3>
                </div>
                <div class="recipe-card" onclick="alert('Nozinho De Coco')">
                    <img src="https://i.ytimg.com/vi/x-HIyExqe4g/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLCu1Usj3TGIPApaSdCRUCIg8K8ikA" alt="nó de sogra">
                    <h3>Nozinho De Coco</h3>
                </div>
                <div class="recipe-card" onclick="alert('Gelatina Mosaico')">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSPdMwP6sGEhJWnONiRjPJqjPxpDJ-VXL5d0g&s" alt="gelatina mosaico">
                    <h3>Gelatina Mosaico</h3>
                </div>
                <div class="recipe-card" onclick="alert('Brigadeiro De Colher')">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkzPSc2UGhHy2c-mOEW2K_X3tikNZuj4dOEA&s" alt="brigadeiro de colher">
                    <h3>Brigadeiro De Colher</h3>
                </div>
            </div>
            <div class="carousel-controls">
                <button class="carousel-btn" onclick="scrollCarousel('mainCarousel', -300)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn" onclick="scrollCarousel('mainCarousel', 300)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <footer>
        <p>© 2026 Candy's Love's — Receitas da Vovó</p>
    </footer>
</div>

<script>
    function scrollCarousel(carouselId, distance) {
        const carousel = document.getElementById(carouselId);
        carousel.scrollBy({
            left: distance,
            behavior: 'smooth'
        });
    }
</script>

</body>

</html>