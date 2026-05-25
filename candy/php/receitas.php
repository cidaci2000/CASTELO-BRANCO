<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candy's Love's</title>
    <link rel="stylesheet" href="pinicial.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Carrossel -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>

/* Reset básico */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f5f2; /* bege pastel base */
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background-color: #d8a7b1; /* rosa vintage base */
    color: #3b2c2c; /* marrom escuro para contraste */
    border-radius: 10px;
    margin-bottom: 30px;
}

.header h1 {
    font-size: 1.5rem;
}

.header nav {
    display: flex;
    gap: 20px;
    align-items: center;
}

.header nav a {
    color: #3b2c2c;
    text-decoration: none;
    font-weight: bold;
}

.header nav button {
    background-color: #fcd5ce; /* rosa pastel claro */
    color: #7c2d2d; /* vermelho queimado para destaque */
    border: none;
    padding: 8px 15px;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
}

/* Hero Section */
.hero {
    text-align: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, #f9d5e5 0%, #f3e1dd 100%); /* pastel suave */
    border-radius: 20px;
    margin-bottom: 40px;
    color: #4a3f3f;
}

.hero h2 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: #b83b5e; /* destaque forte no título */
}

/* Carrossel */
.carousel {
    margin-bottom: 40px;
}

.carousel h2 {
    margin-bottom: 20px;
    color: #b83b5e; /* mesmo rosa forte para destaque */
}

.carousel-track {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding-bottom: 10px;
    scroll-behavior: smooth;
}

.recipe-card {
    min-width: 250px;
    max-width: 250px;
    background: #fffaf7; /* branco quente */
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(90, 70, 70, 0.1);
    transition: transform 0.3s ease;
    cursor: pointer;
}

.recipe-card:hover {
    transform: translateY(-5px);
}

.recipe-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.recipe-card h3 {
    font-size: 18px;
    padding: 10px;
    text-align: center;
    color: #b83b5e; /* rosa mais forte nos títulos dos cards */
}

/* Seção Receitas */
.receitas {
    margin-bottom: 40px;
}

.receitas h2 {
    margin-bottom: 20px;
    color: #b83b5e;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.card {
    background: #fffaf7;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(90, 70, 70, 0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card h3 {
    padding: 15px 15px 5px;
    color: #b83b5e;
}

.card p {
    padding: 0 15px 15px;
    color: #6e5f5f; /* marrom acinzentado */
    line-height: 1.4;
}

.card button {
    margin: 0 15px 20px;
    background-color: #b83b5e; /* cor de destaque forte nos botões */
    color: #fffaf7;
    border: none;
    padding: 8px 15px;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
}

/* Categorias */
.categorias {
    margin-bottom: 40px;
}

.categorias h2 {
    margin-bottom: 20px;
    color: #b83b5e;
}

.categorias-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.categoria {
    height: 200px;
    background-size: cover;
    background-position: center;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.categoria:hover {
    transform: scale(1.02);
}

.categoria::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(60, 50, 50, 0.25);
    border-radius: 15px;
}

.categoria h3 {
    position: relative;
    color: #fffaf7;
    font-size: 1.5rem;
    z-index: 1;
}

/* Footer */
footer {
    text-align: center;
    padding: 30px;
    background-color: #d8a7b1; /* base vintage */
    color: #fffaf7;
    border-radius: 10px;
}

/* Responsividade */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .header nav {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .hero h2 {
        font-size: 1.8rem;
    }
    
    .cards {
        grid-template-columns: 1fr;
    }
}
/* Esconde a barra de rolagem no Chrome, Safari e Opera */
.carousel-track::-webkit-scrollbar {
    display: none;
}

/* Esconde a barra de rolagem no Firefox e IE/Edge */
.carousel-track {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
    overflow-x: scroll; /* Garante que o scroll continue funcionando */
}


</style>

<body>

<div class="container">
    <header class="header">
        <h1>🍓 Candy's Love's</h1>
       <nav>
    <a href="cliente-dashboard.php">Início</a>
    <a href="categorias.html">Categorias</a>
    <button id="loginBtn" onclick="window.location.href='login.php'">Login</button>
    <button id="cartBtn" onclick="window.location.href='receitas-salvas.html'">Guardar Receita</button>
    <a href="livros.html" id="livrosBtn">Nossos Livros</a>
</nav>

    </header>

    <section class="hero">
        <h2>Receitas da Vovó 💗</h2>
        <p>Doces e salgados feitos com amor</p>
    </section>

    <!-- Carrossel de Destaques -->
    <section class="carousel">
        <h2>Destaques</h2>
        <div class="carousel-track">
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
    </section>

    <section class="receitas">
        <h2>Receitas Populares</h2>
        <div class="cards">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587" alt="Bolo">
                <h3>Bolo de Chocolate</h3>
                <p>Bolo fofinho da vovó com cobertura cremosa, uma receita que une gerações e nos traz o gostinho da infância de volta.</p>
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
                <p>Panquecas macias para o café da manhã, a receita típica dos filmes, que todas as crianças amam.</p>
                <button onclick="alert('Ver receita: Panqueca da Vovó')">Ver Receita</button>
            </div>
            <div class="card">
                <img src="https://docesdajessica.com.br/wp-content/uploads/2023/07/Imagem-destacada-Blog-2-750x394.png.webp" alt="Pudim">
                <h3>Pudim de Leite</h3>
                <p>Uma verdadeira delícia que faz parte da memória afetiva de muitas pessoas: o clássico Pudim de Leite</p>
                <button onclick="alert('Ver receita: Pudim de Leite')">Ver Receita</button>
            </div>
            <div class="card">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcReT8cAQ_4utcpOlfdVhVBC2I55FYj8tWRRhg&s" alt="bombom morango">
                <h3>Bombom de Travessa</h3>
                <p>Tudo que você vai precisar é de um brigadeiro branco cremoso, uma ganache, morangos frescos e suspiros.</p>
                <button onclick="alert('Ver receita: Bombom de Travessa')">Ver Receita</button>
            </div>
            <div class="card">
                <img src="https://docesdajessica.com.br/wp-content/uploads/2020/06/Imagem-destacada-blog-16-300x158.png.webp" alt="arroz doce">
                <h3>Arroz Doce</h3>
                <p>Aproveitando esse tempo mais frio e esse mês que nos faz pensar em muitas comidas específicas, resolvi trazer para o blog alguns doces típicos de festa junina.</p>
                <button onclick="alert('Ver receita: Arroz Doce')">Ver Receita</button>
            </div>
        </div>
    </section>

    <section class="categorias">
        <h2>Categorias</h2>
        <div class="categorias-grid">
            <div class="categoria" style="background-image: url('https://img.freepik.com/fotos-premium/petiscos-brasileiros-fritos-em-fundo-de-madeira_70216-7339.jpg?semt=ais_hybrid&w=740&q=80');" onclick="alert('Categoria: Salgados')">
                <h3>Salgados</h3>
            </div>
            <div class="categoria" style="background-image: url('https://i.pinimg.com/236x/aa/90/1c/aa901c2f21c61cc3eafbabbf38d0da88.jpg');" onclick="alert('Categoria: Doces')">
                <h3>Doces</h3>
            </div>
            <div class="categoria" style="background-image: url('https://thumbs.dreamstime.com/b/uma-mesa-festiva-exibe-variedade-de-tortas-e-velas-criando-atmosfera-quente-feriado-401830992.jpg');" onclick="alert('Categoria: Tortas')">
                <h3>Tortas</h3>
            </div>
            <div class="categoria" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRcsNyhBPV1lnRMb6xn8TYrhztlpM0NaJqOug&s');" onclick="alert('Categoria: Café da manhã')">
                <h3>Café da manhã</h3>
            </div>
        </div>
    </section>

    <footer>
        <p>© 2026 Candy's Love's — Receitas da Vovó</p>
    </footer>
</div>

<script>
    const track = document.querySelector('.carousel-track');

    if (track) {
        let scrollSpeed = 1; // Velocidade (aumente se estiver devagar)
        let animationId;
        let isPaused = false;

        // Função que faz o movimento infinito real
        function step() {
            if (!isPaused) {
                track.scrollLeft += scrollSpeed;

                // Se chegar no fim, volta pro zero (Loop)
                if (track.scrollLeft >= (track.scrollWidth - track.offsetWidth)) {
                    track.scrollLeft = 0;
                }
            }
            animationId = requestAnimationFrame(step);
        }

        // Inicia o giro
        animationId = requestAnimationFrame(step);

        // Para o giro quando o mouse passa em cima ou clica
        track.addEventListener('mouseenter', () => isPaused = true);
        track.addEventListener('mouseleave', () => isPaused = false);
        track.addEventListener('mousedown', () => isPaused = true);
        track.addEventListener('mouseup', () => isPaused = false);

        // Lógica de arrastar (Mouse)
        let isDown = false;
        let startX;
        let scrollLeft;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });

        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 2;
            track.scrollLeft = scrollLeft - walk;
        });

        window.addEventListener('mouseup', () => isDown = false);
    }
</script>


</body>

</html>