<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===== HELPERS ===== */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function setFlash($tipo, $msg) {
    $_SESSION['flash'] = ['tipo' => $tipo, 'msg' => $msg];
}

function getFlash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function isLogged() {
    return !empty($_SESSION['usuario_id']);
}

function isAdmin() {
    return !empty($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

function imagemUrl($imagem) {
    if (empty($imagem)) return 'https://picsum.photos/seed/flwrs/600/600';
    if (preg_match('#^https?://#', $imagem)) return $imagem;
    return $imagem; // já vem com "uploads/..."
}

/* ===== CARRINHO ===== */

/**
 * Retorna a "chave" do carrinho (usuário logado OU sessão)
 */
function getCartKey() {
    if (isLogged()) {
        return ['coluna' => 'usuario_id', 'valor' => $_SESSION['usuario_id']];
    }
    if (empty($_SESSION['sessao_id'])) {
        $_SESSION['sessao_id'] = bin2hex(random_bytes(16));
    }
    return ['coluna' => 'sessao_id', 'valor' => $_SESSION['sessao_id']];
}

/**
 * Lista os itens do carrinho do usuário/sessão atual
 */
function getCarrinho() {
    $key = getCartKey();
    $stmt = db()->prepare("
        SELECT c.id AS item_id, c.quantidade, c.produto_id,
               p.nome, p.descricao, p.preco, p.imagem, p.modelo
        FROM carrinho c
        INNER JOIN produtos p ON p.id = c.produto_id
        WHERE c.{$key['coluna']} = :v
          AND p.status = 'ativo'
        ORDER BY c.id DESC
    ");
    $stmt->execute([':v' => $key['valor']]);
    return $stmt->fetchAll();
}

/**
 * Conta total de itens (soma das quantidades)
 */
function countCarrinho() {
    $itens = getCarrinho();
    $c = 0;
    foreach ($itens as $i) $c += (int)$i['quantidade'];
    return $c;
}

/**
 * Calcula total do carrinho
 */
function totalCarrinho() {
    $itens = getCarrinho();
    $total = 0;
    foreach ($itens as $i) {
        $total += (float)$i['preco'] * (int)$i['quantidade'];
    }
    return $total;
}

/**
 * Adiciona produto ao carrinho (soma se já existir)
 */
function adicionarCarrinho($produtoId, $qtd = 1) {
    $key = getCartKey();
    $coluna = $key['coluna'];
    $valor  = $key['valor'];

    // Verifica se já existe
    $stmt = db()->prepare("SELECT id, quantidade FROM carrinho WHERE $coluna = :v AND produto_id = :p");
    $stmt->execute([':v' => $valor, ':p' => $produtoId]);
    $item = $stmt->fetch();

    if ($item) {
        $stmt = db()->prepare("UPDATE carrinho SET quantidade = quantidade + :q WHERE id = :id");
        $stmt->execute([':q' => $qtd, ':id' => $item['id']]);
    } else {
        $stmt = db()->prepare("INSERT INTO carrinho ($coluna, produto_id, quantidade) VALUES (:v, :p, :q)");
        $stmt->execute([':v' => $valor, ':p' => $produtoId, ':q' => $qtd]);
    }
    return true;
}

/**
 * Atualiza quantidade de um item
 */
function atualizarQuantidade($itemId, $qtd) {
    $key = getCartKey();
    $qtd = max(1, (int)$qtd);
    $stmt = db()->prepare("UPDATE carrinho SET quantidade = :q WHERE id = :id AND {$key['coluna']} = :v");
    $stmt->execute([':q' => $qtd, ':id' => $itemId, ':v' => $key['valor']]);
}

/**
 * Remove item do carrinho
 */
function removerItem($itemId) {
    $key = getCartKey();
    $stmt = db()->prepare("DELETE FROM carrinho WHERE id = :id AND {$key['coluna']} = :v");
    $stmt->execute([':id' => $itemId, ':v' => $key['valor']]);
}

/**
 * Limpa todo o carrinho atual
 */
function limparCarrinho() {
    $key = getCartKey();
    $stmt = db()->prepare("DELETE FROM carrinho WHERE {$key['coluna']} = :v");
    $stmt->execute([':v' => $key['valor']]);
}

/**
 * Finaliza a compra: cria pedido + itens, limpa carrinho
 * Retorna o ID do pedido criado ou false em caso de erro
 */
function finalizarCompra($dadosExtras = []) {
    $itens = getCarrinho();
    if (empty($itens)) return false;

    $pdo = db();
    $pdo->beginTransaction();

    try {
        $subtotal = totalCarrinho();
        $frete = $dadosExtras['frete'] ?? 0;
        $total = $subtotal + $frete;

        // 1. Cria pedido
        $stmt = $pdo->prepare("
            INSERT INTO pedidos (usuario_id, endereco_id, subtotal, frete, total, status, forma_pagamento, observacoes)
            VALUES (:u, :e, :s, :f, :t, 'pendente', :fp, :obs)
        ");
        $stmt->execute([
            ':u'  => $_SESSION['usuario_id'] ?? null,
            ':e'  => $dadosExtras['endereco_id'] ?? null,
            ':s'  => $subtotal,
            ':f'  => $frete,
            ':t'  => $total,
            ':fp' => $dadosExtras['forma_pagamento'] ?? null,
            ':obs'=> $dadosExtras['observacoes'] ?? null,
        ]);
        $pedidoId = (int)$pdo->lastInsertId();

        // 2. Insere itens
        $stmtItem = $pdo->prepare("
            INSERT INTO pedido_itens (pedido_id, produto_id, nome_produto, preco_unitario, quantidade, subtotal)
            VALUES (:ped, :prod, :nome, :preco, :qtd, :sub)
        ");
        foreach ($itens as $i) {
            $sub = (float)$i['preco'] * (int)$i['quantidade'];
            $stmtItem->execute([
                ':ped'   => $pedidoId,
                ':prod'  => $i['produto_id'],
                ':nome'  => $i['nome'],
                ':preco' => $i['preco'],
                ':qtd'   => $i['quantidade'],
                ':sub'   => $sub,
            ]);
        }

        // 3. Limpa carrinho
        $key = getCartKey();
        $stmt = $pdo->prepare("DELETE FROM carrinho WHERE {$key['coluna']} = :v");
        $stmt->execute([':v' => $key['valor']]);

        $pdo->commit();
        return $pedidoId;

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erro ao finalizar compra: " . $e->getMessage());
        return false;
    }
}

/**
 * Busca um pedido com itens
 */
function getPedido($pedidoId) {
    $stmt = db()->prepare("SELECT * FROM pedidos WHERE id = :id");
    $stmt->execute([':id' => $pedidoId]);
    $pedido = $stmt->fetch();
    if (!$pedido) return null;

    $stmt = db()->prepare("SELECT * FROM pedido_itens WHERE pedido_id = :id");
    $stmt->execute([':id' => $pedidoId]);
    $pedido['itens'] = $stmt->fetchAll();
    return $pedido;
}
/* ===== PRODUTOS ===== */

/**
 * Mapeamento dos modelos (enum) para nomes amigáveis
 */
function modelosMap() {
    return [
        'ba' => 'Buquê Afetivo',
        'a'  => 'Arranjo',
        'pe' => 'Presente Especial',
        'be' => 'Buquê Especial',
        'pf' => 'Palavras em Flor',
    ];
}

/**
 * Retorna o nome amigável do modelo
 */
function modeloNome($modelo) {
    $map = modelosMap();
    return $map[$modelo] ?? $modelo;
}

/**
 * Lista produtos com filtros opcionais
 */
function getProdutosFiltrados($opcoes = []) {
    $busca    = trim($opcoes['busca'] ?? '');
    $modelo   = $opcoes['modelo'] ?? '';
    $limite   = (int)($opcoes['limite'] ?? 12);
    $offset   = (int)($opcoes['offset'] ?? 0);
    $ordem    = $opcoes['ordem'] ?? 'recentes';

    $sql = "SELECT * FROM produtos WHERE status = 'ativo'";
    $params = [];

    if ($busca !== '') {
        $sql .= " AND (nome LIKE :busca OR descricao LIKE :busca)";
        $params[':busca'] = "%$busca%";
    }

    if ($modelo !== '' && array_key_exists($modelo, modelosMap())) {
        $sql .= " AND modelo = :modelo";
        $params[':modelo'] = $modelo;
    }

    switch ($ordem) {
        case 'menor_preco':
            $sql .= " ORDER BY preco ASC";
            break;
        case 'maior_preco':
            $sql .= " ORDER BY preco DESC";
            break;
        case 'nome':
            $sql .= " ORDER BY nome ASC";
            break;
        default:
            $sql .= " ORDER BY created_at DESC";
    }

    $sql .= " LIMIT :limite OFFSET :offset";

    $stmt = db()->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

/**
 * Conta quantos produtos existem com os filtros aplicados
 */
function contarProdutos($opcoes = []) {
    $busca  = trim($opcoes['busca'] ?? '');
    $modelo = $opcoes['modelo'] ?? '';

    $sql = "SELECT COUNT(*) FROM produtos WHERE status = 'ativo'";
    $params = [];

    if ($busca !== '') {
        $sql .= " AND (nome LIKE :busca OR descricao LIKE :busca)";
        $params[':busca'] = "%$busca%";
    }
    if ($modelo !== '' && array_key_exists($modelo, modelosMap())) {
        $sql .= " AND modelo = :modelo";
        $params[':modelo'] = $modelo;
    }

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}