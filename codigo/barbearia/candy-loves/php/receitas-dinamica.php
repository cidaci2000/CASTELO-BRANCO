<?php
require_once 'config/db_connection.php';

// Obter categorias
$stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receitas - Candy's Love's</title>
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
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

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
            font-family: 'Playfair Display', serif;
        }

        .header nav {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .header nav a, .header nav button {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header nav button {
            background: linear-gradient(135deg, #fcd5ce 0%, #fcc0b0 100%);
            color: #b83b5e;
            padding: 12px 22px;
            border-radius: 25px;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.1);
        }

        .header nav button:hover {
            transform: translateY(-3px);
        }

        .hero {
            height: 500px;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('https://www.receiteria.com.br/wp-content/uploads/bolo-confeitado-de-morango-730x480.jpeg') center/cover;
            border-radius: 20px;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            text-align: center;
            color: #fff;
        }

        .hero h2 {
            font-size: 4rem;
            margin-bottom: 30px;
            font-family: 'Playfair Display', serif;
        }

        .search-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-bar {
            width: 100%;
            padding: 18px 25px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .search-bar:focus {
            outline: none;
            box-shadow: 0 12px 36px rgba(184, 59, 94, 0.2);
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 15px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 100;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            margin-top: 10px;
        }

        .search-results.show {
            display: block;
        }

        .search-result-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-result-item:hover {
            background: #f5f0ed;
        }

        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

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
            cursor: pointer;
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

        .card-meta {
            padding: 0 18px;
            display: flex;
            gap: 12px;
            font-size: 0.85rem;
            color: #8b7575;
            margin-bottom: 10px;
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
        }

        .card button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(184, 59, 94, 0.3);
        }

        .paginacao {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .pagination-btn {
            padding: 10px 15px;
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(184, 59, 94, 0.3);
        }

        .pagination-btn.active {
            background: #8b2d42;
        }

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
            font-family: 'Playfair Display', serif;
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
        }

        .categoria-card:hover {
            transform: translateX(8px) translateY(-4px);
            border-left-color: #b83b5e;
        }

        .categoria-card-text h4 {
            color: #b83b5e;
            font-size: 1.1rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        .destaques {
            margin-top: 60px;
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
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            padding: 20px;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            scroll-behavior: smooth;
        }

        .recipe-card {
            min-width: 260px;
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(90, 70, 70, 0.1);
            transition: all 0.4s;
            cursor: pointer;
            border: 2px solid transparent;
            flex-shrink: 0;
        }

        .recipe-card:hover {
            transform: translateY(-12px);
            border-color: #b83b5e;
        }

        .recipe-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .recipe-card h3 {
            font-size: 1.1rem;
            padding: 15px;
            text-align: center;
            color: #b83b5e;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #b83b5e;
        }

        footer {
            text-align: center;
            padding: 40px 30px;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fffaf7;
            border-radius: 20px 20px 0 0;
            margin-top: 80px;
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <header class="header">
        <h1>🍓 Candy's Love's</h1>
        <nav>
            <a href="cliente-dashboard.php">Início</a>
            <a href="categorias.html">Ver Todas</a>
            <button onclick="window.location.href='login.php'">Login</button>
            <button onclick="window.location.href='receitas-salvas.html'">Guardar Receita</button>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Receitas</h2>
            <div class="search-container">
                <input type="text" id="searchInput" class="search-bar" placeholder="Buscar receita...">
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>
    </section>

    <div class="main-content">
        <section class="receitas">
            <h2 class="section-title"><i class="fas fa-heart"></i> Receitas Populares</h2>
            <div class="cards" id="cardsContainer">
                <div class="loading">Carregando receitas...</div>
            </div>
            <div class="paginacao" id="paginacao"></div>
        </section>

        <aside class="sidebar-categorias">
            <h3><i class="fas fa-th"></i> Categorias</h3>
            <div class="categorias-sidebar" id="categoriasContainer">
                <?php foreach ($categorias as $cat): ?>
                    <div class="categoria-card" onclick="filtrarCategoria(<?php echo $cat['id']; ?>)">
                        <div class="categoria-header">
                            <div class="categoria-card-icon" style="font-size: 2rem;"><?php echo $cat['icone']; ?></div>
                            <div class="categoria-card-text">
                                <h4><?php echo htmlspecialchars($cat['nome']); ?></h4>
                                <p><?php echo htmlspecialchars($cat['descricao']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>

    <section class="destaques">
        <h2>⭐ Em Destaque</h2>
        <div class="carousel-container">
            <div class="carousel-track" id="carouselContainer">
                <div class="loading">Carregando destaques...</div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 Candy's Love's. Todos os direitos reservados. ❤️</p>
    </footer>
</div>

<script>
const API_BASE = 'api_receitas.php';

function carregarReceitas(pagina = 1) {
    const container = document.getElementById('cardsContainer');
    container.innerHTML = '<div class="loading">Carregando receitas...</div>';

    fetch(`${API_BASE}?action=listar&pagina=${pagina}&por_pagina=6`)
        .then(res => res.json())
        .then(data => {
            if (data.sucesso) {
                renderizarReceitas(data.receitas);
                renderizarPaginacao(data.paginacao);
            }
        })
        .catch(err => {
            container.innerHTML = '<p>Erro ao carregar receitas</p>';
            console.error(err);
        });
}

function renderizarReceitas(receitas) {
    const container = document.getElementById('cardsContainer');
    
    if (receitas.length === 0) {
        container.innerHTML = '<p style="grid-column: 1/-1; text-align: center;">Nenhuma receita encontrada</p>';
        return;
    }

    container.innerHTML = receitas.map(receita => `
        <div class="card">
            <img src="${receita.imagem_url || 'https://via.placeholder.com/280x200'}" alt="${receita.titulo}" onerror="this.src='https://via.placeholder.com/280x200'">
            <h3>${receita.titulo}</h3>
            <p>${receita.descricao}</p>
            <div class="card-meta">
                <span><i class="fas fa-clock"></i> ${receita.tempo_preparo || 0}min</span>
                <span><i class="fas fa-chart-pie"></i> ${receita.dificuldade}</span>
            </div>
            <button onclick="abrirDetalhes(${receita.id})"><i class="fas fa-eye"></i> Ver Receita</button>
        </div>
    `).join('');
}

function renderizarPaginacao(paginacao) {
    const container = document.getElementById('paginacao');
    let html = '';

    if (paginacao.pagina_atual > 1) {
        html += `<button class="pagination-btn" onclick="carregarReceitas(${paginacao.pagina_atual - 1})"><i class="fas fa-chevron-left"></i> Anterior</button>`;
    }

    for (let i = 1; i <= paginacao.total_paginas; i++) {
        html += `<button class="pagination-btn ${i === paginacao.pagina_atual ? 'active' : ''}" onclick="carregarReceitas(${i})">${i}</button>`;
    }

    if (paginacao.pagina_atual < paginacao.total_paginas) {
        html += `<button class="pagination-btn" onclick="carregarReceitas(${paginacao.pagina_atual + 1})">Próxima <i class="fas fa-chevron-right"></i></button>`;
    }

    container.innerHTML = html;
}

function carregarDestaques() {
    fetch(`${API_BASE}?action=destaques`)
        .then(res => res.json())
        .then(data => {
            if (data.sucesso && data.destaques.length > 0) {
                const container = document.getElementById('carouselContainer');
                container.innerHTML = data.destaques.map(receita => `
                    <div class="recipe-card" onclick="abrirDetalhes(${receita.id})">
                        <img src="${receita.imagem_url || 'https://via.placeholder.com/260x180'}" alt="${receita.titulo}" onerror="this.src='https://via.placeholder.com/260x180'">
                        <h3>${receita.titulo}</h3>
                    </div>
                `).join('');
            }
        });
}

const searchInput = document.getElementById('searchInput');
let searchTimeout;

searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    const termo = e.target.value;

    if (termo.length < 3) {
        document.getElementById('searchResults').classList.remove('show');
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`${API_BASE}?action=buscar&termo=${encodeURIComponent(termo)}`)
            .then(res => res.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchResults');
                
                if (data.receitas.length > 0) {
                    resultsDiv.innerHTML = data.receitas.map(receita => `
                        <div class="search-result-item" onclick="abrirDetalhes(${receita.id})">
                            <strong>${receita.titulo}</strong>
                            <br>
                            <small>${receita.categoria_nome || 'Receita'}</small>
                        </div>
                    `).join('');
                    resultsDiv.classList.add('show');
                }
            });
    }, 300);
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-container')) {
        document.getElementById('searchResults').classList.remove('show');
    }
});

function abrirDetalhes(receitaId) {
    window.location.href = `receita-detalhes.php?id=${receitaId}`;
}

function filtrarCategoria(categoriaId) {
    fetch(`${API_BASE}?action=categoria&categoria_id=${categoriaId}&pagina=1&por_pagina=6`)
        .then(res => res.json())
        .then(data => {
            if (data.sucesso) {
                renderizarReceitas(data.receitas);
                renderizarPaginacao(data.paginacao);
            }
        });
}

document.addEventListener('DOMContentLoaded', () => {
    carregarReceitas(1);
    carregarDestaques();
});
</script>

</body>
</html>
