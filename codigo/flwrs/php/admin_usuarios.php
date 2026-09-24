<?php
// ============================================
// admin_logs.php — Logs de atividades e logins
// ============================================
require_once __DIR__ . '/../includes/functions.php';

if (!isLogged() || !isAdmin()) {
    setFlash('erro', 'Acesso restrito a administradores.');
    redirect('login.php');
}

$pdo = db();

/* Limpar logs antigos (mais de 30 dias) */
if (($_GET['acao'] ?? '') === 'limpar_antigos') {
    $pdo->exec("DELETE FROM logs_atividades WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    $pdo->exec("DELETE FROM historico_login WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    setFlash('sucesso', 'Logs com mais de 30 dias foram removidos.');
    redirect('admin_logs.php');
}

/* Limpar tudo */
if (($_GET['acao'] ?? '') === 'limpar_tudo') {
    $pdo->exec("DELETE FROM logs_atividades");
    $pdo->exec("DELETE FROM historico_login");
    setFlash('sucesso', 'Todos os logs foram removidos.');
    redirect('admin_logs.php');
}

/* Aba ativa */
$aba = $_GET['aba'] ?? 'atividades';
if (!in_array($aba, ['atividades', 'logins'])) $aba = 'atividades';

/* Filtros */
$busca  = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

/* ===== LOGS DE ATIVIDADES ===== */
$sql = "
    SELECT la.*, u.nome_completo AS usuario_nome, u.email AS usuario_email
    FROM logs_atividades la
    LEFT JOIN usuarios u ON u.id = la.usuario_id
    WHERE 1=1
";
$params = [];
if ($busca !== '') {
    $sql .= " AND (la.acao LIKE :q OR la.descricao LIKE :q OR u.nome_completo LIKE :q)";
    $params[':q'] = "%$busca%";
}
$sql .= " ORDER BY la.id DESC LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$atividades = $stmt->fetchAll();

/* ===== HISTÓRICO DE LOGINS ===== */
$sqlLogin = "
    SELECT hl.*, u.nome_completo AS usuario_nome, u.email AS usuario_email
    FROM historico_login hl
    LEFT JOIN usuarios u ON u.id = hl.usuario_id
    WHERE 1=1
";
$paramsLogin = [];
if ($busca !== '') {
    $sqlLogin .= " AND (u.nome_completo LIKE :q OR u.email LIKE :q OR hl.ip_address LIKE :q)";
    $paramsLogin[':q'] = "%$busca%";
}
if (in_array($status, ['sucesso','falha'])) {
    $sqlLogin .= " AND hl.status = :s";
    $paramsLogin[':s'] = $status;
}
$sqlLogin .= " ORDER BY hl.id DESC LIMIT 200";

$stmtLogin = $pdo->prepare($sqlLogin);
$stmtLogin->execute($paramsLogin);
$logins = $stmtLogin->fetchAll();

/* Totais */
$totalAtividades = (int)$pdo->query("SELECT COUNT(*) FROM logs_atividades")->fetchColumn();
$totalLogins     = (int)$pdo->query("SELECT COUNT(*) FROM historico_login")->fetchColumn();
$totalSucessos   = (int)$pdo->query("SELECT COUNT(*) FROM historico_login WHERE status='sucesso'")->fetchColumn();
$totalFalhas     = (int)$pdo->query("SELECT COUNT(*) FROM historico_login WHERE status='falha'")->fetchColumn();

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>flwrs · logs e histórico</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
<style>
:root {
    --coral:#e8857d; --pink:#f4a8a0; --light-pink:#f5d5d0; --soft:#fdf6f5;
    --dark:#2d2825; --gray:#6d6560; --muted:#a8958f; --border:#f0ece8;
    --shadow:0 10px 30px -10px rgba(0,0,0,.05); --radius:24px;
}
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
    background:var(--soft); color:var(--dark); padding:2rem 1rem; min-height:100vh;
}
.container { max-width:1200px; margin:0 auto; }

.notificacao {
    position:fixed; top:80px; right:20px; padding:1rem 1.5rem;
    border-radius:10px; color:#fff; font-weight:600; z-index:2000;
    box-shadow:var(--shadow); animation:slideIn .3s ease-out; max-width:400px;
}
.notificacao.sucesso { background:linear-gradient(135deg,#5FA86D,#4A8A58); }
.notificacao.erro    { background:linear-gradient(135deg,#D64545,#B83535); }
@keyframes slideIn { from{transform:translateX(120%);opacity:0} to{transform:translateX(0);opacity:1} }

.header {
    display:flex; justify-content:space-between; align-items:center;
    background:#fff; padding:1.5rem 2rem; border-radius:32px;
    margin-bottom:2rem; box-shadow:var(--shadow); gap:1rem; flex-wrap:wrap;
}
.header h1 { font-weight:400; font-size:1.5rem; }
.header-actions { display:flex; gap:.5rem; flex-wrap:wrap; }

.btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.6rem 1.3rem; border-radius:50px; border:none;
    cursor:pointer; font-size:.85rem; font-weight:600;
    font-family:inherit; text-decoration:none; line-height:1;
    transition:transform .2s, box-shadow .2s;
}
.btn:hover { transform:translateY(-2px); box-shadow:var(--shadow); }
.btn-primary { background:linear-gradient(135deg,var(--coral),var(--pink)); color:#fff; }
.btn-outline { background:#fff; color:var(--dark); border:2px solid var(--light-pink); }
.btn-danger  { background:linear-gradient(135deg,#D64545,#B83535); color:#fff; }
.btn-sm      { padding:.4rem .9rem; font-size:.78rem; }

.resumo {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:1rem; margin-bottom:2rem;
}
.resumo-card {
    background:#fff; padding:1.25rem; border-radius:20px;
    box-shadow:var(--shadow); text-align:center;
    border-left:4px solid var(--coral);
}
.resumo-card .num { font-size:1.8rem; font-weight:300; color:var(--coral); line-height:1; }
.resumo-card .lbl { font-size:.72rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-top:.3rem; }

/* ABAS */
.abas {
    display:flex; gap:.5rem; margin-bottom:1.5rem;
    background:#fff; padding:.5rem; border-radius:50px;
    box-shadow:var(--shadow);
    width:fit-content;
}
.aba {
    padding:.6rem 1.3rem; border-radius:50px;
    color:var(--gray); text-decoration:none;
    font-size:.85rem; font-weight:600;
    transition:all .2s;
}
.aba:hover { color:var(--coral); }
.aba.ativa {
    background:linear-gradient(135deg,var(--coral),var(--pink));
    color:#fff;
}

/* FILTROS */
.filters {
    background:#fff; padding:1.25rem; border-radius:20px;
    box-shadow:var(--shadow); margin-bottom:1.5rem;
    display:flex; gap:.5rem; flex-wrap:wrap; align-items:center;
}
.filters input[type=text] {
    flex:1; min-width:200px; padding:.7rem 1rem;
    border:2px solid var(--light-pink); border-radius:50px;
    font-family:inherit; font-size:.9rem; outline:none;
}
.filters input:focus { border-color:var(--coral); }
.filters select {
    padding:.7rem 1rem; border:2px solid var(--light-pink);
    border-radius:50px; font-family:inherit; font-size:.9rem;
    cursor:pointer;
}

/* TABELA */
.table-card {
    background:#fff; border-radius:var(--radius);
    box-shadow:var(--shadow); overflow:hidden;
}
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; }
th {
    text-align:left; color:var(--gray); font-weight:500;
    font-size:.72rem; text-transform:uppercase; letter-spacing:.05em;
    padding:1rem; border-bottom:1px solid var(--border); background:var(--soft);
}
td {
    padding:1rem; border-bottom:1px solid var(--border);
    font-size:.88rem; vertical-align:middle;
}
tbody tr:hover { background:var(--soft); }
tbody tr:last-child td { border-bottom:none; }
.acao-tag {
    display:inline-block; padding:.2rem .6rem;
    background:var(--soft); color:var(--gray);
    border-radius:6px; font-size:.75rem;
    font-family:monospace;
}
.ip-tag {
    font-family:monospace; font-size:.8rem;
    color:var(--gray);
}

.badge {
    display:inline-block; padding:.25rem .7rem;
    border-radius:50px; font-size:.72rem; font-weight:600;
}
.badge-sucesso { background:#d4edda; color:#2c6b3a; }
.badge-falha   { background:#f8d7da; color:#a12b33; }

.user-info {
    display:flex; align-items:center; gap:.6rem;
}
.avatar-mini {
    width:32px; height:32px; border-radius:50%;
    background:linear-gradient(135deg,var(--coral),var(--pink));
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-weight:600; font-size:.8rem;
}
.avatar-mini.desconhecido {
    background:#eee; color:var(--muted);
}

.empty { text-align:center; padding:3rem; color:var(--muted); }
.empty .icon { font-size:3rem; display:block; margin-bottom:.5rem; }

@media (max-width:768px) {
    .header h1 { font-size:1.2rem; }
    th, td { padding:.6rem; font-size:.82rem; }
    .notificacao { left:10px; right:10px; max-width:none; }
    .abas { width:100%; }
    .aba { flex:1; text-align:center; }
}
</style>
</head>
<body>

<?php if ($flash): ?>
    <div class="notificacao <?= e($flash['tipo']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<div class="container">

    <div class="header">
        <h1>📜 Logs e Histórico</h1>
        <div class="header-actions">
            <a href="admin.php" class="btn btn-outline">← Painel</a>
            <a href="?acao=limpar_antigos" class="btn btn-outline"
               onclick="return confirm('Remover logs com mais de 30 dias?')">🧹 Limpar +30 dias</a>
            <a href="?acao=limpar_tudo" class="btn btn-danger"
               onclick="return confirm('Remover TODOS os logs? Esta ação não pode ser desfeita.')">🗑️ Limpar tudo</a>
        </div>
    </div>

    <!-- RESUMO -->
    <div class="resumo">
        <div class="resumo-card">
            <div class="num"><?= $totalAtividades ?></div>
            <div class="lbl">Atividades</div>
        </div>
        <div class="resumo-card" style="border-left-color:#5FA86D;">
            <div class="num" style="color:#5FA86D;"><?= $totalSucessos ?></div>
            <div class="lbl">Logins OK</div>
        </div>
        <div class="resumo-card" style="border-left-color:#D64545;">
            <div class="num" style="color:#D64545;"><?= $totalFalhas ?></div>
            <div class="lbl">Logins falhos</div>
        </div>
        <div class="resumo-card">
            <div class="num"><?= $totalLogins ?></div>
            <div class="lbl">Total de logins</div>
        </div>
    </div>

    <!-- ABAS -->
    <div class="abas">
        <a href="?aba=atividades" class="aba <?= $aba === 'atividades' ? 'ativa' : '' ?>">
            📋 Atividades
        </a>
        <a href="?aba=logins" class="aba <?= $aba === 'logins' ? 'ativa' : '' ?>">
            🔐 Histórico de logins
        </a>
    </div>

    <!-- FILTROS -->
    <form method="GET" class="filters">
        <input type="hidden" name="aba" value="<?= e($aba) ?>">
        <input type="text" name="q" placeholder="Buscar..." value="<?= e($busca) ?>">
        <?php if ($aba === 'logins'): ?>
            <select name="status">
                <option value="">Todos os status</option>
                <option value="sucesso" <?= $status === 'sucesso' ? 'selected' : '' ?>>Sucesso</option>
                <option value="falha"   <?= $status === 'falha'   ? 'selected' : '' ?>>Falha</option>
            </select>
        <?php endif; ?>
        <button class="btn btn-primary" type="submit">Filtrar</button>
        <?php if ($busca || $status): ?>
            <a href="?aba=<?= e($aba) ?>" class="btn btn-outline">Limpar</a>
        <?php endif; ?>
    </form>

    <!-- CONTEÚDO DA ABA -->
    <div class="table-card">

        <?php if ($aba === 'atividades'): ?>

            <?php if (empty($atividades)): ?>
                <div class="empty">
                    <span class="icon">📋</span>
                    <p>Nenhuma atividade registrada.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Descrição</th>
                                <th>IP</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($atividades as $a): ?>
                                <tr>
                                    <td>#<?= $a['id'] ?></td>
                                    <td>
                                        <div class="user-info">
                                            <?php if ($a['usuario_id']): ?>
                                                <div class="avatar-mini">
                                                    <?= strtoupper(mb_substr($a['usuario_nome'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <strong><?= e($a['usuario_nome']) ?></strong><br>
                                                    <small style="color:var(--muted);"><?= e($a['usuario_email']) ?></small>
                                                </div>
                                            <?php else: ?>
                                                <div class="avatar-mini desconhecido">?</div>
                                                <em style="color:var(--muted);">Sistema/Anônimo</em>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><span class="acao-tag"><?= e($a['acao']) ?></span></td>
                                    <td style="max-width:400px;">
                                        <?= e(mb_strimwidth($a['descricao'] ?? '—', 0, 120, '...')) ?>
                                    </td>
                                    <td><span class="ip-tag"><?= e($a['ip_address'] ?? '—') ?></span></td>
                                    <td><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php else: /* aba logins */ ?>

            <?php if (empty($logins)): ?>
                <div class="empty">
                    <span class="icon">🔐</span>
                    <p>Nenhum login registrado.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Status</th>
                                <th>IP</th>
                                <th>Motivo da falha</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logins as $l): ?>
                                <tr>
                                    <td>#<?= $l['id'] ?></td>
                                    <td>
                                        <div class="user-info">
                                            <?php if ($l['usuario_id'] && $l['usuario_nome']): ?>
                                                <div class="avatar-mini">
                                                    <?= strtoupper(mb_substr($l['usuario_nome'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <strong><?= e($l['usuario_nome']) ?></strong><br>
                                                    <small style="color:var(--muted);"><?= e($l['usuario_email']) ?></small>
                                                </div>
                                            <?php else: ?>
                                                <div class="avatar-mini desconhecido">?</div>
                                                <em style="color:var(--muted);">Desconhecido</em>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= e($l['status']) ?>">
                                            <?= e($l['status']) ?>
                                        </span>
                                    </td>
                                    <td><span class="ip-tag"><?= e($l['ip_address'] ?? '—') ?></span></td>
                                    <td><?= e($l['motivo_falha'] ?? '—') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($l['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>

<script>
    setTimeout(() => {
        const n = document.querySelector('.notificacao');
        if (n) { n.style.transition = 'opacity .4s'; n.style.opacity = '0'; setTimeout(() => n.remove(), 500); }
    }, 4000);
</script>

</body>
</html>