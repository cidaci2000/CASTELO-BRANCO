<?php
// ============================================
// admin_pedidos.php — Gerenciar pedidos flwrs
// ============================================
require_once __DIR__ . '/../includes/functions.php';

if (!isLogged() || !isAdmin()) {
    setFlash('erro', 'Acesso restrito a administradores.');
    redirect('login.php');
}

$pdo = db();
$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

/* Atualizar status do pedido */
if ($acao === 'atualizar_status') {
    $id     = (int)($_POST['pedido_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $validos = ['pendente','pago','enviado','entregue','cancelado'];

    if ($id && in_array($status, $validos)) {
        $pdo->prepare("UPDATE pedidos SET status = :s WHERE id = :id")
            ->execute([':s' => $status, ':id' => $id]);

        // Log
        try {
            $pdo->prepare("
                INSERT INTO logs_atividades (usuario_id, acao, descricao, ip_address)
                VALUES (:u, 'pedido_status', :d, :ip)
            ")->execute([
                ':u' => $_SESSION['usuario_id'],
                ':d' => "Pedido #$id → $status",
                ':ip'=> $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (Exception $e) { /* silencioso */ }

        setFlash('sucesso', "Pedido #$id atualizado para '$status'.");
    }
    redirect('admin_pedidos.php' . (!empty($_POST['voltar_ver']) ? '?ver=' . (int)$_POST['voltar_ver'] : ''));
}

/* Excluir pedido */
if ($acao === 'excluir') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        $pdo->prepare("DELETE FROM pedidos WHERE id = :id")->execute([':id' => $id]);
        setFlash('sucesso', 'Pedido excluído.');
    }
    redirect('admin_pedidos.php');
}

/* Ver detalhes */
$verId = (int)($_GET['ver'] ?? 0);
$verPedido = null;
$verItens  = [];
if ($verId) {
    $stmt = $pdo->prepare("
        SELECT p.*, u.nome_completo AS cliente, u.email AS cliente_email, u.telefone AS cliente_tel
        FROM pedidos p
        LEFT JOIN usuarios u ON u.id = p.usuario_id
        WHERE p.id = :id
    ");
    $stmt->execute([':id' => $verId]);
    $verPedido = $stmt->fetch();

    if ($verPedido) {
        $stmt = $pdo->prepare("
            SELECT pi.*, pr.imagem, pr.modelo
            FROM pedido_itens pi
            LEFT JOIN produtos pr ON pr.id = pi.produto_id
            WHERE pi.pedido_id = :id
        ");
        $stmt->execute([':id' => $verId]);
        $verItens = $stmt->fetchAll();
    }
}

/* Listagem com filtros */
$filtroStatus = $_GET['status'] ?? '';
$filtroBusca  = trim($_GET['q'] ?? '');
$statusValidos = ['pendente','pago','enviado','entregue','cancelado'];

$sql = "
    SELECT p.*, u.nome_completo AS cliente,
           (SELECT COUNT(*) FROM pedido_itens pi WHERE pi.pedido_id = p.id) AS qtd_itens
    FROM pedidos p
    LEFT JOIN usuarios u ON u.id = p.usuario_id
    WHERE 1=1
";
$params = [];

if (in_array($filtroStatus, $statusValidos)) {
    $sql .= " AND p.status = :s";
    $params[':s'] = $filtroStatus;
}
if ($filtroBusca !== '') {
    $sql .= " AND (u.nome_completo LIKE :q OR p.id = :id_exato)";
    $params[':q'] = "%$filtroBusca%";
    $params[':id_exato'] = (int)$filtroBusca;
}
$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pedidos = $stmt->fetchAll();

/* Totais por status (para os "chips") */
$totaisStatus = ['pendente'=>0,'pago'=>0,'enviado'=>0,'entregue'=>0,'cancelado'=>0];
foreach ($pdo->query("SELECT status, COUNT(*) AS n FROM pedidos GROUP BY status") as $row) {
    if (isset($totaisStatus[$row['status']])) {
        $totaisStatus[$row['status']] = (int)$row['n'];
    }
}
$totalGeral = array_sum($totaisStatus);
$receitaTotal = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE status != 'cancelado'")->fetchColumn();

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>flwrs · gerenciar pedidos</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
<style>
:root {
    --coral:#e8857d; --pink:#f4a8a0; --light-pink:#f5d5d0; --soft:#fdf6f5;
    --dark:#2d2825; --gray:#6d6560; --muted:#a8958f; --border:#f0ece8;
    --shadow: 0 10px 30px -10px rgba(0,0,0,.05); --radius:24px;
}
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
    background:var(--soft); color:var(--dark);
    padding:2rem 1rem; min-height:100vh;
}
.container { max-width:1200px; margin:0 auto; }

.notificacao {
    position:fixed; top:80px; right:20px;
    padding:1rem 1.5rem; border-radius:10px;
    color:#fff; font-weight:600; z-index:2000;
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
.btn-dark    { background:var(--dark); color:#fff; }
.btn-outline { background:#fff; color:var(--dark); border:2px solid var(--light-pink); }
.btn-danger  { background:linear-gradient(135deg,#D64545,#B83535); color:#fff; }
.btn-sm      { padding:.4rem .9rem; font-size:.78rem; }

/* CARDS DE RESUMO */
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
.chips {
    display:flex; gap:.4rem; flex-wrap:wrap;
    margin-bottom:1.5rem;
}
.chip {
    padding:.5rem 1rem; background:#fff;
    border:2px solid var(--light-pink); border-radius:50px;
    text-decoration:none; color:var(--dark); font-size:.82rem;
    transition:all .2s;
}
.chip:hover { border-color:var(--coral); color:var(--coral); }
.chip.ativo {
    background:linear-gradient(135deg,var(--coral),var(--pink));
    color:#fff; border-color:transparent;
}
.chip small { opacity:.7; margin-left:.3rem; }

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
    font-size:.9rem; vertical-align:middle;
}
tbody tr:hover { background:var(--soft); }
tbody tr:last-child td { border-bottom:none; }

.badge {
    display:inline-block; padding:.25rem .7rem;
    border-radius:50px; font-size:.72rem; font-weight:600;
    text-transform:capitalize;
}
.badge-pendente { background:#fff3cd; color:#856404; }
.badge-pago     { background:#d1ecf1; color:#0c5460; }
.badge-enviado  { background:#cce5ff; color:#004085; }
.badge-entregue { background:#d4edda; color:#2c6b3a; }
.badge-cancelado{ background:#f8d7da; color:#a12b33; }

/* DETALHE */
.detalhe-card {
    background:#fff; padding:2rem; border-radius:var(--radius);
    box-shadow:var(--shadow); margin-bottom:2rem;
}
.detalhe-header {
    display:flex; justify-content:space-between;
    align-items:center; gap:1rem; flex-wrap:wrap;
    margin-bottom:1.5rem; padding-bottom:1.5rem;
    border-bottom:1px solid var(--border);
}
.detalhe-header h2 { font-weight:400; font-size:1.3rem; }
.detalhe-info {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:1rem; margin-bottom:1.5rem;
}
.detalhe-info .item { font-size:.9rem; }
.detalhe-info .item strong { display:block; color:var(--muted); font-size:.75rem; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }

.itens-table { margin-top:1.5rem; }
.itens-table th { background:var(--soft); }
.itens-table .img-cell {
    width:50px; height:50px; object-fit:cover;
    border-radius:10px; border:2px solid var(--light-pink);
}
.totais {
    margin-top:1.5rem; padding-top:1.5rem;
    border-top:2px solid var(--light-pink);
    text-align:right;
}
.totais .linha { display:flex; justify-content:flex-end; gap:2rem; margin-bottom:.4rem; }
.totais .linha span:first-child { color:var(--muted); }
.totais .linha.total { font-size:1.3rem; font-weight:700; color:var(--coral); margin-top:.5rem; }

.status-form {
    margin-top:1.5rem; padding-top:1.5rem;
    border-top:1px solid var(--border);
    display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;
}
.status-form select {
    padding:.6rem 1rem; border:2px solid var(--light-pink);
    border-radius:12px; font-family:inherit; font-size:.9rem;
}

.empty { text-align:center; padding:3rem; color:var(--muted); }
.empty .icon { font-size:3rem; display:block; margin-bottom:.5rem; }

@media (max-width:768px) {
    .header h1 { font-size:1.2rem; }
    th, td { padding:.6rem; font-size:.82rem; }
    .notificacao { left:10px; right:10px; max-width:none; }
    .detalhe-header { flex-direction:column; align-items:flex-start; }
}
</style>
</head>
<body>

<?php if ($flash): ?>
    <div class="notificacao <?= e($flash['tipo']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<div class="container">

    <div class="header">
        <h1>🧾 Gerenciar Pedidos</h1>
        <div class="header-actions">
            <a href="admin.php" class="btn btn-outline">← Painel</a>
        </div>
    </div>

    <!-- RESUMO -->
    <div class="resumo">
        <div class="resumo-card">
            <div class="num"><?= $totalGeral ?></div>
            <div class="lbl">Total de pedidos</div>
        </div>
        <div class="resumo-card" style="border-left-color:#f0ad4e;">
            <div class="num" style="color:#f0ad4e;"><?= $totaisStatus['pendente'] ?></div>
            <div class="lbl">Pendentes</div>
        </div>
        <div class="resumo-card" style="border-left-color:#5FA86D;">
            <div class="num" style="color:#5FA86D;"><?= $totaisStatus['entregue'] ?></div>
            <div class="lbl">Entregues</div>
        </div>
        <div class="resumo-card" style="border-left-color:var(--coral);">
            <div class="num">R$ <?= number_format($receitaTotal, 0, ',', '.') ?></div>
            <div class="lbl">Receita total</div>
        </div>
    </div>

    <!-- DETALHE DE PEDIDO -->
    <?php if ($verPedido): ?>
        <div class="detalhe-card">
            <div class="detalhe-header">
                <h2>Pedido #<?= $verPedido['id'] ?></h2>
                <a href="admin_pedidos.php" class="btn btn-outline btn-sm">✖ Fechar</a>
            </div>

            <div class="detalhe-info">
                <div class="item">
                    <strong>Cliente</strong>
                    <?= e($verPedido['cliente'] ?? 'Visitante') ?>
                </div>
                <div class="item">
                    <strong>E-mail</strong>
                    <?= e($verPedido['cliente_email'] ?? '—') ?>
                </div>
                <div class="item">
                    <strong>Telefone</strong>
                    <?= e($verPedido['cliente_tel'] ?? '—') ?>
                </div>
                <div class="item">
                    <strong>Data</strong>
                    <?= date('d/m/Y H:i', strtotime($verPedido['created_at'])) ?>
                </div>
                <div class="item">
                    <strong>Status</strong>
                    <span class="badge badge-<?= e($verPedido['status']) ?>"><?= e($verPedido['status']) ?></span>
                </div>
                <div class="item">
                    <strong>Forma de pagamento</strong>
                    <?= e($verPedido['forma_pagamento'] ?? 'A definir') ?>
                </div>
                <?php if (!empty($verPedido['observacoes'])): ?>
                    <div class="item" style="grid-column:1/-1;">
                        <strong>Observações</strong>
                        <?= nl2br(e($verPedido['observacoes'])) ?>
                    </div>
                <?php endif; ?>
            </div>

            <table class="itens-table">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verItens as $i): ?>
                        <tr>
                            <td>
                                <?php if (!empty($i['imagem']) && file_exists(__DIR__ . '/' . $i['imagem'])): ?>
                                    <img src="<?= e($i['imagem']) ?>" class="img-cell" alt="">
                                <?php else: ?>
                                    🌸
                                <?php endif; ?>
                            </td>
                            <td><?= e($i['nome_produto']) ?></td>
                            <td><?= (int)$i['quantidade'] ?></td>
                            <td>R$ <?= number_format($i['preco_unitario'], 2, ',', '.') ?></td>
                            <td><strong>R$ <?= number_format($i['subtotal'], 2, ',', '.') ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="totais">
                <div class="linha"><span>Subtotal:</span><span>R$ <?= number_format($verPedido['subtotal'], 2, ',', '.') ?></span></div>
                <div class="linha"><span>Frete:</span><span>R$ <?= number_format($verPedido['frete'], 2, ',', '.') ?></span></div>
                <div class="linha total"><span>Total:</span><span>R$ <?= number_format($verPedido['total'], 2, ',', '.') ?></span></div>
            </div>

            <form method="POST" class="status-form">
                <input type="hidden" name="acao" value="atualizar_status">
                <input type="hidden" name="pedido_id" value="<?= $verPedido['id'] ?>">
                <input type="hidden" name="voltar_ver" value="1">
                <label style="font-weight:600;">Alterar status:</label>
                <select name="status">
                    <?php foreach (['pendente','pago','enviado','entregue','cancelado'] as $s): ?>
                        <option value="<?= $s ?>" <?= $verPedido['status'] === $s ? 'selected' : '' ?>>
                            <?= ucfirst($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-primary" type="submit">💾 Salvar</button>
                <a href="?acao=excluir&id=<?= $verPedido['id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Excluir pedido #<?= $verPedido['id'] ?>?')">🗑️ Excluir</a>
            </form>
        </div>
    <?php endif; ?>

    <!-- FILTROS -->
    <form method="GET" class="filters">
        <input type="text" name="q" placeholder="Buscar por cliente ou ID do pedido..." value="<?= e($filtroBusca) ?>">
        <button class="btn btn-primary" type="submit">Buscar</button>
        <?php if ($filtroBusca || $filtroStatus): ?>
            <a href="admin_pedidos.php" class="btn btn-outline">Limpar</a>
        <?php endif; ?>
    </form>

    <!-- CHIPS DE STATUS -->
    <div class="chips">
        <a href="admin_pedidos.php" class="chip <?= !$filtroStatus ? 'ativo' : '' ?>">
            Todos <small>(<?= $totalGeral ?>)</small>
        </a>
        <?php foreach (['pendente','pago','enviado','entregue','cancelado'] as $s): ?>
            <a href="?status=<?= $s ?>" class="chip <?= $filtroStatus === $s ? 'ativo' : '' ?>">
                <?= ucfirst($s) ?> <small>(<?= $totaisStatus[$s] ?>)</small>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- TABELA -->
    <div class="table-card">
        <?php if (empty($pedidos)): ?>
            <div class="empty">
                <span class="icon">📭</span>
                <p>Nenhum pedido encontrado.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Itens</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p): ?>
                            <tr>
                                <td><strong>#<?= $p['id'] ?></strong></td>
                                <td><?= e($p['cliente'] ?? 'Visitante') ?></td>
                                <td><?= (int)$p['qtd_itens'] ?> item(ns)</td>
                                <td><strong>R$ <?= number_format($p['total'], 2, ',', '.') ?></strong></td>
                                <td>
                                    <span class="badge badge-<?= e($p['status']) ?>"><?= e($p['status']) ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                                <td style="white-space:nowrap;">
                                    <a href="?ver=<?= $p['id'] ?>" class="btn btn-outline btn-sm">👁️ Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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