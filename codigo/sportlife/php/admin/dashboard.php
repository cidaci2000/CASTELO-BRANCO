<?php
session_start();
require_once __DIR__ . '/../../config/database.php'; // CORRIGIDO

// Verificar se o usuário está logado e é admin
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Buscar dados do admin
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

// Buscar estatísticas
$stats = [];

// Total de usuários
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
$stats['total_usuarios'] = $result->fetch_assoc()['total'];

// Total de admins
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'admin'");
$stats['total_admins'] = $result->fetch_assoc()['total'];

// Total de instrutores
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'instrutor'");
$stats['total_instrutores'] = $result->fetch_assoc()['total'];

// Total de usuários comuns
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'usuario'");
$stats['total_usuarios_comuns'] = $result->fetch_assoc()['total'];

// Últimos usuários cadastrados
$result = $conn->query("SELECT id, nome_completo, email, modalidade, tipo_usuario, data_criacao FROM usuarios ORDER BY data_criacao DESC LIMIT 10");
$ultimos_usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SportLife Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.4);
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
            background: linear-gradient(180deg, #ffffff 20%, #818cf8 65%, #4f46e5 100%);
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
            background: rgba(79, 70, 229, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #818cf8;
            border: 1px solid rgba(79, 70, 229, 0.3);
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
            background: linear-gradient(180deg, #ffffff, #818cf8);
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

        .badge-admin {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-instrutor {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
            border: 1px solid rgba(34, 197, 94, 0.3);
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
            <h1>⚡ Painel Admin</h1>
            <div class="user-info">
                <div class="avatar"><?php echo substr($admin['nome_completo'], 0, 1); ?></div>
                <span class="name"><?php echo htmlspecialchars($admin['nome_completo']); ?></span>
                <span class="badge">Administrador</span>
                <a href="logout.php" class="logout-btn" onclick="return confirm('Deseja realmente sair?')">Sair</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number"><?php echo $stats['total_usuarios']; ?></div>
                <div class="stat-label">Total de Usuários</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👤</div>
                <div class="stat-number"><?php echo $stats['total_usuarios_comuns']; ?></div>
                <div class="stat-label">Usuários Comuns</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏋️</div>
                <div class="stat-number"><?php echo $stats['total_instrutores']; ?></div>
                <div class="stat-label">Instrutores</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⚙️</div>
                <div class="stat-number"><?php echo $stats['total_admins']; ?></div>
                <div class="stat-label">Administradores</div>
            </div>
        </div>

        <div class="table-container">
            <h2>📋 Últimos Usuários Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Modalidade</th>
                        <th>Tipo</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimos_usuarios as $usuario): ?>
                    <tr>
                        <td>#<?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><span class="badge-modalidade"><?php echo $usuario['modalidade']; ?></span></td>
                        <td>
                            <span class="badge-tipo badge-<?php echo $usuario['tipo_usuario']; ?>">
                                <?php 
                                    $tipos = [
                                        'admin' => 'Admin',
                                        'instrutor' => 'Instrutor',
                                        'usuario' => 'Usuário'
                                    ];
                                    echo $tipos[$usuario['tipo_usuario']] ?? $usuario['tipo_usuario'];
                                ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($usuario['data_criacao'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>