<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Filmes em Destaque</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <h1 class="logo" onclick="window.location.href='home.php'" style="cursor: pointer;">CINE<span>VORTEX</span></h1>
            <nav class="nav-links">
                <a href="login.php" class="nav-link">Início</a>
                <a href="filmes.php" class="nav-link active">Filmes</a>
                <a href="series.php" class="nav-link">Séries</a>
                <a href="contato.php" class="nav-link">Contato</a>
                
            </nav>
        </div>
    </header>

    <!-- Banner Promocional BTS ARIRANG -->
    <section class="hero-banner">
        <div class="banner-overlay"></div>
        <div class="banner-content">
            <div class="banner-text">
                <span class="banner-tag">🎬 EVENTO ESPECIAL</span>
                <h2>BTS: ARIRANG</h2>
                <h3>World Tour 2025-2026</h3>
                <p>Uma experiência cinematográfica única! Assista aos melhores momentos da turnê que está fazendo história no cinema.</p>
                <div class="banner-buttons">
                    <a href="#" class="btn-primary">🎟️ Comprar Ingressos</a>
                    <a href="#" class="btn-secondary">▶ Assistir Trailer</a>
                </div>
            </div>
            <div class="banner-stats">
                <div class="stat">
                    <span class="stat-number">120+</span>
                    <span class="stat-label">Cidades</span>
                </div>
                <div class="stat">
                    <span class="stat-number">2M+</span>
                    <span class="stat-label">Espectadores</span>
                </div>
                <div class="stat">
                    <span class="stat-number">⭐ 4.9</span>
                    <span class="stat-label">Avaliação</span>
                </div>
            </div>
        </div>
    </section>

    <main class="main">
        <!-- Categorias -->
        <div class="categories">
            <div class="categories-container">
                <button class="category-btn active">Todos</button>
                <button class="category-btn">Ação</button>
                <button class="category-btn">Drama</button>
                <button class="category-btn">Comédia</button>
                <button class="category-btn">Show/Musical</button>
                <button class="category-btn">Animação</button>
            </div>
        </div>

        <!-- Filmes em Destaque -->
        <section class="movies-section">
            <div class="section-header">
                <h2 class="section-title">🎬 Em Cartaz</h2>
                <a href="#" class="view-all">Ver todos →</a>
            </div>
            
            <div class="movies-grid">
                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="https://image.tmdb.org/t/p/w500/8c4a8kE7P2GjQvMgKx5ZqYq5a5l.jpg" alt="Duna: Parte 2">
                        <div class="movie-overlay">
                            <button class="btn-watch">▶ Assistir</button>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3>Duna: Parte 2</h3>
                        <div class="movie-meta">
                            <span class="rating">⭐ 8.7</span>
                            <span class="year">2024</span>
                            <span class="duration">2h 46min</span>
                        </div>
                        <p class="movie-description">A jornada épica continua enquanto Paul Atreides se une a Chani e aos Fremen.</p>
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="https://image.tmdb.org/t/p/w500/7WsyChQLEftFiDOVTGkv3hFpyyt.jpg" alt="Oppenheimer">
                        <div class="movie-overlay">
                            <button class="btn-watch">▶ Assistir</button>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3>Oppenheimer</h3>
                        <div class="movie-meta">
                            <span class="rating">⭐ 8.9</span>
                            <span class="year">2023</span>
                            <span class="duration">3h 00min</span>
                        </div>
                        <p class="movie-description">A história do físico J. Robert Oppenheimer e a criação da bomba atômica.</p>
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="https://image.tmdb.org/t/p/w500/kDp1vUBnMpe8ak4rjgl3cLELrKc.jpg" alt="Wonka">
                        <div class="movie-overlay">
                            <button class="btn-watch">▶ Assistir</button>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3>Wonka</h3>
                        <div class="movie-meta">
                            <span class="rating">⭐ 7.8</span>
                            <span class="year">2023</span>
                            <span class="duration">1h 56min</span>
                        </div>
                        <p class="movie-description">A história de origem do jovem Willy Wonka e suas aventuras na fábrica de chocolate.</p>
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="https://image.tmdb.org/t/p/w500/tbVZ3Sq88dZaCANuUicpgnTY2cR.jpg" alt="Deadpool 3">
                        <div class="movie-overlay">
                            <button class="btn-watch">▶ Assistir</button>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3>Deadpool 3</h3>
                        <div class="movie-meta">
                            <span class="rating">⭐ 8.2</span>
                            <span class="year">2024</span>
                            <span class="duration">2h 10min</span>
                        </div>
                        <p class="movie-description">O mercenário tagarela se junta ao Wolverine em uma aventura insana.</p>
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="https://image.tmdb.org/t/p/w500/zAepoOqqq5sBd2VoL4LzFYW5l6n.jpg" alt="BTS: Yet to Come">
                        <div class="movie-overlay">
                            <button class="btn-watch">▶ Assistir</button>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3>BTS: Yet to Come</h3>
                        <div class="movie-meta">
                            <span class="rating">⭐ 9.5</span>
                            <span class="year">2024</span>
                            <span class="duration">1h 44min</span>
                        </div>
                        <p class="movie-description">O show cinematográfico do BTS, capturando momentos icônicos da turnê.</p>
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="https://image.tmdb.org/t/p/w500/7ZO9yoEU2fAHKhmJWfAc2QIPWJ.jpg" alt="Furiosa">
                        <div class="movie-overlay">
                            <button class="btn-watch">▶ Assistir</button>
                        </div>
                    </div>
                    <div class="movie-info">
                        <h3>Furiosa</h3>
                        <div class="movie-meta">
                            <span class="rating">⭐ 8.0</span>
                            <span class="year">2024</span>
                            <span class="duration">2h 28min</span>
                        </div>
                        <p class="movie-description">A origem da guerreira Furiosa antes dos eventos de Mad Max.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Próximas Estreias -->
        <section class="upcoming-section">
            <div class="section-header">
                <h2 class="section-title">🎬 Próximas Estreias</h2>
                <a href="#" class="view-all">Ver calendário →</a>
            </div>
            
            <div class="upcoming-grid">
                <div class="upcoming-card">
                    <div class="upcoming-date">
                        <span class="day">15</span>
                        <span class="month">MAI</span>
                    </div>
                    <div class="upcoming-info">
                        <h3>Kingdom of the Planet of the Apes</h3>
                        <p>Ação, Ficção Científica</p>
                    </div>
                </div>

                <div class="upcoming-card">
                    <div class="upcoming-date">
                        <span class="day">22</span>
                        <span class="month">MAI</span>
                    </div>
                    <div class="upcoming-info">
                        <h3>Garfield: Fora de Casa</h3>
                        <p>Animação, Comédia</p>
                    </div>
                </div>

                <div class="upcoming-card">
                    <div class="upcoming-date">
                        <span class="day">30</span>
                        <span class="month">MAI</span>
                    </div>
                    <div class="upcoming-info">
                        <h3>BTS: ARIRANG - The Final</h3>
                        <p>Show, Musical, Documentário</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="newsletter">
            <div class="newsletter-content">
                <h3>📧 Fique por dentro das novidades</h3>
                <p>Receba informações sobre lançamentos, promoções e eventos especiais</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Seu melhor e-mail" required>
                    <button type="submit">Inscrever-se</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>CineVortex</h4>
                <p>Sua plataforma definitiva para o melhor do cinema mundial.</p>
                <div class="social-links">
                    <a href="#">📱</a>
                    <a href="#">📘</a>
                    <a href="#">📷</a>
                    <a href="#">🐦</a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Links Rápidos</h4>
                <ul>
                    <li><a href="home.php">Início</a></li>
                    <li><a href="filmes.php">Filmes</a></li>
                    <li><a href="series.php">Séries</a></li>
                    <li><a href="contato.php">Contato</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Suporte</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Termos de Uso</a></li>
                    <li><a href="#">Privacidade</a></li>
                    <li><a href="#">Ajuda</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>CineVortex © 2026 - Tudo sobre cinema em um só lugar</p>
        </div>
    </footer>

    <script src="js/filmes.js"></script>
</body>
</html>