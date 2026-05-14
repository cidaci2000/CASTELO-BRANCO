<?php
// api/avaliar_filme.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../database.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['filme_id']) || !isset($data['nota'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$nota = (int)$data['nota'];
if ($nota < 1 || $nota > 5) {
    echo json_encode(['success' => false, 'message' => 'Nota deve ser entre 1 e 5']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$filmeId = $data['filme_id'];
$comentario = $data['comentario'] ?? null;
$status = $data['status'] ?? 'assistido';

try {
    // Verifica se já existe avaliação
    $stmt = $pdo->prepare("SELECT id FROM usuarios_filmes 
                           WHERE usuario_id = ? AND filme_id = ?");
    $stmt->execute([$usuarioId, $filmeId]);
    $existente = $stmt->fetch();
    
    if ($existente) {
        // Atualiza avaliação existente
        $stmt = $pdo->prepare("UPDATE usuarios_filmes 
                               SET nota = ?, comentario = ?, status = ?, data_atualizacao = NOW()
                               WHERE usuario_id = ? AND filme_id = ?");
        $stmt->execute([$nota, $comentario, $status, $usuarioId, $filmeId]);
        
        // Atualiza na tabela de avaliações
        $stmt = $pdo->prepare("UPDATE avaliacoes 
                               SET nota = ?, comentario = ?, data_avaliacao = NOW()
                               WHERE usuario_id = ? AND filme_id = ?");
        $stmt->execute([$nota, $comentario, $usuarioId, $filmeId]);
        
    } else {
        // Nova avaliação
        $stmt = $pdo->prepare("INSERT INTO usuarios_filmes 
                               (usuario_id, filme_id, nota, comentario, status) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$usuarioId, $filmeId, $nota, $comentario, $status]);
        
        $stmt = $pdo->prepare("INSERT INTO avaliacoes 
                               (usuario_id, filme_id, nota, comentario) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuarioId, $filmeId, $nota, $comentario]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Avaliação salva com sucesso!'
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar avaliação: ' . $e->getMessage()]);
}
?>