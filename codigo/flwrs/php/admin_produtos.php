<?php
// ============================================
// admin_produtos.php — CRUD de produtos flwrs
// ============================================
require_once __DIR__ . '/../includes/functions.php';

if (!isLogged() || !isAdmin()) {
    setFlash('erro', 'Acesso restrito a administradores.');
    redirect('login.php');
}

$pdo = db();

// ===== PROCESSAR AÇÕES =====
$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

/* Excluir produto */
if ($acao === 'excluir') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        // Busca imagem para apagar do disco
        $stmt = $pdo->prepare("SELECT imagem FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $img = $stmt->fetchColumn();

        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        if ($img && file_exists(__DIR__ . '/' . $img)) {
            @unlink(__DIR__ . '/' . $img);
        }
        setFlash('sucesso', 'Produto excluído com sucesso.');
    }
    redirect('admin_produtos.php');
}

/* Alternar status ativo/inativo */
if ($acao === 'toggle_status') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        $pdo->prepare("
            UPDATE produtos
            SET status = IF(status = 'ativo', 'inativo', 'ativo')
            WHERE id = :id
        ")->execute([':id' => $id]);
        setFlash('sucesso', 'Status do produto atualizado.');
    }
    redirect('admin_produtos.php');
}

/* Salvar (criar OU atualizar) */
if ($acao === 'salvar') {
    $id           = (int)($_POST['id'] ?? 0);
    $nome         = trim($_POST['nome'] ?? '');
    $descricao    = trim($_POST['descricao'] ?? '');
    $modelo       = $_POST['modelo'] ?? 'ba';
    $preco        = (float)str_replace(',', '.', $_POST['preco'] ?? '0');
    $status       = $_POST['status'] ?? 'ativo';
    $imagemAtual  = trim($_POST['imagem_atual'] ?? '');

    // Validação
    if ($nome === '' || $preco <= 0) {
        setFlash('erro', 'Preencha nome e preço corretamente.');
        redirect('admin_produtos.php');
    }
    if (!array_key_exists($modelo, modelosMap())) {
        $modelo = 'ba';
    }

    // Upload de imagem
    $imagem = $imagemAtual;
    if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','webp','gif'];

        if (in_array($ext, $permitidas)) {
            if (!is_dir(__DIR__ . '/uploads')) {
                mkdir(__DIR__ . '/uploads', 0755, true);
            }
            $nomeArquivo = 'uploads/' . uniqid('flwrs_') . '.' . $ext;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], __DIR__ . '/' . $nomeArquivo)) {
                // Apaga imagem antiga se era local
                if ($imagemAtual && strpos($imagemAtual, 'uploads/') === 0 && file_exists(__DIR__ . '/' . $imagemAtual)) {
                    @unlink(__DIR__ . '/' . $imagemAtual);
                }
                $imagem = $nomeArquivo;
            }
        } else {
            setFlash('erro', 'Formato inválido. Use JPG, PNG, WEBP ou GIF.');
            redirect('admin_produtos.php');
        }
    }

    if ($id > 0) {
        // Atualizar
        $stmt = $pdo->prepare("
            UPDATE produtos
            SET nome = :nome,
                descricao = :descricao,
                modelo = :modelo,
                preco = :preco,
                imagem = :imagem,
                imagem_tipo = :imagem_tipo,
                status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            ':nome'        => $nome,
            ':descricao'   => $descricao,
            ':modelo'      => $modelo,
            ':preco'       => $preco,
            ':imagem'      => $imagem,
            ':imagem_tipo' => pathinfo($imagem, PATHINFO_EXTENSION) ?: null,
            ':status'      => $status,
            ':id'          => $id,
        ]);
        setFlash('sucesso', 'Produto atualizado!');
    } else {
        // Criar
        $stmt = $pdo->prepare("
            INSERT INTO produtos (nome, descricao, modelo, preco, imagem, imagem_tipo, status)
            VALUES (:nome, :descricao, :modelo, :preco, :imagem, :imagem_tipo, :status)
        ");
        $stmt->execute([
            ':nome'        => $nome,
            ':descricao'   => $descricao,
            ':modelo'      => $modelo,
            ':preco'       => $preco,
            ':imagem'      => $imagem,
            ':imagem_tipo' => pathinfo($imagem, PATHINFO_EXTENSION) ?: null,
            ':status'      => $status,
        ]);
        setFlash('sucesso', 'Produto criado!');
    }
    redirect('admin_produtos.php');
}

// ===== LISTAGEM =====
$busca  = trim($_GET['q'] ?? '');
$filtro = $_GET['status'] ?? '';

$sql = "SELECT * FROM produtos WHERE 1=1";
$params = [];

if ($busca !== '') {
    $sql .= " AND (nome LIKE :busca OR descricao LIKE :busca)";
    $params[':busca'] = "%$busca%";
}
if (in_array($filtro, ['ativo', 'inativo'])) {
    $sql .= " AND status = :status";
    $params[':status'] = $filtro;
}
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();

// Produto em edição (se veio ?editar=ID)
$editar = null;
if (!empty($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['editar']]);
    $editar = $stmt->fetch();
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>flwrs · gerenciar produtos</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
<style>
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
    --radius: 24px;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: var(--soft); color: var(--dark);
    padding: 2rem 1rem; min-height: 100vh;
}
.container { max-width: 1200px; margin: 0 auto; }

.notificacao {
    position: fixed; top: 80px; right: 20px;
    padding: 1rem 1.5rem; border-radius: 10px;
    color: #fff; font-weight: 600; z-index: 2000;
    box-shadow: var(--shadow); animation: slideIn .3s ease-out;
    max-width: 400px;
}
.notificacao.sucesso { background: linear-gradient(135deg,#5FA86D,#4A8A58); }
.notificacao.erro    { background: linear-gradient(135deg,#D64545,#B83535); }
@keyframes slideIn { from{transform:translateX(120%);opacity:0} to{transform:translateX(0);opacity:1} }

.header {
    display: flex; justify-content: space-between; align-items: center;
    background: #fff; padding: 1.5rem 2rem; border-radius: 32px;
    margin-bottom: 2rem; box-shadow: var(--shadow);
    gap: 1rem; flex-wrap: wrap;
}
.header h1 { font-weight: 400; font-size: 1.5rem; }
.header-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

.btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .6rem 1.3rem; border-radius: 50px;
    border: none; cursor: pointer; font-size: .85rem; font-weight: 600;
    font-family: inherit; text-decoration: none; line-height: 1;
    transition: transform .2s, box-shadow .2s;
}
.btn:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.btn-primary { background: linear-gradient(135deg,var(--coral),var(--pink)); color: #fff; }
.btn-dark    { background: var(--dark); color: #fff; }
.btn-outline { background: #fff; color: var(--dark); border: 2px solid var(--light-pink); }
.btn-danger  { background: linear-gradient(135deg,#D64545,#B83535); color: #fff; }
.btn-sm      { padding: .4rem .9rem; font-size: .78rem; }

/* FORM CARD */
.form-card {
    background: #fff; padding: 2rem; border-radius: var(--radius);
    box-shadow: var(--shadow); margin-bottom: 2rem;
}
.form-card h2 { font-weight: 400; font-size: 1.15rem; margin-bottom: 1.5rem; }
.form-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
}
.form-col { display: flex; flex-direction: column; }
.form-col.full { grid-column: 1 / -1; }
label { font-size: .85rem; font-weight: 600; margin-bottom: .3rem; color: var(--gray); }
input[type=text], input[type=number], textarea, select {
    width: 100%; padding: .75rem 1rem;
    border: 2px solid var(--light-pink); border-radius: 12px;
    font-family: inherit; font-size: .95rem;
    outline: none; transition: border-color .2s;
    background: #fff; color: var(--dark);
}
input:focus, textarea:focus, select:focus { border-color: var(--coral); }
textarea { resize: vertical; min-height: 80px; }
input[type=file] {
    padding: .6rem; border: 2px dashed var(--light-pink);
    border-radius: 12px; background: var(--soft);
    cursor: pointer; font-size: .85rem;
}
.preview-img {
    width: 100px; height: 100px; object-fit: cover;
    border-radius: 12px; border: 2px solid var(--light-pink);
    margin-top: .5rem;
}
.form-actions {
    display: flex; gap: .5rem; margin-top: 1.5rem;
    justify-content: flex-end;
}

/* FILTROS */
.filters {
    display: flex; gap: .5rem; flex-wrap: wrap;
    margin-bottom: 1.5rem; align-items: center;
}
.filters form { display: flex; gap: .5rem; flex-wrap: wrap; flex: 1; }
.filters input[type=text] { max-width: 300px; }

/* TABELA */
.table-card {
    background: #fff; border-radius: var(--radius);
    box-shadow: var(--shadow); overflow: hidden;
}
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th {
    text-align: left; color: var(--gray); font-weight: 500;
    font-size: .72rem; text-transform: uppercase; letter-spacing: .05em;
    padding: 1rem; border-bottom: 1px solid var(--border);
    background: var(--soft);
}
td {
    padding: 1rem; border-bottom: 1px solid var(--border);
    font-size: .9rem; vertical-align: middle;
}
tbody tr:hover { background: var(--soft); }
tbody tr:last-child td { border-bottom: none; }

.produto-thumb {
    width: 50px; height: 50px; object-fit: cover;
    border-radius: 10px; border: 2px solid var(--light-pink);
    display: block;
}
.produto-thumb-placeholder {
    width: 50px; height: 50px; border-radius: 10px;
    background: linear-gradient(135deg,var(--light-pink),var(--pink));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
}
.badge {
    display: inline-block; padding: .25rem .7rem;
    border-radius: 50px; font-size: .72rem; font-weight: 600;
    text-transform: capitalize;
}
.badge-ativo    { background: #d4edda; color: #2c6b3a; }
.badge-inativo  { background: #f0ece8; color: var(--gray); }
.badge-modelo   { background: #fce4ec; color: #ad1457; }

.empty {
    text-align: center; padding: 3rem; color: var(--muted);
}
.empty .icon { font-size: 3rem; display: block; margin-bottom: .5rem; }

@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .header h1 { font-size: 1.2rem; }
    .notificacao { left: 10px; right: 10px; max-width: none; }
    th, td { padding: .6rem; font-size: .82rem; }
}
</style>
</head>
<body>

<?php if ($flash): ?>
    <div class="notificacao <?= e($flash['tipo']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<div class="container">

    <div class="header">
        <h1>🌸 Gerenciar Produtos</h1>
        <div class="header-actions">
            <a href="admin.php" class="btn btn-outline">← Painel</a>
            <a href="<?= $editar ? 'admin_produtos.php' : '#formProduto' ?>" class="btn btn-primary"
               onclick="<?= $editar ? '' : 'document.getElementById(\'formProduto\').scrollIntoView({behavior:\'smooth\'}); return false;' ?>">
                <?= $editar ? '+ Novo Produto' : '↓ Ir para o formulário' ?>
            </a>
        </div>
    </div>

    <!-- FORMULÁRIO CRIAR/EDITAR -->
    <div class="form-card" id="formProduto">
        <h2><?= $editar ? '✏️ Editar Produto #' . $editar['id'] : '➕ Novo Produto' ?></h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= $editar['id'] ?? 0 ?>">
            <input type="hidden" name="imagem_atual" value="<?= e($editar['imagem'] ?? '') ?>">

            <div class="form-grid">
                <div class="form-col">
                    <label>Nome *</label>
                    <input type="text" name="nome" required
                           value="<?= e($editar['nome'] ?? '') ?>"
                           placeholder="Ex: Buquê Campos de Verão">
                </div>

                <div class="form-col">
                    <label>Modelo *</label>
                    <select name="modelo" required>
                        <?php foreach (modelosMap() as $k => $v): ?>
                            <option value="<?= $k ?>" <?= ($editar['modelo'] ?? '') === $k ? 'selected' : '' ?>>
                                <?= e($v) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-col">
                    <label>Preço (R$) *</label>
                    <input type="number" step="0.01" min="0" name="preco" required
                           value="<?= e($editar['preco'] ?? '') ?>"
                           placeholder="89.90">
                </div>

                <div class="form-col">
                    <label>Status</label>
                    <select name="status">
                        <option value="ativo"   <?= ($editar['status'] ?? 'ativo') === 'ativo'   ? 'selected' : '' ?>>Ativo</option>
                        <option value="inativo" <?= ($editar['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="form-col full">
                    <label>Descrição</label>
                    <textarea name="descricao" placeholder="Descreva as flores, cores, ocasião..."><?= e($editar['descricao'] ?? '') ?></textarea>
                </div>

                <div class="form-col full">
                    <label>Imagem (JPG, PNG, WEBP, GIF)</label>
                    <input type="file" name="imagem" accept="image/*">
                    <?php if (!empty($editar['imagem']) && file_exists(__DIR__ . '/' . $editar['imagem'])): ?>
                        <img src="<?= e($editar['imagem']) ?>" class="preview-img" alt="Preview">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <?php if ($editar): ?>
                    <a href="admin_produtos.php" class="btn btn-outline">Cancelar</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">
                    <?= $editar ? '💾 Salvar alterações' : '➕ Criar produto' ?>
                </button>
            </div>
        </form>
    </div>

    <!-- FILTROS -->
    <div class="filters">
        <form method="GET">
            <input type="text" name="q" placeholder="Buscar por nome ou descrição..." value="<?= e($busca) ?>">
            <select name="status">
                <option value="">Todos os status</option>
                <option value="ativo"   <?= $filtro === 'ativo'   ? 'selected' : '' ?>>Ativos</option>
                <option value="inativo" <?= $filtro === 'inativo' ? 'selected' : '' ?>>Inativos</option>
            </select>
            <button class="btn btn-primary" type="submit">Filtrar</button>
            <?php if ($busca || $filtro): ?>
                <a href="admin_produtos.php" class="btn btn-outline">Limpar</a>
            <?php endif; ?>
        </form>
        <span style="color:var(--muted); font-size:.85rem;">
            <?= count($produtos) ?> produto<?= count($produtos) === 1 ? '' : 's' ?>
        </span>
    </div>

    <!-- TABELA -->
    <div class="table-card">
        <?php if (empty($produtos)): ?>
            <div class="empty">
                <span class="icon">🌸</span>
                <p>Nenhum produto encontrado.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Modelo</th>
                            <th>Preço</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $p): ?>
                            <tr>
                                <td>#<?= $p['id'] ?></td>
                                <td>
                                    <?php if (!empty($p['imagem']) && file_exists(__DIR__ . '/' . $p['imagem'])): ?>
                                        <img src="<?= e($p['imagem']) ?>" class="produto-thumb" alt="">
                                    <?php else: ?>
                                        <div class="produto-thumb-placeholder">🌸</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= e($p['nome']) ?></strong><br>
                                    <small style="color:var(--muted);">
                                        <?= e(mb_strimwidth($p['descricao'], 0, 60, '...')) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-modelo">
                                        <?= e(modeloNome($p['modelo'])) ?>
                                    </span>
                                </td>
                                <td><strong>R$ <?= number_format($p['preco'], 2, ',', '.') ?></strong></td>
                                <td>
                                    <span class="badge badge-<?= e($p['status']) ?>">
                                        <?= e($p['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                <td style="white-space:nowrap;">
                                    <a href="?editar=<?= $p['id'] ?>" class="btn btn-outline btn-sm">✏️</a>
                                    <a href="?acao=toggle_status&id=<?= $p['id'] ?>"
                                       class="btn btn-outline btn-sm"
                                       title="<?= $p['status'] === 'ativo' ? 'Desativar' : 'Ativar' ?>">
                                        <?= $p['status'] === 'ativo' ? '🚫' : '✅' ?>
                                    </a>
                                    <a href="?acao=excluir&id=<?= $p['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Excluir produto &quot;<?= e($p['nome']) ?>&quot;?')">
                                        🗑️
                                    </a>
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