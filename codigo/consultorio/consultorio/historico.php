<?php
require_once 'config.php';

// Verificar se usuário está logado - USANDO A FUNÇÃO DO CONFIG.PHP
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$filtro = $_GET['filtro'] ?? 'todos';

// Buscar agendamentos do usuário
$sql = "SELECT a.*, m.nome as medico_nome, m.especialidade, m.foto 
        FROM agendamentos a 
        LEFT JOIN medicos m ON a.medico_id = m.id 
        WHERE a.usuario_id = ?";

if ($filtro == 'ativos') {
    $sql .= " AND a.status IN ('agendado', 'confirmado') AND a.data_consulta >= CURDATE()";
} elseif ($filtro == 'realizados') {
    $sql .= " AND a.status = 'realizado'";
} elseif ($filtro == 'cancelados') {
    $sql .= " AND a.status = 'cancelado'";
}

$sql .= " ORDER BY a.data_consulta DESC, a.hora_consulta DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$agendamentos = [];
while ($row = $result->fetch_assoc()) {
    $agendamentos[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos - Clínica Saúde Total</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, #2b6cb0, #2c5282);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(43, 108, 176, 0.3);
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
            font-size: 0.9rem;
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

        .main {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
            flex: 1;
            width: 100%;
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
            font-size: 1.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .filters {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .filter-btn:hover {
            border-color: #2b6cb0;
            color: #2b6cb0;
        }

        .filter-btn.active {
            background: #2b6cb0;
            color: white;
            border-color: #2b6cb0;
        }

        .agendamentos-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .agendamento-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s;
        }

        .agendamento-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .agendamento-foto {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: #e2e8f0;
        }

        .agendamento-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .agendamento-foto .placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            background: #e2e8f0;
        }

        .agendamento-info {
            flex: 1;
        }

        .agendamento-info .medico-nome {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a202c;
        }

        .agendamento-info .medico-especialidade {
            color: #2b6cb0;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .agendamento-info .data-hora {
            color: #4a5568;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .agendamento-info .data-hora i {
            margin-right: 0.25rem;
            color: #718096;
        }

        .agendamento-info .observacoes {
            color: #718096;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .agendamento-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-agendado {
            background: #ebf8ff;
            color: #2a69ac;
        }

        .status-confirmado {
            background: #f0fff4;
            color: #276749;
        }

        .status-realizado {
            background: #e2e8f0;
            color: #4a5568;
        }

        .status-cancelado {
            background: #fff5f5;
            color: #9b2c2c;
        }

        .btn-cancelar {
            background: none;
            border: none;
            color: #e53e3e;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .btn-cancelar:hover {
            background: #fff5f5;
        }

        .sem-agendamentos {
            text-align: center;
            padding: 4rem 2rem;
            color: #4a5568;
        }

        .sem-agendamentos i {
            font-size: 3rem;
            color: #a0aec0;
            margin-bottom: 1rem;
            display: block;
        }

        .sem-agendamentos a {
            display: inline-block;
            margin-top: 1rem;
            color: #2b6cb0;
            text-decoration: none;
            font-weight: 500;
        }

        .sem-agendamentos a:hover {
            text-decoration: underline;
        }

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

            .agendamento-card {
                flex-direction: column;
                text-align: center;
            }

            .agendamento-status {
                align-items: center;
                width: 100%;
            }

            .section-header {
                flex-direction: column;
                align-items: stretch;
            }

            .filters {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .agendamento-card {
                padding: 1rem;
            }

            .agendamento-foto {
                width: 60px;
                height: 60px;
            }

            .filter-btn {
                padding: 0.35rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div>
                    <h1><i class="fas fa-calendar-check"></i> Meus Agendamentos</h1>
                    <div class="subtitle">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></div>
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
        <div class="section-header">
            <h2><i class="fas fa-list"></i> Histórico de Consultas</h2>
            <div class="filters">
                <a href="?filtro=todos" class="filter-btn <?php echo $filtro == 'todos' ? 'active' : ''; ?>">Todos</a>
                <a href="?filtro=ativos" class="filter-btn <?php echo $filtro == 'ativos' ? 'active' : ''; ?>">Ativos</a>
                <a href="?filtro=realizados" class="filter-btn <?php echo $filtro == 'realizados' ? 'active' : ''; ?>">Realizados</a>
                <a href="?filtro=cancelados" class="filter-btn <?php echo $filtro == 'cancelados' ? 'active' : ''; ?>">Cancelados</a>
            </div>
        </div>

        <?php if (empty($agendamentos)): ?>
            <div class="sem-agendamentos">
                <i class="fas fa-calendar-times"></i>
                <p style="font-size: 1.1rem; font-weight: 500;">Nenhum agendamento encontrado</p>
                <p style="color: #718096;">Você ainda não possui consultas agendadas ou com este filtro.</p>
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i> Voltar e agendar
                </a>
            </div>
        <?php else: ?>
            <div class="agendamentos-list">
                <?php foreach ($agendamentos as $agendamento): ?>
                    <div class="agendamento-card">
                        <div class="agendamento-foto">
                            <?php if (!empty($agendamento['foto'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($agendamento['foto']); ?>" alt="Foto do médico">
                            <?php else: ?>
                                <div class="placeholder">👨‍⚕️</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="agendamento-info">
                            <div class="medico-nome"><?php echo htmlspecialchars($agendamento['medico_nome'] ?? 'Médico não disponível'); ?></div>
                            <div class="medico-especialidade"><?php echo htmlspecialchars($agendamento['especialidade'] ?? ''); ?></div>
                            <div class="data-hora">
                                <i class="fas fa-calendar-alt"></i> 
                                <?php echo date('d/m/Y', strtotime($agendamento['data_consulta'])); ?> às 
                                <?php echo date('H:i', strtotime($agendamento['hora_consulta'])); ?>
                            </div>
                            <?php if (!empty($agendamento['observacoes'])): ?>
                                <div class="observacoes">
                                    <i class="fas fa-comment"></i> <?php echo htmlspecialchars($agendamento['observacoes']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="agendamento-status">
                            <span class="status-badge status-<?php echo $agendamento['status']; ?>">
                                <?php 
                                    $statusLabels = [
                                        'agendado' => '📋 Agendado',
                                        'confirmado' => '✅ Confirmado',
                                        'realizado' => '✔ Realizado',
                                        'cancelado' => '❌ Cancelado'
                                    ];
                                    echo $statusLabels[$agendamento['status']] ?? $agendamento['status'];
                                ?>
                            </span>
                            
                            <?php if ($agendamento['status'] == 'agendado' || $agendamento['status'] == 'confirmado'): ?>
                                <?php if (strtotime($agendamento['data_consulta']) >= strtotime(date('Y-m-d'))): ?>
                                    <form method="POST" action="cancelar_agendamento.php" style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja cancelar este agendamento?');">
                                        <input type="hidden" name="agendamento_id" value="<?php echo $agendamento['id']; ?>">
                                        <button type="submit" class="btn-cancelar">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>