<?php
// api/adicionar_filme.php (VERSÃO CORRIGIDA)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Responder requisições OPTIONS (preflight do CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Se for GET, retorna instruções (apenas para debug)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => false,
        'message' => 'Esta API aceita apenas requisições POST. Use POST com dados JSON.',
        'exemplo' => [
            'method' => 'POST',
            'headers' => ['Content-Type: application/json'],
            'body' => [
                'titulo' => 'Nome do Filme',
                'ano' => 2024,
                'descricao' => 'Descrição do filme',
                'genero' => 'Ação',
                'url_imagem' => 'https://exemplo.com/imagem.jpg'
            ]
        ]
    ]);
    exit();
}

require_once '../database.php';

// Log para debug
error_log("=== Nova requisição para add_filme.php ===");
error_log("Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Content-Type: " . ($_SERVER['CONTENT_TYPE'] ?? 'não definido'));

// Receber dados do POST
$rawInput = file_get_contents('php://input');
error_log("Raw input: " . $rawInput);

// Tenta decodificar JSON
$data = json_decode($rawInput, true);

// Se não conseguiu decodificar JSON, tenta pegar do POST normal
if (!$data && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    error_log("Usando dados do POST: " . print_r($data, true));
}

// Se ainda não tem dados, tenta pegar do php://input como string
if (!$data && $rawInput) {
    parse_str($rawInput, $data);
    error_log("Parse str result: " . print_r($data, true));
}

if (!$data || empty($data)) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados não recebidos. Verifique o formato da requisição.',
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'não definido',
            'raw_input' => substr($rawInput, 0, 200)
        ]
    ]);
    exit;
}

// Validar dados obrigatórios
if (empty($data['titulo']) || empty($data['ano'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Título e ano são obrigatórios',
        'received' => [
            'titulo' => $data['titulo'] ?? 'não enviado',
            'ano' => $data['ano'] ?? 'não enviado'
        ]
    ]);
    exit;
}

// Validar URL da imagem
$imagem = $data['poster'] ?? $data['url_imagem'] ?? '';
if ($imagem && !filter_var($imagem, FILTER_VALIDATE_URL) && strpos($imagem, 'data:image') !== 0) {
    $imagem = 'https://via.placeholder.com/300x450?text=Sem+Poster';
}

if (empty($imagem)) {
    $imagem = 'https://via.placeholder.com/300x450?text=Sem+Poster';
}

// Converter gênero de array para string
$genero = '';
if (isset($data['genero'])) {
    if (is_array($data['genero'])) {
        $genero = implode(',', $data['genero']);
    } else {
        $genero = $data['genero'];
    }
}

// Iniciar sessão para pegar o usuário logado
session_start();
$usuario_id = $_SESSION['usuario_id'] ?? 1; // Fallback para ID 1 se não estiver logado

try {
    $query = "INSERT INTO filmes (titulo, ano, descricao, genero, url_imagem, id_usuario_criador) 
              VALUES (:titulo, :ano, :descricao, :genero, :url_imagem, :id_usuario_criador)";
    
    $stmt = $pdo->prepare($query);
    
    $result = $stmt->execute([
        ':titulo' => $data['titulo'],
        ':ano' => (int)$data['ano'],
        ':descricao' => $data['descricao'] ?? null,
        ':genero' => $genero ?: null,
        ':url_imagem' => $imagem,
        ':id_usuario_criador' => $usuario_id
    ]);
    
    if ($result) {
        $id = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Filme adicionado com sucesso!',
            'filme' => [
                'id' => (int)$id,
                'titulo' => $data['titulo'],
                'ano' => (int)$data['ano'],
                'descricao' => $data['descricao'] ?? 'Sem descrição disponível',
                'genero' => $genero ? explode(',', $genero) : ['Geral'],
                'poster' => $imagem,
                'url_imagem' => $imagem,
                'nota' => 0
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao inserir no banco de dados'
        ]);
    }
    
} catch(PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erro no banco de dados: ' . $e->getMessage()
    ]);
}
?>