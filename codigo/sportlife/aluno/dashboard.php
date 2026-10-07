<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
exigir_login(['usuario', 'admin']);

$user     = usuario_logado();
$aluno_id = $user['id'];

// Próximos 3 treinos confirmados
$stmt = $pdo->prepare("
    SELECT a.titulo, a.modalidade, a.data_aula, a.duracao, a.local,
           u.nome_completo AS instrutor
    FROM inscricoes_aulas i
    JOIN aulas a ON a.id = i.aula_id
    LEFT JOIN usuarios u ON u.id = a.instrutor_id
    WHERE i.aluno_id = ? AND i.status = 'confirmada' AND a.data_aula >= NOW()
    ORDER BY a.data_aula ASC
    LIMIT 3
");
$stmt->execute([$aluno_id]);
$proximos = $stmt->fetchAll();

// Contador do mês
$stmt = $pdo->prepare("
    SELECT COUNT(*) AS total FROM inscricoes_aulas
    WHERE aluno_id = ? AND status = 'confirmada'
      AND MONTH(data_inscricao) = MONTH(CURDATE())
      AND YEAR(data_inscricao)  = YEAR(CURDATE())
");
$stmt->execute([$aluno_id]);
$treinos_mes = (int)$stmt->fetch()['total'];

$cores = [
    'Musculação' => '#4f46e5', 'Funcional' => '#22c55e',
    'CrossFit'   => '#ef4444', 'Yoga'      => '#eab308', 'Luta' => '#f97316',
];
function cor_mod($m, $map) { return $map[$m] ?? '#818cf8'; }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Painel — SportLife</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Bebas+Neue&family=Syncopate:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #4f46e5;
            --deep-purple: #1e1b4b;
            --neon-glow: #818cf8;
            --bg-black: #0a0a0f;
            --card-gray: rgba(255, 255, 255, 0.03);
            --border: rgba(79, 70, 229, 0.15);
            --text-muted: #94a3b8;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg-black); color: white; line-height: 1.6; min-height: 100vh; }
        .glow { position: fixed; width: 400px; height: 400px; background: #3730a3; filter: blur(150px); border-radius: 50%; opacity: 0.12; z-index: -1; }
        .glow.top { top: -100px; left: 50%; transform: translateX(-50%); }
        .glow.bottom { bottom: -100px; right: -100px; }

        header {
            padding: 20px 5%; display: flex; justify-content: space-between; align-items: center;
            background: rgba(10,10,15,0.6); backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100;
        }
        .logo {
            font-family: 'Syncopate', sans-serif; font-size: 1.3rem; letter-spacing: 4px;
            background: linear-gradient(to right, #e2e8f0, var(--primary-purple));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-decoration: none;
        }
        .user-area { display: flex; align-items: center; gap: 15px; }
        .user-greeting { color: var(--text-muted); font-size: 0.8rem; letter-spacing: 1px; }
        .user-greeting strong { color: white; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .badge-usuario { background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid #22c55e; }
        .btn-logout {
            background: transparent; color: rgba(255,255,255,0.7);
            border: 1px solid var(--border); padding: 8px 18px; border-radius: 50px;
            font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;
            transition: 0.3s; cursor: pointer; text-decoration: none;
        }
        .btn-logout:hover { color: #ef4444; border-color: #ef4444; }

        .container { max-width: 1100px; margin: 0 auto; padding: 50px 5%; }

        .hero-aluno {
            background: linear-gradient(135deg, rgba(79,70,229,0.15), rgba(30,27,75,0.3));
            border: 1px solid var(--border); border-radius: 20px;
            padding: 50px 40px; margin-bottom: 40px; position: relative; overflow: hidden;
            display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;
        }
        .hero-aluno::after {
            content: ''; position: absolute; right: -50px; top: -50px;
            width: 300px; height: 300px; background: #4f46e5;
            filter: blur(100px); opacity: 0.2; border-radius: 50%;
        }
        .hero-aluno h1 {
            font-family: 'Bebas Neue', sans-serif; font-size: clamp(2.5rem, 6vw, 4rem);
            letter-spacing: 2px; text-transform: uppercase; line-height: 1; position: relative; z-index: 1;
        }
        .hero-aluno h1 span { color: var(--primary-purple); }
        .hero-aluno p { color: var(--text-muted); margin-top: 10px; position: relative; z-index: 1; letter-spacing: 1px; font-size: 0.9rem; }
        .btn-agenda-hero {
            position: relative; z-index: 1;
            background: var(--primary-purple); color: white; text-decoration: none;
            padding: 14px 28px; border-radius: 50px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; font-size: 0.7rem;
            transition: 0.4s; white-space: nowrap;
        }
        .btn-agenda-hero:hover { background: white; color: black; transform: translateY(-3px); }

        .section-title {
            font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem;
            letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px;
            border-left: 3px solid var(--primary-purple); padding-left: 15px;
        }

        .quick-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 50px; }
        .quick-card {
            background: var(--card-gray); border: 1px solid var(--border);
            padding: 25px; border-radius: 16px; text-align: center;
            text-decoration: none; color: white; transition: 0.4s;
        }
        .quick-card:hover { border-color: var(--primary-purple); transform: translateY(-5px); background: rgba(79,70,229,0.05); }
        .quick-card .icon { font-size: 2rem; margin-bottom: 10px; }
        .quick-card .label { font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }

        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 50px; }
        .stat-card {
            background: var(--card-gray); border: 1px solid var(--border);
            padding: 25px; border-radius: 16px;
        }
        .stat-label { color: var(--text-muted); font-size: 0.65rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
        .stat-value { font-family: 'Bebas Neue', sans-serif; font-size: 2.5rem; color: var(--neon-glow); line-height: 1; margin-top: 5px; }

        .treino-lista { background: var(--card-gray); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .treino-item {
            padding: 22px 25px; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center; gap: 15px;
        }
        .treino-item:last-child { border-bottom: none; }
        .treino-item strong { display: block; font-size: 0.95rem; }
        .treino-item span { color: var(--text-muted); font-size: 0.75rem; }
        .tag { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; background: rgba(34,197,94,0.15); color: #86efac; }
        .tag.mod { background: rgba(79,70,229,0.15); color: var(--neon-glow); border: 1px solid var(--border); }
        .vazio { padding: 35px; text-align: center; color: var(--text-muted); font-size: 0.85rem; }
        .vazio a { color: var(--neon-glow); text-decoration: none; font-weight: 700; }
        .vazio a:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            header { flex-direction: column; gap: 12px; text-align: center; }
            .hero-aluno { padding: 35px 25px; }
            .treino-item { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
<div class="glow top"></div>
<div class="glow bottom"></div>

<header>
    <a href="dashboard.php" class="logo">SPORTLIFE</a>
    <div class="user-area">
        <span class="user-greeting">Olá, <strong><?= htmlspecialchars($user['nome']) ?></strong></span>
        <span class="badge badge-usuario">Aluno</span>
        <a href="../logout.php" class="btn-logout">Sair</a>
    </div>
</header>

<div class="container">

    <div class="hero-aluno">
        <div>
            <h1>Bem-vindo, <span><?= htmlspecialchars(explode(' ', $user['nome'])[0]) ?></span></h1>
            <p>Pronto para o treino de hoje? Bora com tudo! 💪</p>
        </div>
        <a href="agenda.php" class="btn-agenda-hero">📅 Ver Agenda</a>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Treinos no mês</div>
            <div class="stat-value">18</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Sequência atual</div>
            <div class="stat-value">7 dias</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Modalidade</div>
            <div class="stat-value" style="font-size:1.6rem"><?= htmlspecialchars($user['modalidade'] ?? 'N/A') ?></div>
        </div>
    </div>

    <h2 class="section-title">Acesso rápido</h2>
    <div class="quick-grid">
        <a href="agenda.php" class="quick-card"><div class="icon">📅</div><div class="label">Meus Treinos</div></a>
        <a href="#" class="quick-card"><div class="icon">📈</div><div class="label">Progresso</div></a>
        <a href="#" class="quick-card"><div class="icon">🥗</div><div class="label">Dieta</div></a>
        <a href="#" class="quick-card"><div class="icon">👤</div><div class="label">Meu Perfil</div></a>
    </div>

    <h2 class="section-title">Próximos treinos</h2>
    <div class="treino-lista">
        <?php if (empty($proximos)): ?>
            <div class="vazio">
                Nenhum treino agendado ainda.<br>
                <a href="agenda.php">→ Agendar agora</a>
            </div>
        <?php else: ?>
            <?php foreach ($proximos as $p):
                $cor = cor_mod($p['modalidade'], $cores);
                $dt  = strtotime($p['data_aula']);
                $hoje = date('Y-m-d');
                $amanha = date('Y-m-d', strtotime('+1 day'));
                $dia = date('Y-m-d', $dt);
                if ($dia === $hoje)        $quando = 'Hoje';
                elseif ($dia === $amanha)  $quando = 'Amanhã';
                else                       $quando = date('d/m', $dt);
            ?>
                <div class="treino-item">
                    <div>
                        <strong style="color: <?= $cor ?>">
                            <?= htmlspecialchars($p['modalidade']) ?> — <?= htmlspecialchars($p['titulo']) ?>
                        </strong>
                        <span>
                            <?= $quando ?> · <?= date('H:i', $dt) ?> · 
                            <?= (int)$p['duracao'] ?> min · 
                            <?= htmlspecialchars($p['local'] ?? '—') ?> · 
                            🧑‍🏫 <?= htmlspecialchars($p['instrutor'] ?? '—') ?>
                        </span>
                    </div>
                    <span class="tag">Confirmado</span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
</body>
</html>