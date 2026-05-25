<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Candy's Love's</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100%;
            background-color: #d8a7b1; /* rosa vintage base */
            color: #3b2c2c; /* marrom escuro */
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(60, 50, 50, 0.2);
        }

        .sidebar-header h2 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #3b2c2c;
        }

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
            color: #3b2c2c;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #3b2c2c;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.2);
            border-left-color: #b83b5e;
        }

        .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-left-color: #b83b5e;
            color: #b83b5e;
            font-weight: bold;
        }

        .menu-item i {
            width: 24px;
            font-size: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* Top Bar */
        .top-bar {
            background: #fffaf7; /* branco quente */
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(90, 70, 70, 0.1);
        }

        .page-title {
            font-size: 24px;
            color: #b83b5e; /* rosa forte destaque */
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #6e5f5f;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: #b83b5e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fffaf7;
            font-weight: bold;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fffaf7;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(90, 70, 70, 0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(90, 70, 70, 0.15);
        }

        .stat-info h3 {
            font-size: 14px;
            color: #6e5f5f;
            margin-bottom: 5px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #b83b5e;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: #d8a7b1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #b83b5e;
            font-size: 24px;
        }

        /* Content Sections */
        .content-section {
            display: none;
            animation: fadeIn 0.5s;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Table */
        .table-container {
            background: #fffaf7;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(90, 70, 70, 0.1);
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h3 {
            color: #b83b5e;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            padding: 10px 15px;
            border: 1px solid #d8a7b1;
            border-radius: 20px;
            width: 250px;
            background: #f9f5f2;
        }

        .search-box input:focus {
            outline: none;
            border-color: #b83b5e;
        }

        .btn-add {
            background-color: #b83b5e;
            color: #fffaf7;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-weight: bold;
        }

        .btn-add:hover {
            background-color: #9e2d4e;
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0e6e2;
        }

        th {
            background: #d8a7b1;
            font-weight: 600;
            color: #3b2c2c;
        }

        tr:hover {
            background: #f9f5f2;
        }

        .book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: bold;
        }

        .btn-edit {
            background-color: #d8a7b1;
            color: #3b2c2c;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-edit:hover, .btn-delete:hover {
            transform: scale(1.05);
        }

        .status {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status.active {
            background-color: #d4edda;
            color: #155724;
        }

        .status.inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(60, 50, 50, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: #fffaf7;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 90%;
            overflow-y: auto;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #f0e6e2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #d8a7b1;
            border-radius: 15px 15px 0 0;
        }

        .modal-header h3 {
            font-size: 20px;
            color: #3b2c2c;
        }

        .close-modal {
            font-size: 28px;
            cursor: pointer;
            color: #3b2c2c;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #b83b5e;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d8a7b1;
            border-radius: 20px;
            font-size: 14px;
            background: #f9f5f2;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #b83b5e;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #f0e6e2;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-save, .btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-save {
            background-color: #b83b5e;
            color: #fffaf7;
        }

        .btn-save:hover {
            background-color: #9e2d4e;
        }

        .btn-cancel {
            background-color: #d8a7b1;
            color: #3b2c2c;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .page-btn {
            padding: 8px 12px;
            border: 1px solid #d8a7b1;
            background: #fffaf7;
            cursor: pointer;
            border-radius: 20px;
            transition: all 0.2s;
            color: #6e5f5f;
        }

        .page-btn.active {
            background-color: #b83b5e;
            color: #fffaf7;
            border-color: transparent;
        }

        .page-btn:hover {
            background-color: #d8a7b1;
        }

        /* Alert Messages */
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            z-index: 3000;
            animation: slideInRight 0.3s;
            display: none;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
            display: block;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
            display: block;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 1001;
                background-color: #d8a7b1;
                color: #b83b5e;
                padding: 10px;
                border-radius: 10px;
                cursor: pointer;
            }
        }

        /* Menu Toggle Button */
        .menu-toggle {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Menu Toggle para Mobile -->
    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>🍓 Candy's Love's</h2>
            <p>Painel Admin</p>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active" data-section="dashboard">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item" data-section="products">
                <i class="fas fa-book"></i>
                <span>Livros</span>
            </div>
            <div class="menu-item" data-section="recipes">
                <i class="fas fa-utensils"></i>
                <span>Receitas</span>
            </div>
            <div class="menu-item" data-section="orders">
                <i class="fas fa-shopping-cart"></i>
                <span>Pedidos</span>
            </div>
            <div class="menu-item" data-section="users">
                <i class="fas fa-users"></i>
                <span>Usuários</span>
            </div>
            <div class="menu-item" data-section="settings">
                <i class="fas fa-cog"></i>
                <span>Configurações</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h1 class="page-title" id="pageTitle">Dashboard</h1>
            <div class="user-info">
                <span>Bem-vinda, Administradora</span>
                <div class="user-avatar">A</div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div id="dashboard" class="content-section active">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total de Livros</h3>
                        <div class="stat-number" id="totalBooks">0</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Total de Receitas</h3>
                        <div class="stat-number" id="totalRecipes">0</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Pedidos Hoje</h3>
                        <div class="stat-number" id="todayOrders">0</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Usuários Ativos</h3>
                        <div class="stat-number" id="activeUsers">0</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <h3 style="color: #b83b5e; margin-bottom: 20px;">📖 Últimos Livros Adicionados</h3>
                <div id="recentBooks"></div>
            </div>
        </div>

        <!-- Products Section (Livros) -->
        <div id="products" class="content-section">
            <div class="table-container">
                <div class="table-header">
                    <h3>📚 Gerenciar Livros</h3>
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="🔍 Buscar livro...">
                        <button class="btn-add" onclick="openModal('book')">
                            <i class="fas fa-plus"></i> Novo Livro
                        </button>
                    </div>
                </div>
                <div id="booksTable"></div>
                <div id="pagination" class="pagination"></div>
            </div>
        </div>

        <!-- Recipes Section -->
        <div id="recipes" class="content-section">
            <div class="table-container">
                <div class="table-header">
                    <h3>🍰 Gerenciar Receitas</h3>
                    <div class="search-box">
                        <input type="text" id="searchRecipeInput" placeholder="🔍 Buscar receita...">
                        <button class="btn-add" onclick="openModal('recipe')">
                            <i class="fas fa-plus"></i> Nova Receita
                        </button>
                    </div>
                </div>
                <div id="recipesTable"></div>
                <div id="recipePagination" class="pagination"></div>
            </div>
        </div>

        <!-- Orders Section -->
        <div id="orders" class="content-section">
            <div class="table-container">
                <h3>🛒 Últimos Pedidos</h3>
                <div id="ordersTable"></div>
            </div>
        </div>

        <!-- Users Section -->
        <div id="users" class="content-section">
            <div class="table-container">
                <h3>👥 Usuários do Sistema</h3>
                <div id="usersTable"></div>
            </div>
        </div>

        <!-- Settings Section -->
        <div id="settings" class="content-section">
            <div class="table-container">
                <h3>⚙️ Configurações do Sistema</h3>
                <div style="padding: 20px;">
                    <h4 style="color: #b83b5e;">Configurações Gerais</h4>
                    <form id="settingsForm">
                        <div class="form-group">
                            <label>Nome da Loja</label>
                            <input type="text" id="storeName" value="Candy's Love's">
                        </div>
                        <div class="form-group">
                            <label>Email de Contato</label>
                            <input type="email" id="contactEmail" value="contato@candyloves.com">
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" id="phone" value="(11) 9999-9999">
                        </div>
                        <button class="btn-save" onclick="saveSettings()">Salvar Configurações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Adicionar Livro</h3>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    <input type="hidden" id="itemId">
                    <input type="hidden" id="itemType" value="book">
                    <div class="form-group">
                        <label>Título *</label>
                        <input type="text" id="titulo" required>
                    </div>
                    <div class="form-group">
                        <label>Autor *</label>
                        <input type="text" id="autor" required>
                    </div>
                    <div class="form-group">
                        <label>Editora</label>
                        <input type="text" id="editora">
                    </div>
                    <div class="form-group">
                        <label>Descrição *</label>
                        <textarea id="descricao" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Preço *</label>
                        <input type="number" id="preco" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria</label>
                        <select id="categoria">
                            <option value="">Selecione</option>
                            <option value="Gastronomia">Gastronomia</option>
                            <option value="Doces">Doces</option>
                            <option value="Salgados">Salgados</option>
                            <option value="Tortas">Tortas</option>
                            <option value="Café da manhã">Café da manhã</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>URL da Imagem *</label>
                        <input type="url" id="imagem_url" required>
                    </div>
                    <div class="form-group">
                        <label>Estoque</label>
                        <input type="number" id="estoque" value="0">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="ativo">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button class="btn-save" onclick="saveItem()">Salvar</button>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alert" class="alert"></div>

    <script>
        // Dados mockados
        let books = [
            {
                id: 1,
                titulo: "Doces Coloridos",
                autor: "Ladurée",
                editora: "Editora Globo",
                descricao: "Dos biscoitos aos bolos, passando pelas tortas e doçuras, este livro de receitas desvela os grandes clássicos da Maison Ladurée.",
                preco: 160.93,
                categoria: "Gastronomia",
                imagem_url: "https://m.media-amazon.com/images/I/51RAb17zveL._SX342_SY445_ML2_.jpg",
                estoque: 10,
                ativo: 1,
                data_cadastro: "2024-01-15"
            },
            {
                id: 2,
                titulo: "Bolos da Vovó",
                autor: "Maria da Silva",
                editora: "Editora Doce",
                descricao: "As receitas tradicionais da família em um livro especial.",
                preco: 89.90,
                categoria: "Doces",
                imagem_url: "https://images.unsplash.com/photo-1578985545062-69928b1d9587",
                estoque: 25,
                ativo: 1,
                data_cadastro: "2024-01-10"
            }
        ];

        let recipes = [
            {
                id: 1,
                titulo: "Bolo de Cenoura com Cobertura de Chocolate",
                autor: "Vovó Maria",
                descricao: "Bolo fofinho da vovó com cobertura cremosa",
                preco: 0,
                categoria: "Doces",
                imagem_url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSDgdwkjxrDOmMIg9UJmk0_b8YepaWsPI6TA&s",
                ativo: 1,
                tempo_preparo: "45min",
                dificuldade: "Fácil"
            },
            {
                id: 2,
                titulo: "Lasanha da Vovó",
                autor: "Vovó Maria",
                descricao: "Lasanha caseira tradicional",
                preco: 0,
                categoria: "Salgados",
                imagem_url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRVKK-9OZDsONLj4NikWXeQsKGji6zQu2PGTA&s",
                ativo: 1,
                tempo_preparo: "60min",
                dificuldade: "Médio"
            }
        ];

        let currentPage = 1;
        let currentRecipePage = 1;
        const itemsPerPage = 10;

        // Menu Navigation
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', () => {
                const section = item.dataset.section;
                
                document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
                item.classList.add('active');
                
                document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
                document.getElementById(section).classList.add('active');
                
                const titles = {
                    dashboard: 'Dashboard',
                    products: 'Livros',
                    recipes: 'Receitas',
                    orders: 'Pedidos',
                    users: 'Usuários',
                    settings: 'Configurações'
                };
                document.getElementById('pageTitle').textContent = titles[section];
                
                if (section === 'products') {
                    loadBooks();
                } else if (section === 'recipes') {
                    loadRecipes();
                } else if (section === 'dashboard') {
                    loadDashboard();
                } else if (section === 'orders') {
                    loadOrders();
                } else if (section === 'users') {
                    loadUsers();
                }
            });
        });

        function loadDashboard() {
            document.getElementById('totalBooks').textContent = books.length;
            document.getElementById('totalRecipes').textContent = recipes.length;
            document.getElementById('todayOrders').textContent = "3";
            document.getElementById('activeUsers').textContent = "127";
            
            // Recent books
            const recentHtml = `
                <table>
                    <thead>
                        <tr><th>Título</th><th>Autor</th><th>Preço</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        ${books.slice(0, 5).map(book => `
                            <tr>
                                <td>${book.titulo}</td>
                                <td>${book.autor}</td>
                                <td>R$ ${book.preco.toFixed(2)}</td>
                                <td><span class="status ${book.ativo ? 'active' : 'inactive'}">${book.ativo ? 'Ativo' : 'Inativo'}</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            document.getElementById('recentBooks').innerHTML = recentHtml;
        }

        function loadBooks() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            let filteredBooks = books.filter(book => 
                book.titulo.toLowerCase().includes(searchTerm) || 
                book.autor.toLowerCase().includes(searchTerm)
            );
            
            const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage;
            const paginatedBooks = filteredBooks.slice(start, start + itemsPerPage);
            
            const tableHtml = `
                <table>
                    <thead>
                        <tr><th>Capa</th><th>Título</th><th>Autor</th><th>Preço</th><th>Estoque</th><th>Status</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        ${paginatedBooks.map(book => `
                            <tr>
                                <td><img src="${book.imagem_url}" class="book-cover" onerror="this.src='https://via.placeholder.com/50x70'"></td>
                                <td><strong>${book.titulo}</strong></td>
                                <td>${book.autor}</td>
                                <td>R$ ${book.preco.toFixed(2)}</td>
                                <td>${book.estoque}</td>
                                <td><span class="status ${book.ativo ? 'active' : 'inactive'}">${book.ativo ? 'Ativo' : 'Inativo'}</span></td>
                                <td class="actions">
                                    <button class="btn-edit" onclick="editBook(${book.id})"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete" onclick="deleteBook(${book.id})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            
            document.getElementById('booksTable').innerHTML = tableHtml;
            
            // Pagination
            let paginationHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
            }
            document.getElementById('pagination').innerHTML = paginationHtml;
        }

        function loadRecipes() {
            const searchTerm = document.getElementById('searchRecipeInput')?.value.toLowerCase() || '';
            let filteredRecipes = recipes.filter(recipe => 
                recipe.titulo.toLowerCase().includes(searchTerm)
            );
            
            const totalPages = Math.ceil(filteredRecipes.length / itemsPerPage);
            const start = (currentRecipePage - 1) * itemsPerPage;
            const paginatedRecipes = filteredRecipes.slice(start, start + itemsPerPage);
            
            const tableHtml = `
                <table>
                    <thead>
                        <tr><th>Imagem</th><th>Título</th><th>Categoria</th><th>Tempo</th><th>Dificuldade</th><th>Status</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        ${paginatedRecipes.map(recipe => `
                            <tr>
                                <td><img src="${recipe.imagem_url}" class="book-cover" onerror="this.src='https://via.placeholder.com/50x70'"></td>
                                <td><strong>${recipe.titulo}</strong></td>
                                <td>${recipe.categoria}</td>
                                <td>${recipe.tempo_preparo || '--'}</td>
                                <td>${recipe.dificuldade || '--'}</td>
                                <td><span class="status ${recipe.ativo ? 'active' : 'inactive'}">${recipe.ativo ? 'Ativo' : 'Inativo'}</span></td>
                                <td class="actions">
                                    <button class="btn-edit" onclick="editRecipe(${recipe.id})"><i class="fas fa-edit"></i></button>
                                    <button class="btn-delete" onclick="deleteRecipe(${recipe.id})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            
            document.getElementById('recipesTable').innerHTML = tableHtml;
            
            // Pagination
            let paginationHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<button class="page-btn ${i === currentRecipePage ? 'active' : ''}" onclick="changeRecipePage(${i})">${i}</button>`;
            }
            document.getElementById('recipePagination').innerHTML = paginationHtml;
        }

        function loadOrders() {
            document.getElementById('ordersTable').innerHTML = `
                <table>
                    <thead><tr><th>ID</th><th>Cliente</th><th>Produto</th><th>Valor</th><th>Status</th><th>Data</th></tr></thead>
                    <tbody>
                        <tr><td>#001</td><td>Maria Silva</td><td>Doces Coloridos</td><td>R$ 160,93</td><td><span class="status active">Entregue</span></td><td>15/01/2024</td></tr>
                        <tr><td>#002</td><td>João Santos</td><td>Bolos da Vovó</td><td>R$ 89,90</td><td><span class="status active">Em andamento</span></td><td>14/01/2024</td></tr>
                        <tr><td>#003</td><td>Ana Paula</td><td>Doces Coloridos</td><td>R$ 160,93</td><td><span class="status inactive">Pendente</span></td><td>13/01/2024</td></tr>
                    </tbody>
                </table>
            `;
        }

        function loadUsers() {
            document.getElementById('usersTable').innerHTML = `
                <table>
                    <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Tipo</th><th>Status</th><th>Data Cadastro</th></tr></thead>
                    <tbody>
                        <tr><td>1</td><td>Administrador</td><td>admin@candyloves.com</td><td>Admin</td><td><span class="status active">Ativo</span></td><td>01/01/2024</td></tr>
                        <tr><td>2</td><td>Maria Silva</td><td>maria@email.com</td><td>Cliente</td><td><span class="status active">Ativo</span></td><td>10/01/2024</td></tr>
                        <tr><td>3</td><td>João Santos</td><td>joao@email.com</td><td>Cliente</td><td><span class="status active">Ativo</span></td><td>11/01/2024</td></tr>
                    </tbody>
                </table>
            `;
        }

        function openModal(type) {
            document.getElementById('itemType').value = type;
            document.getElementById('modalTitle').textContent = type === 'book' ? 'Adicionar Livro' : 'Adicionar Receita';
            document.getElementById('itemForm').reset();
            document.getElementById('itemId').value = '';
            document.getElementById('modal').classList.add('active');
            
            // Mostrar/esconder campos específicos
            const recipeFields = document.querySelectorAll('.recipe-field');
            if (type === 'recipe') {
                // Adicionar campos específicos de receita se necessário
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.remove('active');
        }

        function saveItem() {
            const type = document.getElementById('itemType').value;
            const id = document.getElementById('itemId').value;
            
            const item = {
                titulo: document.getElementById('titulo').value,
                autor: document.getElementById('autor').value,
                editora: document.getElementById('editora').value,
                descricao: document.getElementById('descricao').value,
                preco: parseFloat(document.getElementById('preco').value),
                categoria: document.getElementById('categoria').value,
                imagem_url: document.getElementById('imagem_url').value,
                estoque: parseInt(document.getElementById('estoque').value),
                ativo: parseInt(document.getElementById('ativo').value)
            };
            
            if (type === 'book') {
                if (id) {
                    // Update
                    const index = books.findIndex(b => b.id == id);
                    if (index !== -1) {
                        item.id = parseInt(id);
                        item.data_cadastro = books[index].data_cadastro;
                        books[index] = item;
                        showAlert('Livro atualizado com sucesso!', 'success');
                    }
                } else {
                    // Add new
                    item.id = books.length + 1;
                    item.data_cadastro = new Date().toISOString().split('T')[0];
                    books.push(item);
                    showAlert('Livro adicionado com sucesso!', 'success');
                }
                loadBooks();
            }
            
            closeModal();
            loadDashboard();
        }

        function editBook(id) {
            const book = books.find(b => b.id === id);
            if (book) {
                document.getElementById('itemType').value = 'book';
                document.getElementById('itemId').value = book.id;
                document.getElementById('titulo').value = book.titulo;
                document.getElementById('autor').value = book.autor;
                document.getElementById('editora').value = book.editora || '';
                document.getElementById('descricao').value = book.descricao;
                document.getElementById('preco').value = book.preco;
                document.getElementById('categoria').value = book.categoria;
                document.getElementById('imagem_url').value = book.imagem_url;
                document.getElementById('estoque').value = book.estoque;
                document.getElementById('ativo').value = book.ativo;
                document.getElementById('modalTitle').textContent = 'Editar Livro';
                document.getElementById('modal').classList.add('active');
            }
        }

        function deleteBook(id) {
            if (confirm('Tem certeza que deseja excluir este livro?')) {
                books = books.filter(b => b.id !== id);
                loadBooks();
                loadDashboard();
                showAlert('Livro excluído com sucesso!', 'success');
            }
        }

        function editRecipe(id) {
            showAlert('Funcionalidade em desenvolvimento', 'success');
        }

        function deleteRecipe(id) {
            if (confirm('Tem certeza que deseja excluir esta receita?')) {
                recipes = recipes.filter(r => r.id !== id);
                loadRecipes();
                showAlert('Receita excluída com sucesso!', 'success');
            }
        }

        function changePage(page) {
            currentPage = page;
            loadBooks();
        }

        function changeRecipePage(page) {
            currentRecipePage = page;
            loadRecipes();
        }

        function showAlert(message, type) {
            const alertDiv = document.getElementById('alert');
            alertDiv.textContent = message;
            alertDiv.className = `alert ${type}`;
            setTimeout(() => {
                alertDiv.style.display = 'none';
                alertDiv.className = 'alert';
            }, 3000);
        }

        function saveSettings() {
            showAlert('Configurações salvas com sucesso!', 'success');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Search functionality
        if (document.getElementById('searchInput')) {
            document.getElementById('searchInput').addEventListener('input', () => {
                currentPage = 1;
                loadBooks();
            });
        }

        if (document.getElementById('searchRecipeInput')) {
            document.getElementById('searchRecipeInput').addEventListener('input', () => {
                currentRecipePage = 1;
                loadRecipes();
            });
        }

        // Initial load
        loadDashboard();
        loadBooks();
    </script>
</body>

</html>