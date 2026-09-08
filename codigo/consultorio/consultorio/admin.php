<?php

require_once 'config.php';

// Proteger página de admin
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!isAdmin()) {
    header('Location: index.php');
    exit;
}


// Processar ações
$acao = $_GET['acao'] ?? 'dashboard';

// Buscar estatísticas
$stats = [];

// Total de médicos
$result = $conn->query("SELECT COUNT(*) as total FROM medicos");
$stats['medicos'] = $result->fetch_assoc()['total'];

// Total de usuários
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo = 'paciente'");
$stats['pacientes'] = $result->fetch_assoc()['total'];

// Total de agendamentos
$result = $conn->query("SELECT COUNT(*) as total FROM agendamentos");
$stats['agendamentos'] = $result->fetch_assoc()['total'];

// Agendamentos hoje
$result = $conn->query("SELECT COUNT(*) as total FROM agendamentos WHERE data_consulta = CURDATE()");
$stats['hoje'] = $result->fetch_assoc()['total'];

// Agendamentos pendentes (agendado/confirmado)
$result = $conn->query("SELECT COUNT(*) as total FROM agendamentos WHERE status IN ('agendado', 'confirmado') AND data_consulta >= CURDATE()");
$stats['pendentes'] = $result->fetch_assoc()['total'];

// Buscar médicos
$medicos = [];
$result = $conn->query("SELECT * FROM medicos ORDER BY nome");
while ($row = $result->fetch_assoc()) {
    $medicos[] = $row;
}

// Buscar usuários
$usuarios = [];
$result = $conn->query("SELECT * FROM usuarios ORDER BY nome");
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

// Buscar agendamentos
$agendamentos = [];
$sql = "SELECT a.*, m.nome as medico_nome, m.especialidade, u.nome as paciente_nome 
        FROM agendamentos a 
        LEFT JOIN medicos m ON a.medico_id = m.id 
        LEFT JOIN usuarios u ON a.usuario_id = u.id 
        ORDER BY a.data_consulta DESC, a.hora_consulta DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Clínica Saúde Total</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #1a202c;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #2b6cb0, #2c5282);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(43, 108, 176, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-left h1 {
            font-size: 1.5rem;
        }

        .header-left .subtitle {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-back {
            background: rgba(255,255,255,0.15);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.25);
        }

        .btn-logout {
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255, 0, 0, 0.2);
        }

        /* Main */
        .main {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #edf2f7;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2b6cb0;
        }

        .stat-card .stat-label {
            color: #718096;
            font-size: 0.9rem;
        }

        .stat-card.medicos .stat-icon { color: #2b6cb0; }
        .stat-card.pacientes .stat-icon { color: #38a169; }
        .stat-card.agendamentos .stat-icon { color: #d69e2e; }
        .stat-card.hoje .stat-icon { color: #e53e3e; }

        /* Admin Menu */
        .admin-menu {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            background: white;
            padding: 1rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .admin-menu a {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-menu a:hover {
            background: #ebf4ff;
            color: #2b6cb0;
        }

        .admin-menu a.active {
            background: #2b6cb0;
            color: white;
        }

        /* Sections */
        .section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-primary {
            background: #2b6cb0;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #2c5282;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(43, 108, 176, 0.3);
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
        }

        .btn-danger:hover {
            background: #c53030;
        }

        .btn-success {
            background: #38a169;
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
        }

        .btn-success:hover {
            background: #2f855a;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #edf2f7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f7fafc;
        }

        table th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #edf2f7;
        }

        table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f7fafc;
            vertical-align: middle;
        }

        table tbody tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-ativo { background: #f0fff4; color: #276749; }
        .status-inativo { background: #fff5f5; color: #9b2c2c; }
        .status-pendente { background: #fefcbf; color: #975a16; }

        .status-agendado { background: #ebf8ff; color: #2a69ac; }
        .status-confirmado { background: #f0fff4; color: #276749; }
        .status-realizado { background: #e2e8f0; color: #4a5568; }
        .status-cancelado { background: #fff5f5; color: #9b2c2c; }

        .foto-mini {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #e2e8f0;
        }

        .acoes {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .acoes .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-edit {
            background: #ebf8ff;
            color: #2b6cb0;
        }

        .btn-edit:hover {
            background: #bee3f8;
        }

        .btn-delete {
            background: #fff5f5;
            color: #e53e3e;
        }

        .btn-delete:hover {
            background: #feb2b2;
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
            max-width: 600px;
            width: 100%;
            padding: 2rem;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalIn 0.3s ease;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f7fafc;
        }

        .modal-header h2 {
            font-size: 1.25rem;
        }

        .modal-close {
            font-size: 1.5rem;
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.2s;
            background: none;
            border: none;
        }

        .modal-close:hover {
            color: #2d3748;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #e53e3e;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #edf2f7;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2b6cb0;
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Alert */
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

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-right {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .main {
                padding: 0 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .admin-menu {
                justify-content: center;
            }

            .admin-menu a {
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                font-size: 0.85rem;
            }

            table th,
            table td {
                padding: 0.5rem;
            }

            .modal-content {
                padding: 1.5rem;
                margin: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div>
                    <h1><i class="fas fa-shield-alt"></i> Painel Admin</h1>
                    <div class="subtitle">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></div>
                </div>
            </div>
            <div class="header-right">
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </div>
    </header>

    <main class="main">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card medicos">
                <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                <div class="stat-number"><?php echo $stats['medicos']; ?></div>
                <div class="stat-label">Médicos</div>
            </div>
            <div class="stat-card pacientes">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?php echo $stats['pacientes']; ?></div>
                <div class="stat-label">Pacientes</div>
            </div>
            <div class="stat-card agendamentos">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-number"><?php echo $stats['agendamentos']; ?></div>
                <div class="stat-label">Total Agendamentos</div>
            </div>
            <div class="stat-card hoje">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-number"><?php echo $stats['hoje']; ?></div>
                <div class="stat-label">Consultas Hoje</div>
            </div>
            <div class="stat-card" style="border-color: #d69e2e;">
                <div class="stat-icon" style="color: #d69e2e;"><i class="fas fa-clock"></i></div>
                <div class="stat-number" style="color: #d69e2e;"><?php echo $stats['pendentes']; ?></div>
                <div class="stat-label">Pendentes</div>
            </div>
        </div>

        <!-- Admin Menu -->
        <nav class="admin-menu">
            <a href="?acao=dashboard" class="<?php echo $acao == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="?acao=medicos" class="<?php echo $acao == 'medicos' ? 'active' : ''; ?>">
                <i class="fas fa-user-md"></i> Médicos
            </a>
            <a href="?acao=usuarios" class="<?php echo $acao == 'usuarios' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Usuários
            </a>
            <a href="?acao=agendamentos" class="<?php echo $acao == 'agendamentos' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> Agendamentos
            </a>
        </nav>

        <!-- Dashboard -->
        <div id="dashboard" class="section <?php echo $acao == 'dashboard' ? 'active' : ''; ?>">
            <div class="section-header">
                <h2><i class="fas fa-chart-pie"></i> Visão Geral</h2>
            </div>
            <div style="background: white; border-radius: 16px; padding: 2rem; text-align: center; border: 1px solid #edf2f7;">
                <i class="fas fa-clinic-medical" style="font-size: 4rem; color: #2b6cb0; margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem;">Clínica Saúde Total</h3>
                <p style="color: #718096;">Sistema de gerenciamento completo</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <div style="background: #f7fafc; padding: 1rem; border-radius: 12px;">
                        <strong style="color: #2b6cb0;"><?php echo $stats['medicos']; ?></strong>
                        <p style="font-size: 0.85rem; color: #718096;">Médicos</p>
                    </div>
                    <div style="background: #f7fafc; padding: 1rem; border-radius: 12px;">
                        <strong style="color: #38a169;"><?php echo $stats['pacientes']; ?></strong>
                        <p style="font-size: 0.85rem; color: #718096;">Pacientes</p>
                    </div>
                    <div style="background: #f7fafc; padding: 1rem; border-radius: 12px;">
                        <strong style="color: #d69e2e;"><?php echo $stats['agendamentos']; ?></strong>
                        <p style="font-size: 0.85rem; color: #718096;">Agendamentos</p>
                    </div>
                    <div style="background: #f7fafc; padding: 1rem; border-radius: 12px;">
                        <strong style="color: #e53e3e;"><?php echo $stats['hoje']; ?></strong>
                        <p style="font-size: 0.85rem; color: #718096;">Hoje</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Médicos -->
        <div id="medicos" class="section <?php echo $acao == 'medicos' ? 'active' : ''; ?>">
            <div class="section-header">
                <h2><i class="fas fa-user-md"></i> Gerenciar Médicos</h2>
                <button class="btn-primary" onclick="abrirModal('medico')">
                    <i class="fas fa-plus"></i> Novo Médico
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            <th>CRM</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($medicos)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: #718096;">
                                    Nenhum médico cadastrado
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($medicos as $medico): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($medico['foto'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($medico['foto']); ?>" class="foto-mini" alt="Foto">
                                        <?php else: ?>
                                            <div class="foto-mini" style="display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">👨‍⚕️</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($medico['nome']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($medico['especialidade']); ?></td>
                                    <td><?php echo htmlspecialchars($medico['crm']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $medico['status']; ?>">
                                            <?php echo ucfirst($medico['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="acoes">
                                            <button class="btn-sm btn-edit" onclick="editarMedico(<?php echo $medico['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-sm btn-delete" onclick="excluirMedico(<?php echo $medico['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Usuários -->
        <div id="usuarios" class="section <?php echo $acao == 'usuarios' ? 'active' : ''; ?>">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> Gerenciar Usuários</h2>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: #718096;">
                                    Nenhum usuário cadastrado
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($usuario['nome']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['telefone'] ?? '-'); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $usuario['tipo'] == 'admin' ? 'status-confirmado' : ($usuario['tipo'] == 'medico' ? 'status-agendado' : 'status-realizado'); ?>">
                                            <?php echo ucfirst($usuario['tipo']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $usuario['status']; ?>">
                                            <?php echo ucfirst($usuario['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="acoes">
                                            <button class="btn-sm btn-delete" onclick="excluirUsuario(<?php echo $usuario['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Agendamentos -->
        <div id="agendamentos" class="section <?php echo $acao == 'agendamentos' ? 'active' : ''; ?>">
            <div class="section-header">
                <h2><i class="fas fa-calendar-alt"></i> Todos os Agendamentos</h2>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Médico</th>
                            <th>Paciente</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Status</th>
                            <th>Observações</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($agendamentos)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #718096;">
                                    Nenhum agendamento encontrado
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($agendamentos as $agendamento): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($agendamento['medico_nome'] ?? 'N/A'); ?></strong><br>
                                        <small style="color: #718096;"><?php echo htmlspecialchars($agendamento['especialidade'] ?? ''); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($agendamento['paciente_nome'] ?? $agendamento['paciente_nome']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($agendamento['data_consulta'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($agendamento['hora_consulta'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $agendamento['status']; ?>">
                                            <?php echo ucfirst($agendamento['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($agendamento['observacoes'] ?? '-'); ?></td>
                                    <td>
                                        <div class="acoes">
                                            <?php if ($agendamento['status'] == 'agendado' || $agendamento['status'] == 'confirmado'): ?>
                                                <button class="btn-sm btn-success" onclick="confirmarAgendamento(<?php echo $agendamento['id']; ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn-sm btn-delete" onclick="cancelarAgendamento(<?php echo $agendamento['id']; ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Novo Médico</h2>
                <button class="modal-close" onclick="fecharModal()">&times;</button>
            </div>
            <div id="modal-body">
                <!-- Conteúdo do modal será carregado via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Funções do Modal
        function abrirModal(tipo, dados = null) {
            const modal = document.getElementById('modal');
            const title = document.getElementById('modal-title');
            const body = document.getElementById('modal-body');
            
            if (tipo === 'medico') {
                title.textContent = dados ? 'Editar Médico' : 'Novo Médico';
                body.innerHTML = `
                    <form id="form-medico" method="POST" action="processa_medico.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="${dados ? dados.id : ''}">
                        <input type="hidden" name="acao" value="${dados ? 'editar' : 'cadastrar'}">
                        
                        <div class="form-group">
                            <label>Nome completo <span class="required">*</span></label>
                            <input type="text" name="nome" value="${dados ? dados.nome : ''}" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Especialidade <span class="required">*</span></label>
                                <input type="text" name="especialidade" value="${dados ? dados.especialidade : ''}" required>
                            </div>
                            <div class="form-group">
                                <label>CRM <span class="required">*</span></label>
                                <input type="text" name="crm" value="${dados ? dados.crm : ''}" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea name="descricao" rows="3">${dados ? dados.descricao : ''}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="foto" accept="image/*">
                            ${dados && dados.foto ? `<p style="font-size: 0.85rem; color: #718096; margin-top: 0.25rem;">Foto atual: ${dados.foto}</p>` : ''}
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="ativo" ${dados && dados.status == 'ativo' ? 'selected' : ''}>Ativo</option>
                                <option value="inativo" ${dados && dados.status == 'inativo' ? 'selected' : ''}>Inativo</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fas fa-save"></i> ${dados ? 'Atualizar' : 'Cadastrar'}
                        </button>
                    </form>
                `;
            }
            
            modal.classList.add('active');
        }

        function fecharModal() {
            document.getElementById('modal').classList.remove('active');
        }

        // Fechar modal ao clicar fora
        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });

        // Funções para médicos
        function editarMedico(id) {
            fetch(`buscar_medico.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        abrirModal('medico', data.medico);
                    } else {
                        alert('Erro ao buscar dados do médico');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao buscar dados do médico');
                });
        }

        function excluirMedico(id) {
            if (confirm('Tem certeza que deseja excluir este médico?')) {
                window.location.href = `processa_medico.php?acao=excluir&id=${id}`;
            }
        }

        // Funções para usuários
        function excluirUsuario(id) {
            if (confirm('Tem certeza que deseja excluir este usuário?')) {
                window.location.href = `processa_usuario.php?acao=excluir&id=${id}`;
            }
        }

        // Funções para agendamentos
        function confirmarAgendamento(id) {
            if (confirm('Confirmar este agendamento?')) {
                window.location.href = `processa_agendamento_admin.php?acao=confirmar&id=${id}`;
            }
        }

        function cancelarAgendamento(id) {
            if (confirm('Cancelar este agendamento?')) {
                window.location.href = `processa_agendamento_admin.php?acao=cancelar&id=${id}`;
            }
        }

        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                fecharModal();
            }
        });

        // Verificar se há mensagem na URL
        const urlParams = new URLSearchParams(window.location.search);
        const mensagem = urlParams.get('mensagem');
        const tipo = urlParams.get('tipo');
        
        if (mensagem) {
            alert(mensagem);
            // Limpar URL
            const newUrl = window.location.pathname + '?acao=' + urlParams.get('acao');
            window.history.replaceState({}, document.title, newUrl);
        }
    </script>
</body>
</html>