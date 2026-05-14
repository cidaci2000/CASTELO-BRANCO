

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVortex • Dashboard</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header class="header">
        <div class="header-container">
            <h1 class="logo">CINE<span>VORTEX</span></h1>
            
            <div class="header-buttons">
                <button class="btn-login" onclick="window.location.href='login.php'">Entrar</button>
                <button class="btn-signup" onclick="window.location.href='cadastro.php'">Cadastrar</button>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="dashboard">
            <!-- Lado Esquerdo - Avaliações -->
            <div class="dashboard-left">
                <div class="page-title">
                    <h2>Bem-vindo ao <span>CineVortex</span></h2>
                    <p>Tudo sobre cinema em um só lugar</p>
                </div>

                <div class="avaliacao">
                    <h3 class="section-title">⭐ Avalie um Filme</h3>
                    
                    <div class="avaliacao-container">
                        <div class="select-filme">
                            <label for="filme">Selecione o filme:</label>
                            <select id="filme" class="select-input">
                                <option value="">-- Escolha um filme --</option>
                            </select>
                        </div>

                        <div class="estrelas-container">
                            <label>Sua nota:</label>
                            <div class="estrelas">
                                <span class="estrela" onclick="avaliar(1)">☆</span>
                                <span class="estrela" onclick="avaliar(2)">☆</span>
                                <span class="estrela" onclick="avaliar(3)">☆</span>
                                <span class="estrela" onclick="avaliar(4)">☆</span>
                                <span class="estrela" onclick="avaliar(5)">☆</span>
                            </div>
                            <span class="nota-texto" id="notaTexto">Clique nas estrelas</span>
                        </div>

                        <div class="comentario">
                            <label for="comentario">Comentário (opcional):</label>
                            <textarea id="comentario" class="comentario-input" placeholder="O que você achou do filme?" rows="3"></textarea>
                        </div>

                        <button class="btn-avaliar" onclick="enviarAvaliacao()">
                            Enviar Avaliação
                        </button>
                    </div>
                </div>

                <div class="ultimas-avaliacoes">
                    <h3 class="section-title">📝 Últimas Avaliações</h3>
                    <div class="avaliacoes-lista" id="avaliacoesLista"></div>
                </div>
            </div>

            <!-- Lado Direito - Biblioteca de Filmes -->
            <div class="dashboard-right">
                <!-- Hero Section simplificada -->
                <div class="hero">
                    <h1 class="hero-title">Sua Biblioteca Personalizada</h1>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number" id="totalFilmes">0</span>
                            <span class="stat-label">Total</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number" id="totalAssistidos">0</span>
                            <span class="stat-label">Assistidos</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number" id="totalFavoritos">0</span>
                            <span class="stat-label">Favoritos</span>
                        </div>
                    </div>
                </div>

                <!-- Botão para adicionar filme -->
                <button class="btn-adicionar-filme" onclick="abrirModalAdicionar()">
                    ➕ Adicionar Novo Filme
                </button>

                <!-- Filtros Rápidos -->
                <div class="filtros-rapidos">
                    <button class="filtro-btn active" data-filtro="todos">🎬 Todos</button>
                    <button class="filtro-btn" data-filtro="quero-ver">⏰ Quero Ver</button>
                    <button class="filtro-btn" data-filtro="favoritos">⭐ Favoritos</button>
                    <button class="filtro-btn" data-filtro="assistidos">✅ Assistidos</button>
                </div>

                <!-- Grid de Filmes -->
                <div class="filmes-grid" id="todos-filmes-grid"></div>
            </div>
        </div>

        <!-- Modal para adicionar filme -->
        <div id="modalAdicionar" class="modal">
            <div class="modal-content modal-pequeno">
                <span class="modal-close" onclick="fecharModalAdicionar()">&times;</span>
                <h3 class="modal-titulo-form">🎬 Adicionar Novo Filme</h3>
                <div class="form-adicionar">
                    <input type="text" id="novoTitulo" class="form-input" placeholder="Título do filme *">
                    <input type="number" id="novoAno" class="form-input" placeholder="Ano de lançamento *">
                    <textarea id="novaDescricao" class="form-input" placeholder="Descrição do filme" rows="2"></textarea>
                    <input type="text" id="novoGenero" class="form-input" placeholder="Gênero (ex: Ação, Drama)">
                    <input type="text" id="novaImagem" class="form-input" placeholder="URL da imagem do poster">
                    <button class="btn-salvar" onclick="adicionarFilme()">Salvar Filme</button>
                </div>
            </div>
        </div>

        <!-- Modal de Detalhes do Filme -->
        <div id="modalDetalhes" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="fecharModalDetalhes()">&times;</span>
                <div id="modal-detalhes"></div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>CineVortex © 2026 - Tudo sobre cinema em um só lugar</p>
    </footer>

    <script src="js/home.js"></script>
</body>
</html>