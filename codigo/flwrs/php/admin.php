<?php
// admin.php - Página real do admin
session_start();

// Verificar se está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}

// Buscar dados do admin
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar total de usuários
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

// Buscar últimos usuários cadastrados
$stmt = $pdo->query("SELECT id, nome_completo, email FROM usuarios ORDER BY id DESC LIMIT 5");
$usuariosRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flwrs · admin</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #fcf8f5;
            margin: 0;
            padding: 2rem;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 32px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.02);
        }
        .header h1 {
            margin: 0;
            font-weight: 400;
            color: #2d2825;
        }
        .header .admin-badge {
            background: #2d2825;
            color: white;
            padding: 0.3rem 1.2rem;
            border-radius: 50px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn-logout {
            background: #e94e77;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background: #d43f68;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 32px;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.02);
        }
        .card h2 {
            font-weight: 400;
            color: #2d2825;
            margin-top: 0;
            font-size: 1.2rem;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .stat-item {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.02);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 300;
            color: #e94e77;
        }
        .stat-label {
            color: #a8958f;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            color: #6d6560;
            font-weight: 500;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #f0ece8;
        }
        td {
            padding: 0.8rem 0;
            border-bottom: 1px solid #f5f0ed;
            color: #3d3835;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .welcome {
            font-size: 1.1rem;
            color: #6d6560;
        }
        .welcome strong {
            color: #2d2825;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>🛠️ Painel Admin</h1>
                <span class="admin-badge">Administrador</span>
            </div>
            <form method="POST" action="logout.php">
                <button type="submit" class="btn-logout">Sair</button>
            </form>
        </div>
        
        <div class="card">
            <p class="welcome">Olá, <strong><?php echo htmlspecialchars($admin['nome_completo']); ?></strong>! Bem-vindo ao painel administrativo.</p>
        </div>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number"><?php echo $totalUsuarios; ?></div>
                <div class="stat-label">Total de Usuários</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo count($usuariosRecentes); ?></div>
                <div class="stat-label">Últimos Cadastros</div>
            </div>
        </div>
        
        <div class="card">
            <h2>📋 Últimos Usuários Cadastrados</h2>
            <?php if (count($usuariosRecentes) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuariosRecentes as $usuario): ?>
                            <tr>
                                <td>#<?php echo $usuario['id']; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nome_completo']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($usuario['data_cadastro'] ?? 'now')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #a8958f;">Nenhum usuário cadastrado ainda.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>