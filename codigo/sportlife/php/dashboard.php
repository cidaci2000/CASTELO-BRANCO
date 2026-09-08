<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Verificar se está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Se for admin, redirecionar para admin dashboard
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Se for instrutor, redirecionar para instrutor dashboard
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'instrutor') {
    header("Location: ../instrutor/dashboard.php");
    exit();
}

// Buscar dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SportLife</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
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
        }

        .logout-btn:hover {
            color: #ef4444;
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .welcome-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: var(--text-muted);
        }

        .welcome-card .modalidade {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 24px;
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid rgba(79, 70, 229, 0.2);
            border-radius: 50px;
            color: #818cf8;
            font-weight: 600;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .feature-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.1);
        }

        .feature-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            body { padding: 15px; }
            .header { flex-direction: column; align-items: stretch; text-align: center; }
            .user-info { justify-content: center; flex-wrap: wrap; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏋️ Dashboard</h1>
            <div class="user-info">
                <div class="avatar"><?php echo substr($_SESSION['nome_usuario'], 0, 1); ?></div>
                <span><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></span>
                <span class="badge">Usuário</span>
                <a href="logout.php" class="logout-btn" onclick="return confirm('Deseja sair?')">Sair</a>
            </div>
        </div>

        <div class="welcome-card">
            <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>! 👋</h2>
            <p>Gerencie sua jornada esportiva com a SportLife</p>
            <div class="modalidade">🏆 <?php echo htmlspecialchars($_SESSION['modalidade']); ?></div>
        </div>

        <div class="features-grid">
            <a href="#" class="feature-card">
                <div class="icon">📊</div>
                <h3>Performance</h3>
                <p>Monitore seus resultados e evolução</p>
            </a>
            <a href="../agenda.html" class="feature-card">
                <div class="icon">📅</div>
                <h3>Agenda</h3>
                <p>Gerencie seus treinos e compromissos</p>
            </a>
            <a href="../planos.html" class="feature-card">
                <div class="icon">💪</div>
                <h3>Planos</h3>
                <p>Escolha o melhor plano para você</p>
            </a>
            <a href="#" class="feature-card">
                <div class="icon">📈</div>
                <h3>Estatísticas</h3>
                <p>Veja seu progresso em gráficos</p>
            </a>
        </div>
    </div>
</body>
</html>