<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

// Verificar se o usuário está logado e é instrutor
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'instrutor') {
    header("Location: login.php");
    exit();
}

// Buscar dados do instrutor
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$instrutor = $result->fetch_assoc();
$stmt->close();

// Buscar estatísticas
$stats = [];

// Total de alunos (usuários comuns)
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'usuario'");
$stats['total_alunos'] = $result->fetch_assoc()['total'];

// Total de instrutores
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'instrutor'");
$stats['total_instrutores'] = $result->fetch_assoc()['total'];

// Total de treinos criados (exemplo - ajuste conforme sua estrutura)
$result = $conn->query("SELECT COUNT(*) as total FROM treinos"); // Ajuste para sua tabela de treinos
$stats['total_treinos'] = $result->fetch_assoc()['total'] ?? 0;

// Próximas aulas (exemplo - ajuste conforme sua estrutura)
$result = $conn->query("SELECT COUNT(*) as total FROM aulas WHERE data_aula >= CURDATE() AND instrutor_id = " . $_SESSION['usuario_id']);
$stats['proximas_aulas'] = $result->fetch_assoc()['total'] ?? 0;

// Últimos alunos cadastrados
$result = $conn->query("SELECT id, nome_completo, email, modalidade, tipo_usuario, data_criacao FROM usuarios WHERE tipo_usuario = 'usuario' ORDER BY data_criacao DESC LIMIT 10");
$ultimos_alunos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SportLife Instrutor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0891b2;
            --primary-glow: rgba(8, 145, 178, 0.4);
            --bg-dark: #06060a;
            --card-bg: rgba(10, 10, 15, 0.7);
            --card-border: rgba(255, 255, 255, 0.06);
            --text-main: #f8fafc;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            background: linear-gradient(180deg, #ffffff 20%, #67e8f9 65%, #0891b2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--card-bg);
            padding: 10px 20px;
            border-radius: 12px;
            border: 1px solid var(--card-border);
        }

        .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .user-info .name {
            font-weight: 600;
        }

        .user-info .badge {
            background: rgba(8, 145, 178, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #67e8f9;
            border: 1px solid rgba(8, 145, 178, 0.3);
        }

        .logout-btn {
            color: var(--text-muted);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .logout-btn:hover {
            color: #ef4444;
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--primary);
            box-shadow: 0 0 30px var(--primary-glow);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(180deg, #ffffff, #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 5px;
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .table-container {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 24px;
            overflow-x: auto;
        }

        .table-container h2 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--text-main);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            text-align: left;
            padding: 12px 16px;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--card-border);
        }

        table td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 0.9rem;
        }

        table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .badge-tipo {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-instrutor {
            background: rgba(8, 145, 178, 0.2);
            color: #67e8f9;
            border: 1px solid rgba(8, 145, 178, 0.3);
        }

        .badge-usuario {
            background: rgba(79, 70, 229, 0.2);
            color: #818cf8;
            border: 1px solid rgba(79, 70, 229, 0.3);
        }

        .badge-modalidade {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.7rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid var(--card-border);
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-action:hover {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-action.ver {
            color: #818cf8;
        }

        .btn-action.ver:hover {
            background: rgba(79, 70, 229, 0.2);
            border-color: #818cf8;
        }

        @media (max-width: 768px) {
            body { padding: 15px; }
            .header { flex-direction: column; align-items: stretch; text-align: center; }
            .user-info { justify-content: center; flex-wrap: wrap; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏋️ Painel Instrutor</h1>
            <div class="user-info">
                <div class="avatar"><?php echo substr($instrutor['nome_completo'], 0, 1); ?></div>
                <span class="name"><?php echo htmlspecialchars($instrutor['nome_completo']); ?></span>
                <span class="badge">Instrutor</span>
                <span class="badge-modalidade"><?php echo $instrutor['modalidade']; ?></span>
                <a href="logout.php" class="logout-btn" onclick="return confirm('Deseja realmente sair?')">Sair</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?php echo $stats['total_alunos']; ?></div>
                <div class="stat-label">Total de Alunos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏋️</div>
                <div class="stat-number"><?php echo $stats['total_instrutores']; ?></div>
                <div class="stat-label">Instrutores</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-number"><?php echo $stats['total_treinos']; ?></div>
                <div class="stat-label">Total de Treinos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-number"><?php echo $stats['proximas_aulas']; ?></div>
                <div class="stat-label">Próximas Aulas</div>
            </div>
        </div>

        <div class="table-container">
            <h2>📋 Últimos Alunos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Modalidade</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimos_alunos as $aluno): ?>
                    <tr>
                        <td>#<?php echo $aluno['id']; ?></td>
                        <td><?php echo htmlspecialchars($aluno['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                        <td><span class="badge-modalidade"><?php echo $aluno['modalidade']; ?></span></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($aluno['data_criacao'])); ?></td>
                        <td>
                            <div class="actions">
                                <a href="#" class="btn-action ver">Ver</a>
                                <a href="#" class="btn-action">Treino</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>