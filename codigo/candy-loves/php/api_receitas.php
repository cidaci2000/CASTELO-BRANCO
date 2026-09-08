<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Conectar ao banco de dados
require_once 'config/db_connection.php';

// Obter ação solicitada
$action = $_GET['action'] ?? 'listar';
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($action) {
        case 'listar':
            listarReceitas();
            break;
        
        case 'destaques':
            listarDestaques();
            break;
        
        case 'categoria':
            listarPorCategoria();
            break;
        
        case 'buscar':
            buscarReceitas();
            break;
        
        case 'detalhes':
            obterDetalhes();
            break;
        
        case 'criar':
            if ($method === 'POST') {
                criarReceita();
            }
            break;
        
        case 'atualizar':
            if ($method === 'PUT') {
                atualizarReceita();
            }
            break;
        
        case 'deletar':
            if ($method === 'DELETE') {
                deletarReceita();
            }
            break;
        
        default:
            http_response_code(400);
            echo json_encode(['erro' => 'Ação inválida']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}

// ===== FUNÇÕES =====

function listarReceitas() {
    global $pdo;
    
    $pagina = $_GET['pagina'] ?? 1;
    $por_pagina = $_GET['por_pagina'] ?? 6;
    $offset = ($pagina - 1) * $por_pagina;
    
    // Total de receitas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM receitas WHERE ativo = TRUE");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total / $por_pagina);
    
    // Buscar receitas
    $stmt = $pdo->prepare("\n        SELECT r.*, c.nome as categoria_nome, c.icone as categoria_icone\n        FROM receitas r\n        LEFT JOIN categorias c ON r.categoria_id = c.id\n        WHERE r.ativo = TRUE\n        ORDER BY r.created_at DESC\n        LIMIT ? OFFSET ?\n    ");
    $stmt->execute([$por_pagina, $offset]);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sucesso' => true,
        'receitas' => $receitas,
        'paginacao' => [
            'pagina_atual' => intval($pagina),
            'por_pagina' => intval($por_pagina),
            'total_receitas' => intval($total),
            'total_paginas' => intval($total_paginas)
        ]
    ]);
}

function listarDestaques() {
    global $pdo;
    
    $stmt = $pdo->prepare("\n        SELECT r.*, c.nome as categoria_nome, c.icone as categoria_icone\n        FROM receitas r\n        LEFT JOIN categorias c ON r.categoria_id = c.id\n        WHERE r.ativo = TRUE AND r.destaque = TRUE\n        ORDER BY r.created_at DESC\n        LIMIT 8\n    ");
    $stmt->execute();
    $destaques = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sucesso' => true,
        'destaques' => $destaques
    ]);
}

function listarPorCategoria() {
    global $pdo;
    
    $categoria_id = $_GET['categoria_id'] ?? null;
    
    if (!$categoria_id) {
        http_response_code(400);
        echo json_encode(['erro' => 'categoria_id é obrigatório']);
        return;
    }
    
    $pagina = $_GET['pagina'] ?? 1;
    $por_pagina = $_GET['por_pagina'] ?? 6;
    $offset = ($pagina - 1) * $por_pagina;
    
    // Total de receitas da categoria
    $stmt = $pdo->prepare("\n        SELECT COUNT(*) as total FROM receitas \n        WHERE ativo = TRUE AND categoria_id = ?\n    ");
    $stmt->execute([$categoria_id]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total / $por_pagina);
    
    // Buscar receitas da categoria
    $stmt = $pdo->prepare("\n        SELECT r.*, c.nome as categoria_nome, c.icone as categoria_icone\n        FROM receitas r\n        LEFT JOIN categorias c ON r.categoria_id = c.id\n        WHERE r.ativo = TRUE AND r.categoria_id = ?\n        ORDER BY r.created_at DESC\n        LIMIT ? OFFSET ?\n    ");
    $stmt->execute([$categoria_id, $por_pagina, $offset]);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sucesso' => true,
        'receitas' => $receitas,
        'paginacao' => [
            'pagina_atual' => intval($pagina),
            'por_pagina' => intval($por_pagina),
            'total_receitas' => intval($total),
            'total_paginas' => intval($total_paginas)
        ]
    ]);
}

function buscarReceitas() {
    global $pdo;
    
    $termo = $_GET['termo'] ?? '';
    
    if (strlen($termo) < 3) {
        echo json_encode(['receitas' => [], 'aviso' => 'Digite no mínimo 3 caracteres']);
        return;
    }
    
    $termo_busca = "%{$termo}%";
    
    $stmt = $pdo->prepare("\n        SELECT r.*, c.nome as categoria_nome, c.icone as categoria_icone\n        FROM receitas r\n        LEFT JOIN categorias c ON r.categoria_id = c.id\n        WHERE r.ativo = TRUE AND (\n            r.titulo LIKE ? OR \n            r.descricao LIKE ?\n        )\n        ORDER BY r.titulo ASC\n        LIMIT 20\n    ");
    $stmt->execute([$termo_busca, $termo_busca]);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sucesso' => true,
        'receitas' => $receitas,
        'total_resultados' => count($receitas)
    ]);
}

function obterDetalhes() {
    global $pdo;
    
    $receita_id = $_GET['receita_id'] ?? null;
    
    if (!$receita_id) {
        http_response_code(400);
        echo json_encode(['erro' => 'receita_id é obrigatório']);
        return;
    }
    
    // Receita
    $stmt = $pdo->prepare("\n        SELECT r.*, c.nome as categoria_nome, c.icone as categoria_icone\n        FROM receitas r\n        LEFT JOIN categorias c ON r.categoria_id = c.id\n        WHERE r.id = ? AND r.ativo = TRUE\n    ");
    $stmt->execute([$receita_id]);
    $receita = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$receita) {
        http_response_code(404);
        echo json_encode(['erro' => 'Receita não encontrada']);
        return;
    }
    
    // Ingredientes
    $stmt = $pdo->prepare("\n        SELECT * FROM ingredientes WHERE receita_id = ? ORDER BY id ASC\n    ");
    $stmt->execute([$receita_id]);
    $ingredientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Modo de preparo
    $stmt = $pdo->prepare("\n        SELECT * FROM modo_preparo WHERE receita_id = ? ORDER BY passo ASC\n    ");
    $stmt->execute([$receita_id]);
    $modo_preparo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'sucesso' => true,
        'receita' => $receita,
        'ingredientes' => $ingredientes,
        'modo_preparo' => $modo_preparo
    ]);
}

function criarReceita() {
    global $pdo;
    
    $dados = json_decode(file_get_contents('php://input'), true);
    
    try {
        $pdo->beginTransaction();
        
        // Inserir receita
        $stmt = $pdo->prepare("\n            INSERT INTO receitas (titulo, descricao, categoria_id, imagem_url, tempo_preparo, tempo_cozimento, dificuldade, rendimento, ativo, destaque)\n            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)\n        ");
        
        $stmt->execute([
            $dados['titulo'] ?? '',
            $dados['descricao'] ?? '',
            $dados['categoria_id'] ?? 1,
            $dados['imagem_url'] ?? null,
            $dados['tempo_preparo'] ?? 0,
            $dados['tempo_cozimento'] ?? 0,
            $dados['dificuldade'] ?? 'Média',
            $dados['rendimento'] ?? '',
            $dados['ativo'] ?? true,
            $dados['destaque'] ?? false
        ]);
        
        $receita_id = $pdo->lastInsertId();
        
        // Inserir ingredientes
        if (!empty($dados['ingredientes'])) {
            $stmt = $pdo->prepare("\n                INSERT INTO ingredientes (receita_id, nome, quantidade, unidade)\n                VALUES (?, ?, ?, ?)\n            ");
            
            foreach ($dados['ingredientes'] as $ingrediente) {
                $stmt->execute([
                    $receita_id,
                    $ingrediente['nome'] ?? '',
                    $ingrediente['quantidade'] ?? '',
                    $ingrediente['unidade'] ?? ''
                ]);
            }
        }
        
        // Inserir modo de preparo
        if (!empty($dados['modo_preparo'])) {
            $stmt = $pdo->prepare("\n                INSERT INTO modo_preparo (receita_id, passo, descricao)\n                VALUES (?, ?, ?)\n            ");
            
            $passo = 1;
            foreach ($dados['modo_preparo'] as $item) {
                $stmt->execute([
                    $receita_id,
                    $passo++,
                    $item['descricao'] ?? ''
                ]);
            }
        }
        
        $pdo->commit();
        
        http_response_code(201);
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Receita criada com sucesso',
            'receita_id' => $receita_id
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['erro' => $e->getMessage()]);
    }
}

function atualizarReceita() {
    global $pdo;
    
    $receita_id = $_GET['receita_id'] ?? null;
    
    if (!$receita_id) {
        http_response_code(400);
        echo json_encode(['erro' => 'receita_id é obrigatório']);
        return;
    }
    
    $dados = json_decode(file_get_contents('php://input'), true);
    
    try {
        $stmt = $pdo->prepare("\n            UPDATE receitas SET\n            titulo = ?, descricao = ?, categoria_id = ?, imagem_url = ?,\n            tempo_preparo = ?, tempo_cozimento = ?, dificuldade = ?, rendimento = ?, ativo = ?, destaque = ?\n            WHERE id = ?\n        ");
        
        $stmt->execute([
            $dados['titulo'] ?? '',
            $dados['descricao'] ?? '',
            $dados['categoria_id'] ?? 1,
            $dados['imagem_url'] ?? null,
            $dados['tempo_preparo'] ?? 0,
            $dados['tempo_cozimento'] ?? 0,
            $dados['dificuldade'] ?? 'Média',
            $dados['rendimento'] ?? '',
            $dados['ativo'] ?? true,
            $dados['destaque'] ?? false,
            $receita_id
        ]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Receita atualizada com sucesso'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['erro' => $e->getMessage()]);
    }
}

function deletarReceita() {
    global $pdo;
    
    $receita_id = $_GET['receita_id'] ?? null;
    
    if (!$receita_id) {
        http_response_code(400);
        echo json_encode(['erro' => 'receita_id é obrigatório']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM receitas WHERE id = ?");
        $stmt->execute([$receita_id]);
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Receita deletada com sucesso'
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['erro' => $e->getMessage()]);
    }
}
?>
