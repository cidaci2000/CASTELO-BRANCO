<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Candy's Love's</title>
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
            background-color: #f9f5f2;
            overflow-x: hidden;
            color: #3b2c2c;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fff;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .sidebar-header h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: #fff;
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.5px;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.9);
        }

        .sidebar-menu {
            padding: 20px 0;
            flex: 1;
        }

        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.15);
            border-left-color: #fff;
            padding-left: 30px;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.25);
            border-left-color: #fff;
            color: #fff;
            font-weight: 700;
        }

        .menu-item i {
            width: 24px;
            font-size: 18px;
            text-align: center;
        }

        /* Logout button na sidebar */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-logout i {
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 25px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 6px 20px rgba(90, 70, 70, 0.08);
            border: 1px solid rgba(184, 59, 94, 0.1);
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-title {
            font-size: 2rem;
            color: #b83b5e;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.5px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            background: linear-gradient(135deg, #f9d5e5 0%, #f3e1dd 100%);
            padding: 12px 20px;
            border-radius: 50px;
            color: #b83b5e;
            font-weight: 600;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.3);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
            animation: fadeInUp 0.6s ease;
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

        .stat-card {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 15px;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 6px 20px rgba(90, 70, 70, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(184, 59, 94, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 36px rgba(184, 59, 94, 0.15);
            border-color: #b83b5e;
        }

        .stat-info h3 {
            font-size: 0.9rem;
            color: #8b7575;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #b83b5e;
            font-family: 'Playfair Display', serif;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #f9d5e5 0%, #f3e1dd 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #b83b5e;
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
            background: linear-gradient(135deg, #f3e1dd 0%, #edd0d9 100%);
        }

        /* Content Sections */
        .content-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Table Container */
        .table-container {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(90, 70, 70, 0.08);
            border: 1px solid rgba(184, 59, 94, 0.1);
            overflow-x: auto;
            margin-bottom: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h3 {
            color: #b83b5e;
            font-size: 1.5rem;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        .search-box {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-box input {
            padding: 12px 18px;
            border: 1px solid #d8a7b1;
            border-radius: 25px;
            width: 250px;
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
            color: #3b2c2c;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .search-box input:focus {
            outline: none;
            border-color: #b83b5e;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.2);
            background: #fff;
        }

        .btn-add {
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.2);
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(184, 59, 94, 0.3);
            background: linear-gradient(135deg, #a02d50 0%, #8b2545 100%);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #f0e6e2;
        }

        th {
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
        }

        .book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 8px 12px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fff;
            box-shadow: 0 2px 6px rgba(184, 59, 94, 0.2);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(244, 67, 54, 0.2);
        }

        .btn-edit:hover, .btn-delete:hover {
            transform: translateY(-2px);
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
        }

        .status.active {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .status.inactive {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
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
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(135deg, #fffaf7 0%, #fff5f0 100%);
            border-radius: 20px;
            width: 100%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
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
            padding: 25px 30px;
            border-bottom: 1px solid rgba(184, 59, 94, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            border-radius: 20px 20px 0 0;
        }

        .modal-header h3 {
            font-size: 1.8rem;
            color: #fff;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        .close-modal {
            font-size: 28px;
            cursor: pointer;
            color: #fff;
            transition: all 0.3s ease;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #b83b5e;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d8a7b1;
            border-radius: 12px;
            font-size: 0.95rem;
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
            color: #3b2c2c;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #b83b5e;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.2);
            background: #fff;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-footer {
            padding: 25px 30px;
            border-top: 1px solid rgba(184, 59, 94, 0.1);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
            border-radius: 0 0 20px 20px;
        }

        .btn-save, .btn-cancel {
            padding: 12px 28px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
        }

        .btn-save {
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.2);
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(184, 59, 94, 0.3);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.15);
        }

        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(184, 59, 94, 0.25);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .page-btn {
            padding: 8px 12px;
            border: 1px solid #d8a7b1;
            background: linear-gradient(135deg, #f9f5f2 0%, #f5f0ed 100%);
            cursor: pointer;
            border-radius: 20px;
            transition: all 0.3s ease;
            color: #b83b5e;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .page-btn.active {
            background: linear-gradient(135deg, #b83b5e 0%, #a02d50 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.2);
        }

        .page-btn:hover {
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Alert Messages */
        .alert {
            position: fixed;
            top: 25px;
            right: 25px;
            padding: 16px 24px;
            border-radius: 12px;
            z-index: 3000;
            animation: slideInRight 0.3s ease;
            display: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
            display: flex;
        }

        .alert.error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
            display: flex;
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

        /* Menu Toggle Button */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, #d8a7b1 0%, #c9949f 100%);
            color: #fff;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            border: none;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(184, 59, 94, 0.2);
        }

        .menu-toggle:hover {
            transform: scale(1.05);
        }
        .btn-logout {
display: flex;
align-items: center;
justify-content: center;
gap: 10px;
width: 100%;
padding: 12px;
background: rgba(255, 255, 255, 0.15);
border: 1px solid rgba(255, 255, 255, 0.3);
border-radius: 12px;
color: #fff;
font-weight: 600;
font-size: 1rem;
cursor: pointer;
transition: all 0.3s ease;
text-decoration: none;
font-family: 'Poppins', sans-serif;
}

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 240px;
            }

            .main-content {
                margin-left: 240px;
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 100%;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .menu-toggle {
                display: block;
            }

            .top-bar {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }

            .page-title {
                font-size: 1.6rem;
                width: 100%;
            }

            .user-info {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
                flex-direction: column;
            }

            .search-box input {
                width: 100%;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 10px 8px;
            }

            .actions {
                flex-direction: column;
                gap: 5px;
            }

            .btn-edit, .btn-delete {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }

            .main-content {
                padding: 10px;
            }

            .menu-toggle {
                top: 10px;
                left: 10px;
                padding: 10px;
            }

            .top-bar {
                padding: 15px;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .sidebar-header {
                padding: 20px 15px;
            }

            .sidebar-header h2 {
                font-size: 1.5rem;
            }

            .menu-item {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 25px;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .table-container {
                padding: 15px;
                margin-bottom: 20px;
            }

            .table-header h3 {
                font-size: 1.2rem;
            }

            .search-box input {
                font-size: 0.9rem;
                padding: 10px 12px;
            }

            .modal-content {
                border-radius: 12px;
            }

            .modal-header,
            .modal-footer {
                padding: 15px 20px;
            }

            .modal-header h3 {
                font-size: 1.4rem;
            }

            .modal-body {
                padding: 20px;
            }

            .btn-save, .btn-cancel {
                padding: 10px 20px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <!-- Menu Toggle para Mobile -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>🍓 Candy's</h2>
            <p>Painel Administrativo</p>
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
            <div class="menu-item" data-section="categories">
                <i class="fas fa-th"></i>
                <span>Categorias</span>
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
        <!-- Botão de Logout na Sidebar -->
        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h1 class="page-title" id="pageTitle">Dashboard</h1>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span>Bem-vindo(a), <strong>Administrador</strong></span>
                <div class="user-avatar">A</div>
                <!-- Logout no top bar para mobile -->
                <a href="logout.php" style="color: #b83b5e; text-decoration: none; margin-left: 10px; font-size: 1.2rem;" title="Sair">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
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
                <h3 style="color: #b83b5e; margin-bottom: 25px; font-family: 'Playfair Display', serif;">📖 Últimos Livros Adicionados</h3>
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

        <!-- Categories Section -->
        <div id="categories" class="content-section">
            <div class="table-container">
                <div class="table-header">
                    <h3>🏷️ Gerenciar Categorias</h3>
                    <button class="btn-add" onclick="openModal('category')">
                        <i class="fas fa-plus"></i> Nova Categoria
                    </button>
                </div>
                <div id="categoriesTable"></div>
            </div>
        </div>

        <!-- Orders Section -->
        <div id="orders" class="content-section">
            <div class="table-container">
                <h3>🛒 Pedidos Recentes</h3>
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
                <h3 style="margin-bottom: 25px;">⚙️ Configurações do Sistema</h3>
                <form id="settingsForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nome da Loja</label>
                            <input type="text" id="storeName" value="Candy's Love's">
                        </div>
                        <div class="form-group">
                            <label>Email de Contato</label>
                            <input type="email" id="contactEmail" value="contato@candyloves.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" id="phone" value="(11) 9999-9999">
                        </div>
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="text" id="whatsapp" value="(11) 9999-9999">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Endereço</label>
                        <input type="text" id="address" value="São Paulo - SP">
                    </div>
                    <button type="button" class="btn-save" onclick="saveSettings()">
                        <i class="fas fa-save"></i> Salvar Configurações
                    </button>
                </form>
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
                    <div class="form-row">
                        <div class="form-group">
                            <label>Título *</label>
                            <input type="text" id="titulo" required>
                        </div>
                        <div class="form-group">
                            <label>Autor *</label>
                            <input type="text" id="autor" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Editora</label>
                            <input type="text" id="editora">
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
                    </div>
                    <div class="form-group">
                        <label>Descrição *</label>
                        <textarea id="descricao" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Preço *</label>
                            <input type="number" id="preco" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Estoque</label>
                            <input type="number" id="estoque" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>URL da Imagem *</label>
                        <input type="url" id="imagem_url" required>
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
                <button class="btn-cancel" onclick="closeModal()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button class="btn-save" onclick="saveItem()">
                    <i class="fas fa-save"></i> Salvar
                </button>
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
                    categories: 'Categorias',
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
                } else if (section === 'categories') {
                    loadCategories();
                }

                // Fechar sidebar em mobile
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('active');
                }
            });
        });

        function loadDashboard() {
            document.getElementById('totalBooks').textContent = books.length;
            document.getElementById('totalRecipes').textContent = recipes.length;
            document.getElementById('todayOrders').textContent = "12";
            document.getElementById('activeUsers').textContent = "127";
            
            const recentHtml = `
                <table>
                    <thead>
                        <tr><th>Título</th><th>Autor</th><th>Preço</th><th>Estoque</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        ${books.slice(0, 5).map(book => `
                            <tr>
                                <td><strong>${book.titulo}</strong></td>
                                <td>${book.autor}</td>
                                <td>R$ ${book.preco.toFixed(2)}</td>
                                <td>${book.estoque}</td>
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
                                    <button class="btn-edit" onclick="editBook(${book.id})"><i class="fas fa-edit"></i> Editar</button>
                                    <button class="btn-delete" onclick="deleteBook(${book.id})"><i class="fas fa-trash"></i> Excluir</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            
            document.getElementById('booksTable').innerHTML = tableHtml;
            
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
                                    <button class="btn-edit" onclick="editRecipe(${recipe.id})"><i class="fas fa-edit"></i> Editar</button>
                                    <button class="btn-delete" onclick="deleteRecipe(${recipe.id})"><i class="fas fa-trash"></i> Excluir</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            
            document.getElementById('recipesTable').innerHTML = tableHtml;
            
            let paginationHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<button class="page-btn ${i === currentRecipePage ? 'active' : ''}" onclick="changeRecipePage(${i})">${i}</button>`;
            }
            document.getElementById('recipePagination').innerHTML = paginationHtml;
        }

        function loadCategories() {
            const categories = [
                { id: 1, nome: 'Doces', receitas: 32, status: 1 },
                { id: 2, nome: 'Salgados', receitas: 24, status: 1 },
                { id: 3, nome: 'Tortas', receitas: 18, status: 1 },
                { id: 4, nome: 'Café da Manhã', receitas: 20, status: 1 }
            ];

            const tableHtml = `
                <table>
                    <thead>
                        <tr><th>Nome</th><th>Receitas</th><th>Status</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        ${categories.map(cat => `
                            <tr>
                                <td><strong>${cat.nome}</strong></td>
                                <td>${cat.receitas}</td>
                                <td><span class="status ${cat.status ? 'active' : 'inactive'}">${cat.status ? 'Ativa' : 'Inativa'}</span></td>
                                <td class="actions">
                                    <button class="btn-edit" onclick="alert('Editar categoria: ${cat.nome}')"><i class="fas fa-edit"></i> Editar</button>
                                    <button class="btn-delete" onclick="alert('Excluir categoria: ${cat.nome}')"><i class="fas fa-trash"></i> Excluir</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            document.getElementById('categoriesTable').innerHTML = tableHtml;
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
            const titles = {
                'book': 'Adicionar Livro',
                'recipe': 'Adicionar Receita',
                'category': 'Adicionar Categoria'
            };
            document.getElementById('modalTitle').textContent = titles[type] || 'Adicionar Item';
            document.getElementById('itemForm').reset();
            document.getElementById('itemId').value = '';
            document.getElementById('modal').classList.add('active');
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
                preco: parseFloat(document.getElementById('preco').value) || 0,
                categoria: document.getElementById('categoria').value,
                imagem_url: document.getElementById('imagem_url').value,
                estoque: parseInt(document.getElementById('estoque').value) || 0,
                ativo: parseInt(document.getElementById('ativo').value)
            };
            
            if (type === 'book') {
                if (id) {
                    const index = books.findIndex(b => b.id == id);
                    if (index !== -1) {
                        item.id = parseInt(id);
                        item.data_cadastro = books[index].data_cadastro;
                        books[index] = { ...books[index], ...item };
                        showAlert('✅ Livro atualizado com sucesso!', 'success');
                    }
                } else {
                    item.id = Math.max(...books.map(b => b.id), 0) + 1;
                    item.data_cadastro = new Date().toISOString().split('T')[0];
                    books.push(item);
                    showAlert('✅ Livro adicionado com sucesso!', 'success');
                }
                loadBooks();
                loadDashboard();
            }
            
            closeModal();
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
                showAlert('✅ Livro excluído com sucesso!', 'success');
            }
        }

        function editRecipe(id) {
            showAlert('ℹ️ Funcionalidade em desenvolvimento', 'success');
        }

        function deleteRecipe(id) {
            if (confirm('Tem certeza que deseja excluir esta receita?')) {
                recipes = recipes.filter(r => r.id !== id);
                loadRecipes();
                showAlert('✅ Receita excluída com sucesso!', 'success');
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
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            alertDiv.className = `alert ${type}`;
            setTimeout(() => {
                alertDiv.classList.remove('success', 'error');
            }, 4000);
        }

        function saveSettings() {
            showAlert('✅ Configurações salvas com sucesso!', 'success');
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

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Initial load
        loadDashboard();
    </script>
</body>

</html>