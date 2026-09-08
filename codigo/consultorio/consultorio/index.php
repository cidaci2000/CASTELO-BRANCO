<?php
require_once 'config.php';

// REMOVA QUALQUER LINHA QUE TENHA requireLogin() ou isLoggedIn() aqui!
// O index.php DEVE ser público

// Buscar médicos ativos do banco
$sql = "SELECT * FROM medicos WHERE status = 'ativo' ORDER BY nome";
$result = $conn->query($sql);

// Verificar se a consulta foi bem sucedida
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$medicos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $medicos[] = $row;
    }
}

// Verificar se usuário está logado (apenas para exibir informações, NÃO para redirecionar)
$usuario_logado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true;
$usuario_nome = $_SESSION['usuario_nome'] ?? '';
$usuario_tipo = $_SESSION['usuario_tipo'] ?? '';
$usuario_id = $_SESSION['usuario_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Saúde Total - Agendamento de Consultas</title>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset e Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f4f8;
            color: #1a202c;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #2b6cb0 0%, #2c5282 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(43, 108, 176, 0.3);
            position: relative;
            overflow: hidden;
        }

        header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-left .logo-icon {
            font-size: 2.5rem;
            background: rgba(255, 255, 255, 0.15);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .header-left h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #FFD700;
        }

        .header-left p {
            font-size: 0.95rem;
            opacity: 0.85;
            font-weight: 300;
            margin-top: 0.1rem;
        }

        /* Header Right - Login */
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .login-area {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1.5rem 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .login-area .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .login-area .user-info .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .login-area .user-info .user-details {
            line-height: 1.3;
        }

        .login-area .user-info .user-details .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .login-area .user-info .user-details .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-login.outline {
            background: transparent;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .btn-login.outline:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-logout:hover {
            background: rgba(255, 0, 0, 0.2);
            border-color: rgba(255, 0, 0, 0.3);
        }

        .btn-header {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            font-size: 0.8rem;
        }

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Main */
        main {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
            flex: 1;
            width: 100%;
        }

        /* Seção Médicos */
        .medicos-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .medicos-section h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .medicos-section h2 i {
            color: #2b6cb0;
            font-size: 1.5rem;
        }

        .medicos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        /* Card do Médico */
        .medico-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 380px;
        }

        .medico-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(43, 108, 176, 0.15);
            border-color: #bee3f8;
        }

        .medico-card .medico-foto {
            height: 180px;
            min-height: 180px;
            max-height: 180px;
            background: linear-gradient(135deg, #ebf4ff, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .medico-card .medico-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .medico-card:hover .medico-foto img {
            transform: scale(1.05);
        }

        .medico-card .foto-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
        }

        .medico-card .card-body {
            padding: 1.25rem 1.5rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .medico-card h3 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
            line-height: 1.3;
            min-height: 2.6rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .medico-card .especialidade {
            font-weight: 500;
            color: #2b6cb0;
            font-size: 0.95rem;
            display: inline-block;
            background: #ebf4ff;
            padding: 0.2rem 0.75rem;
            border-radius: 20px;
            margin: 0.5rem 0;
            align-self: flex-start;
            min-height: 2rem;
        }

        .medico-card .descricao {
            color: #4a5568;
            font-size: 0.9rem;
            margin: 0.5rem 0 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
            flex: 1;
            min-height: 4.5rem;
        }

        .medico-card .crm {
            font-size: 0.8rem;
            color: #718096;
            font-weight: 500;
            letter-spacing: 0.3px;
            margin-top: auto;
        }

        .medico-card .card-footer {
            padding: 0.75rem 1.5rem 1.25rem;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            background: white;
            min-height: 60px;
        }

        .medico-card .btn-agendar {
            background: #2b6cb0;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            text-decoration: none;
        }

        .medico-card .btn-agendar:hover {
            background: #2c5282;
        }

        .medico-card .btn-agendar.btn-login-link {
            background: #38a169;
        }

        .medico-card .btn-agendar.btn-login-link:hover {
            background: #2f855a;
        }

        /* Sem médicos */
        .sem-medicos {
            text-align: center;
            padding: 4rem 2rem;
            color: #4a5568;
        }

        .sem-medicos i {
            font-size: 3rem;
            color: #a0aec0;
            margin-bottom: 1rem;
            display: block;
        }

        .sem-medicos p:first-of-type {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            max-width: 560px;
            width: 100%;
            padding: 2rem 2.5rem;
            position: relative;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            animation: modalIn 0.3s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal .close {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 2rem;
            font-weight: 300;
            color: #a0aec0;
            cursor: pointer;
            transition: color 0.2s;
            line-height: 1;
            background: none;
            border: none;
        }

        .modal .close:hover {
            color: #2d3748;
        }

        .modal-doctor-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin: 1.5rem 0 1.75rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f7fafc;
        }

        .modal-doctor-foto {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            background: #edf2f7;
            flex-shrink: 0;
        }

        .modal-doctor-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-doctor-foto .foto-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            background: #e2e8f0;
        }

        .modal-doctor-details h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
        }

        .modal-doctor-details p {
            color: #4a5568;
            font-size: 0.95rem;
        }

        .modal-doctor-details p:last-of-type {
            font-size: 0.85rem;
            color: #718096;
        }

        /* Formulário do Modal - CORRIGIDO */
        #modal-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 0.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #e53e3e;
        }

        #modal-form input,
        #modal-form textarea {
            padding: 0.75rem 1rem;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #f7fafc;
            width: 100%;
        }

        #modal-form input:focus,
        #modal-form textarea:focus {
            outline: none;
            border-color: #2b6cb0;
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
            background: white;
        }

        #modal-form textarea {
            resize: vertical;
            min-height: 80px;
        }

        #modal-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        #modal-form button[type="submit"] {
            background: linear-gradient(135deg, #2b6cb0, #2c5282);
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
            letter-spacing: 0.3px;
        }

        #modal-form button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(43, 108, 176, 0.3);
        }

        #modal-form input[readonly] {
            background: #edf2f7;
            cursor: not-allowed;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #f0fff4;
            color: #276749;
            border: 1px solid #c6f6d5;
        }

        .alert-error {
            background: #fff5f5;
            color: #9b2c2c;
            border: 1px solid #feb2b2;
        }

        .alert-info {
            background: #ebf8ff;
            color: #2a69ac;
            border: 1px solid #bee3f8;
        }

        /* Footer */
        footer {
            background: #1a202c;
            color: #a0aec0;
            padding: 3rem 2rem 1.5rem;
            margin-top: 3rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2.5rem;
        }

        .footer-section h3 {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: 0.3px;
        }

        .footer-section h3 i {
            margin-right: 0.5rem;
            color: #2b6cb0;
        }

        .footer-section p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            line-height: 1.7;
        }

        .footer-section p i {
            width: 20px;
            color: #2b6cb0;
            margin-right: 0.5rem;
        }

        .footer-section .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .footer-section .social-links a {
            color: #a0aec0;
            font-size: 1.2rem;
            transition: all 0.3s;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .footer-section .social-links a:hover {
            color: white;
            background: #2b6cb0;
            transform: translateY(-3px);
        }

        .footer-section .hours {
            font-size: 0.9rem;
            line-height: 1.8;
        }

        .footer-section .hours span {
            color: #e2e8f0;
        }

        .footer-bottom {
            max-width: 1400px;
            margin: 2rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.85rem;
        }

        .footer-bottom .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-bottom .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-bottom .footer-links a:hover {
            color: white;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .header-left {
                justify-content: center;
                text-align: center;
            }

            .header-right {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 1rem 1rem;
            }

            .header-left h1 {
                font-size: 1.5rem;
            }

            .header-left .logo-icon {
                width: 45px;
                height: 45px;
                font-size: 1.8rem;
            }

            .header-left p {
                font-size: 0.8rem;
            }

            .login-area {
                padding: 0.4rem 1rem 0.4rem 0.75rem;
                gap: 0.75rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .login-area .user-info .user-details .user-name {
                font-size: 0.8rem;
            }

            .btn-login {
                padding: 0.4rem 1rem;
                font-size: 0.75rem;
            }

            main {
                padding: 0 1rem;
                margin: 1.5rem auto;
            }

            .medicos-section {
                padding: 1.5rem 1rem;
            }

            .medicos-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 1.25rem;
            }

            .medico-card {
                min-height: 340px;
            }

            .medico-card .medico-foto {
                height: 160px;
                min-height: 160px;
                max-height: 160px;
            }

            .modal-content {
                padding: 1.5rem 1.25rem;
                margin: 1rem;
            }

            .modal-doctor-info {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            #modal-form .form-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .modal .close {
                top: 0.75rem;
                right: 1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-section .social-links {
                justify-content: center;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-bottom .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .medicos-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }

            .medico-card {
                min-height: 320px;
            }

            .medico-card .card-body {
                padding: 1rem 1.25rem 0.75rem;
            }

            .medico-card .card-footer {
                padding: 0.5rem 1.25rem 1rem;
                flex-direction: column;
                gap: 0.5rem;
                align-items: stretch;
                min-height: 50px;
            }

            .medico-card .btn-agendar {
                justify-content: center;
            }

            .medico-card h3 {
                min-height: 2.2rem;
                font-size: 1rem;
            }

            .medico-card .descricao {
                min-height: 3.5rem;
                font-size: 0.85rem;
            }

            .login-area {
                flex-direction: column;
                align-items: stretch;
                border-radius: 16px;
                padding: 0.75rem;
                width: 100%;
            }

            .login-area .user-info {
                justify-content: center;
            }

            .header-right .btn-login,
            .header-right .btn-logout {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-left">
                <div class="logo-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>
                    <h1>Clínica Saúde Total</h1>
                    <p><i class="fas fa-phone-alt" style="font-size: 0.8rem; opacity: 0.7;"></i> (45) 4000-0000 • <i class="fas fa-map-marker-alt" style="font-size: 0.8rem; opacity: 0.7;"></i> Cascavel, PR</p>
                </div>
            </div>

            <div class="header-right">
                <div class="login-area">
                    <?php if ($usuario_logado): ?>
                        <div class="user-info">
                            <div class="avatar">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="user-details">
                                <div class="user-name"><?php echo htmlspecialchars($usuario_nome); ?></div>
                                <div class="user-role"><?php echo ucfirst($usuario_tipo); ?></div>
                            </div>
                        </div>
                        <?php if ($usuario_tipo == 'admin'): ?>
                            <a href="admin.php" class="btn-header">
                                <i class="fas fa-shield-alt"></i> Admin
                            </a>
                        <?php endif; ?>
                        <a href="historico.php" class="btn-header">
                            <i class="fas fa-history"></i> Histórico
                        </a>
                        <a href="logout.php" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="cadastro_usuario.php" class="btn-login outline">
                            <i class="fas fa-user-plus"></i> Cadastrar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Lista de Médicos em Cards -->
        <section class="medicos-section">
            <h2><i class="fas fa-user-md"></i> Médicos Disponíveis</h2>
            
            <?php if (empty($medicos)): ?>
                <div class="sem-medicos">
                    <i class="fas fa-user-slash"></i>
                    <p>Nenhum médico cadastrado ainda.</p>
                    <p style="color: #718096; font-size: 0.95rem;">Aguardando cadastro de médicos.</p>
                </div>
            <?php else: ?>
                <div id="medicos-container" class="medicos-grid">
                    <?php foreach ($medicos as $medico): ?>
                        <div class="medico-card" data-id="<?php echo $medico['id']; ?>">
                            <div class="medico-foto">
                                <?php if (!empty($medico['foto'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($medico['foto']); ?>" 
                                         alt="Foto de <?php echo htmlspecialchars($medico['nome']); ?>"
                                         onerror="this.src='img/default-doctor.png'">
                                <?php else: ?>
                                    <div class="foto-placeholder">
                                        <span>👨‍⚕️</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($medico['nome']); ?></h3>
                                <span class="especialidade"><?php echo htmlspecialchars($medico['especialidade']); ?></span>
                                <p class="descricao"><?php echo htmlspecialchars($medico['descricao']); ?></p>
                            </div>
                            <div class="card-footer">
                                <span class="crm">CRM: <?php echo htmlspecialchars($medico['crm']); ?></span>
                                <?php if ($usuario_logado): ?>
                                    <button class="btn-agendar" 
                                            onclick="abrirModal(<?php echo $medico['id']; ?>, 
                                                '<?php echo addslashes($medico['nome']); ?>', 
                                                '<?php echo addslashes($medico['especialidade']); ?>', 
                                                '<?php echo addslashes($medico['crm']); ?>')">
                                        <i class="fas fa-calendar-check"></i> Agendar
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn-agendar btn-login-link">
                                        <i class="fas fa-sign-in-alt"></i> Faça login
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Modal de Agendamento - CORRIGIDO -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <button class="close" onclick="fecharModal()">&times;</button>
            
            <?php
            // Limpar mensagens do modal que vieram de redirect
            if (isset($_SESSION['modal_message'])) {
                $modal_message_type = $_SESSION['modal_message_type'] ?? 'info';
                echo "<div class='alert alert-{$modal_message_type}'><i class='fas fa-info-circle'></i> {$_SESSION['modal_message']}</div>";
                unset($_SESSION['modal_message']);
                unset($_SESSION['modal_message_type']);
            }
            ?>
            
            <div class="modal-doctor-info">
                <div class="modal-doctor-foto" id="modal-foto">
                    <!-- A foto será inserida via JavaScript -->
                </div>
                <div class="modal-doctor-details">
                    <h2 id="modal-nome"></h2>
                    <p id="modal-especialidade"></p>
                    <p id="modal-crm"></p>
                </div>
            </div>
            
            <form id="modal-form" action="processa_agendamento.php" method="POST">
                <input type="hidden" name="medico_id" id="medico-id">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
                
                <div class="form-group">
                    <label>Seu nome completo <span class="required">*</span></label>
                    <input type="text" name="paciente_nome" value="<?php echo htmlspecialchars($usuario_nome); ?>" required readonly>
                </div>
                
                <div class="form-group">
                    <label>Seu e-mail <span class="required">*</span></label>
                    <input type="email" name="paciente_email" value="<?php echo htmlspecialchars($_SESSION['usuario_email'] ?? ''); ?>" required readonly>
                </div>
                
                <div class="form-group">
                    <label>Telefone para contato <span class="required">*</span></label>
                    <input type="text" name="paciente_telefone" id="telefone" placeholder="(00) 00000-0000" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Data da consulta <span class="required">*</span></label>
                        <input type="date" name="data_consulta" id="data-consulta" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Hora da consulta <span class="required">*</span></label>
                        <input type="time" name="hora_consulta" id="hora-consulta" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Observações (opcional)</label>
                    <textarea name="observacoes" placeholder="Observações (opcional)" rows="3"></textarea>
                </div>
                
                <button type="submit"><i class="fas fa-check"></i> Confirmar Agendamento</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3><i class="fas fa-heartbeat"></i> Clínica Saúde Total</h3>
                <p><i class="fas fa-map-marker-alt"></i> Av. Paulista, 1000 - Bela Vista</p>
                <p><i class="fas fa-map-pin"></i> São Paulo - SP, 01310-100</p>
                <p><i class="fas fa-phone-alt"></i> (11) 4000-0000</p>
                <p><i class="fas fa-envelope"></i> contato@saudetotal.com.br</p>
                <p><i class="fas fa-globe"></i> www.saudetotal.com.br</p>
            </div>

            <div class="footer-section">
                <h3><i class="fas fa-clock"></i> Horário de Funcionamento</h3>
                <div class="hours">
                    <p><span>Segunda a Sexta:</span> 07:00 - 22:00</p>
                    <p><span>Sábado:</span> 08:00 - 18:00</p>
                    <p><span>Domingo:</span> 08:00 - 14:00</p>
                    <p style="margin-top: 0.5rem; color: #48bb78; font-weight: 500;">
                        <i class="fas fa-check-circle"></i> Atendimento 24h para emergências
                    </p>
                </div>
            </div>

            <div class="footer-section">
                <h3><i class="fas fa-link"></i> Links Rápidos</h3>
                <p><a href="#" style="color: #a0aec0; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#a0aec0'"><i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i> Sobre a Clínica</a></p>
                <p><a href="#" style="color: #a0aec0; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#a0aec0'"><i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i> Especialidades</a></p>
                <p><a href="#" style="color: #a0aec0; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#a0aec0'"><i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i> Convênios</a></p>
                <p><a href="#" style="color: #a0aec0; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#a0aec0'"><i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i> Trabalhe Conosco</a></p>
                <p><a href="#" style="color: #a0aec0; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#a0aec0'"><i class="fas fa-chevron-right" style="font-size: 0.6rem;"></i> Fale Conosco</a></p>
            </div>

            <div class="footer-section">
                <h3><i class="fas fa-share-alt"></i> Redes Sociais</h3>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <p style="margin-top: 1rem; font-size: 0.85rem;">
                    <i class="fas fa-qrcode"></i> Escaneie nosso QR Code para agendamento rápido
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>
                &copy; <?php echo date('Y'); ?> Clínica Saúde Total - Todos os direitos reservados
            </span>
            <div class="footer-links">
                <a href="#">Política de Privacidade</a>
                <a href="#">Termos de Uso</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </footer>

    <script>
    // Array com os dados dos médicos para acesso no JavaScript
    const medicosData = <?php echo json_encode($medicos); ?>;
    
    // Função para abrir modal com dados do médico
    function abrirModal(id, nome, especialidade, crm) {
        document.getElementById('medico-id').value = id;
        document.getElementById('modal-nome').textContent = nome;
        document.getElementById('modal-especialidade').textContent = especialidade;
        document.getElementById('modal-crm').textContent = 'CRM: ' + crm;
        
        // Buscar a foto do médico
        const medico = medicosData.find(m => m.id == id);
        const fotoContainer = document.getElementById('modal-foto');
        
        if (medico && medico.foto) {
            fotoContainer.innerHTML = `<img src="uploads/${medico.foto}" alt="Foto de ${nome}" onerror="this.src='img/default-doctor.png'">`;
        } else {
            fotoContainer.innerHTML = '<div class="foto-placeholder"><span>👨‍⚕️</span></div>';
        }
        
        // Limpar campos do formulário
        document.getElementById('modal-form').reset();
        
        // Remover mensagens anteriores
        const alerts = document.querySelectorAll('.modal .alert');
        alerts.forEach(alert => alert.remove());
        
        // Abrir modal
        document.getElementById('modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Fechar modal
    function fecharModal() {
        document.getElementById('modal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            fecharModal();
        }
    }

    // Fechar modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fecharModal();
        }
    });

    // Máscara para telefone
    document.getElementById('telefone')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        
        if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5}).*/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d*)/, '($1');
        }
        
        e.target.value = value;
    });

    // Definir data mínima e hora padrão
    document.addEventListener('DOMContentLoaded', function() {
        const dataInput = document.getElementById('data-consulta');
        if (dataInput) {
            const hoje = new Date().toISOString().split('T')[0];
            dataInput.min = hoje;
            
            // Se não houver data selecionada, define para amanhã
            if (!dataInput.value) {
                const amanha = new Date();
                amanha.setDate(amanha.getDate() + 1);
                dataInput.value = amanha.toISOString().split('T')[0];
            }
        }

        const horaInput = document.getElementById('hora-consulta');
        if (horaInput && !horaInput.value) {
            const agora = new Date();
            const horas = String(agora.getHours()).padStart(2, '0');
            const minutos = String(Math.ceil(agora.getMinutes() / 30) * 30).padStart(2, '0');
            horaInput.value = `${horas}:${minutos}`;
        }
    });
    </script>
</body>
</html>