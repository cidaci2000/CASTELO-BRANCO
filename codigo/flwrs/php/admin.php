<?php
// ============================================
// admin.php — Painel administrativo flwrs
// ============================================
require_once __DIR__ . '/../includes/functions.php';

// Verifica se está logado e é admin
if (!isLogged()) {
    setFlash('erro', 'Faça login para acessar o painel.');
    redirect('login.php');
}
if (!isAdmin()) {
    setFlash('erro', 'Acesso restrito a administradores.');
    redirect('home.php');
}

// ============================================
// COLETA DE DADOS
// ============================================
$pdo = db();

// Dados do admin logado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $_SESSION['usuario_id']]);
$admin = $stmt->fetch();

// Estatísticas gerais
$totalUsuarios  = (int) $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalProdutos  = (int) $pdo->query("SELECT COUNT(*) FROM produtos WHERE status='ativo'")->fetchColumn();
$totalPedidos   = (int) $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$receita        = (float) $pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE status != 'cancelado'")->fetchColumn();

// Contadores adicionais
$usuariosAtivos = (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE status='ativo'")->fetchColumn();
$pedidosPendentes = (int) $pdo->query("SELECT COUNT(*) FROM pedidos WHERE status='pendente'")->fetchColumn();

// Últimos 5 usuários cadastrados
$usuariosRecentes = $pdo->query("
    SELECT id, nome_completo, email, tipo, status, created_at
    FROM usuarios
    ORDER BY id DESC
    LIMIT 5
")->fetchAll();

// Últimos 5 pedidos
$pedidosRecentes = $pdo->query("
    SELECT p.id, p.total, p.status, p.created_at, u.nome_completo AS cliente
    FROM pedidos p
    LEFT JOIN usuarios u ON u.id = p.usuario_id
    ORDER BY p.id DESC
    LIMIT 5
")->fetchAll();

// Produtos com estoque crítico (se você tiver coluna estoque; senão ignora)
// Como sua tabela produtos não tem "estoque", pulamos isso.

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>flwrs · painel admin</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
/* ===== VARIÁVEIS ===== */
:root {
    --coral: #e8857d;
    --pink: #f4a8a0;
    --light-pink: #f5d5d0;
    --soft: #fdf6f5;
    --dark: #2d2825;
    --gray: #6d6560;
    --muted: #a8958f;
    --border: #f0ece8;
    --shadow: 0 10px 30px -10px rgba(0,0,0,.05);
    --radius: 32px;
    --transition: .25s ease;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: var(--soft);
    color: var(--dark);
    line-height: 1.6;
    padding: 2rem 1rem;
    min-height: 100vh;
}

.container { max-width: 1100px; margin: 0 auto; }

/* ===== NOTIFICAÇÕES ===== */
.notificacao {
    position: fixed; top: 80px; right: 20px;
    padding: 1rem 1.5rem; border-radius: 10px;
    color: #fff; font-weight: 600; z-index: 2000;
    box-shadow: var(--shadow);
    animation: slideIn .3s ease-out;
    max-width: 400px;
}
.notificacao.sucesso { background: linear-gradient(135deg, #5FA86D, #4A8A58); }
.notificacao.erro    { background: linear-gradient(135deg, #D64545, #B83535); }
@keyframes slideIn {
    from { transform: translateX(120%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}

/* ===== HEADER ===== */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 1.5rem 2rem;
    border-radius: var(--radius);
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    gap: 1rem;
    flex-wrap: wrap;
}
.header-left { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.header h1 {
    margin: 0;
    font-weight: 400;
    font-size: 1.5rem;
    color: var(--dark);
}
.admin-badge {
    background: var(--dark);
    color: #fff;
    padding: .3rem 1rem;
    border-radius: 50px;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 600;
}
.header-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

/* ===== BOTÕES ===== */
.btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .6rem 1.3rem; border-radius: 50px;
    border: none; cursor: pointer;
    font-size: .85rem; font-weight: 600;
    font-family: inherit; text-decoration: none;
    transition: transform var(--transition), box-shadow var(--transition);
    line-height: 1;
}
.btn:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.btn-primary {
    background: linear-gradient(135deg, var(--coral), var(--pink));
    color: #fff;
}
.btn-dark { background: var(--dark); color: #fff; }
.btn-outline {
    background: #fff; color: var(--dark);
    border: 2px solid var(--light-pink);
}
.btn-danger {
    background: linear-gradient(135deg, #D64545, #B83535);
    color: #fff;
}
.btn-sm { padding: .4rem .9rem; font-size: .8rem; }

/* ===== CARDS ===== */
.card {
    background: #fff;
    padding: 2rem;
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
}
.card h2 {
    font-weight: 400;
    color: var(--dark);
    margin: 0 0 1.5rem;
    font-size: 1.15rem;
    display: flex; align-items: center; gap: .5rem;
}

/* ===== WELCOME ===== */
.welcome { font-size: 1.05rem; color: var(--gray); }
.welcome strong { color: var(--dark); }

/* ===== STATS ===== */
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
.stat-item {
    background: #fff;
    padding: 1.5rem 1.25rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: var(--shadow);
    border-left: 4px solid var(--coral);
    transition: transform var(--transition);
}
.stat-item:hover { transform: translateY(-3px); }
.stat-number {
    font-size: 2.2rem;
    font-weight: 300;
    color: var(--coral);
    line-height: 1;
    margin-bottom: .5rem;
}
.stat-label {
    color: var(--muted);
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .05em;
}

/* ===== MENU RÁPIDO ===== */
.quick-menu {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.quick-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 20px;
    text-decoration: none;
    color: var(--dark);
    box-shadow: var(--shadow);
    transition: transform var(--transition);
    display: flex;
    align-items: center;
    gap: 1rem;
}
.quick-card:hover { transform: translateY(-3px); }
.quick-card .icon {
    font-size: 1.8rem;
    width: 50px; height: 50px;
    display: flex; align-items: center; justify-content: center;
    background: var(--soft);
    border-radius: 16px;
}
.quick-card .info strong {
    display: block;
    font-weight: 600;
    font-size: .95rem;
    margin-bottom: .15rem;
}
.quick-card .info span {
    color: var(--muted);
    font-size: .8rem;
}

/* ===== TABELAS ===== */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th {
    text-align: left;
    color: var(--gray);
    font-weight: 500;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 0 .75rem .75rem;
    border-bottom: 1px solid var(--border);
}
td {
    padding: .85rem .75rem;
    border-bottom: 1px solid var(--border);
    color: var(--dark);
    font-size: .9rem;
    vertical-align: middle;
}
tbody tr { transition: background var(--transition); }
tbody tr:hover { background: var(--soft); }
tbody tr:last-child td { border-bottom: none; }

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    padding: .25rem .7rem;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 600;
    text-transform: capitalize;
    background: #eee;
    color: #555;
}
.badge-admin    { background: #2d2825; color: #fff; }
.badge-usuario  { background: #f5f0ed; color: var(--gray); }
.badge-ativo    { background: #d4edda; color: #2c6b3a; }
.badge-inativo  { background: #f0ece8; color: var(--gray); }
.badge-bloqueado{ background: #f8d7da; color: #a12b33; }
.badge-pendente { background: #fff3cd; color: #856404; }
.badge-pago     { background: #d1ecf1; color: #0c5460; }
.badge-enviado  { background: #cce5ff; color: #004085; }
.badge-entregue { background: #d4edda; color: #2c6b3a; }
.badge-cancelado{ background: #f8d7da; color: #a12b33; }

/* ===== GRID DE PAINÉIS ===== */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

/* ===== EMPTY ===== */
.empty {
    text-align: center;
    padding: 2rem;
    color: var(--muted);
}
.empty .icon { font-size: 2.5rem; display: block; margin-bottom: .5rem; }

/* ===== RESPONSIVO ===== */
@media (max-width: 900px) {
    .grid-2 { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    body { padding: 1rem .5rem; }
    .header { padding: 1.25rem; }
    .header h1 { font-size: 1.2rem; }
    .card { padding: 1.5rem 1.25rem; }
    .stats { grid-template-columns: 1fr 1fr; }
    .stat-number { font-size: 1.8rem; }
    .notificacao { left: 10px; right: 10px; max-width: none; }
    .quick-card { padding: 1.25rem; }
}
</style>
</head>
<body>

<?php if ($flash): ?>
    <div class="notificacao <?= e($flash['tipo']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
            <h1>🛠️ Painel Admin</h1>
            <span class="admin-badge">Administrador</span>
        </div>
        <div class="header-actions">
            <a href="home.php" class="btn btn-outline">
                <span class="material-symbols-outlined" style="font-size:1rem;">storefront</span>
                Ver site
            </a>
            <form method="POST" action="logout.php" style="display:inline;">
                <button type="submit" class="btn btn-danger">
                    <span class="material-symbols-outlined" style="font-size:1rem;">logout</span>
                    Sair
                </button>
            </form>
        </div>
    </div>

    <!-- BOAS-VINDAS -->
    <div class="card">
        <p class="welcome">
            Olá, <strong><?= e($admin['nome_completo']) ?></strong>! Bem-vindo ao painel administrativo.
            <?php if ($admin['created_at']): ?>
                <br><small style="color:var(--muted);">
                    Sua conta foi criada em <?= date('d/m/Y', strtotime($admin['created_at'])) ?>.
                </small>
            <?php endif; ?>
        </p>
    </div>

    <!-- ESTATÍSTICAS -->
    <div class="stats">
        <div class="stat-item">
            <div class="stat-number"><?= $totalUsuarios ?></div>
            <div class="stat-label">Usuários</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= $totalProdutos ?></div>
            <div class="stat-label">Produtos ativos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number"><?= $totalPedidos ?></div>
            <div class="stat-label">Pedidos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">R$ <?= number_format($receita, 0, ',', '.') ?></div>
            <div class="stat-label">Receita</div>
        </div>
    </div>

    <!-- MENU RÁPIDO -->
    <div class="quick-menu">
        <a href="admin_produtos.php" class="quick-card">
            <div class="icon">🌸</div>
            <div class="info">
                <strong>Gerenciar Produtos</strong>
                <span>Criar, editar e excluir flores</span>
            </div>
        </a>
        <a href="admin_pedidos.php" class="quick-card">
            <div class="icon">🧾</div>
            <div class="info">
                <strong>Pedidos</strong>
                <span><?= $pedidosPendentes ?> pendente<?= $pedidosPendentes === 1 ? '' : 's' ?></span>
            </div>
        </a>
        <a href="admin_usuarios.php" class="quick-card">
            <div class="icon">👥</div>
            <div class="info">
                <strong>Usuários</strong>
                <span><?= $usuariosAtivos ?> ativo<?= $usuariosAtivos === 1 ? '' : 's' ?></span>
            </div>
        </a>
       
    </div>

    <!-- GRID: ÚLTIMOS USUÁRIOS + ÚLTIMOS PEDIDOS -->
    <div class="grid-2">

        <!-- USUÁRIOS RECENTES -->
        <div class="card">
            <h2>👥 Últimos usuários</h2>
            <?php if (empty($usuariosRecentes)): ?>
                <div class="empty">
                    <span class="icon">👤</span>
                    <p>Nenhum usuário cadastrado ainda.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuariosRecentes as $u): ?>
                                <tr>
                                    <td>#<?= $u['id'] ?></td>
                                    <td>
                                        <strong><?= e($u['nome_completo']) ?></strong><br>
                                        <small style="color:var(--muted);"><?= e($u['email']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= e($u['tipo']) ?>">
                                            <?= e($u['tipo']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:1rem; text-align:right;">
                    <a href="admin_usuarios.php" class="btn btn-outline btn-sm">ver todos →</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- PEDIDOS RECENTES -->
        <div class="card">
            <h2>🧾 Últimos pedidos</h2>
            <?php if (empty($pedidosRecentes)): ?>
                <div class="empty">
                    <span class="icon">📭</span>
                    <p>Nenhum pedido ainda.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidosRecentes as $p): ?>
                                <tr>
                                    <td><strong>#<?= $p['id'] ?></strong></td>
                                    <td><?= e($p['cliente'] ?? 'Visitante') ?></td>
                                    <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                                    <td>
                                        <span class="badge badge-<?= e($p['status']) ?>">
                                            <?= e($p['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:1rem; text-align:right;">
                    <a href="admin_pedidos.php" class="btn btn-outline btn-sm">ver todos →</a>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
    // Auto-esconde notificação
    setTimeout(() => {
        const n = document.querySelector('.notificacao');
        if (n) {
            n.style.transition = 'opacity .4s, transform .4s';
            n.style.opacity = '0';
            n.style.transform = 'translateX(120%)';
            setTimeout(() => n.remove(), 500);
        }
    }, 4000);
</script>

</body>
</html>