<?php
// api/atualizar_status.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../database.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['filme_id']) || empty($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$filmeId = $data['filme_id'];
$status = $data['status'];

$statusPermitidos = ['quero_ver', 'assistido', 'favorito'];
if (!in_array($status, $statusPermitidos)) {
    echo json_encode(['success' => false, 'message' => 'Status inválido']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM usuarios_filmes 
                           WHERE usuario_id = ? AND filme_id = ?");
    $stmt->execute([$usuarioId, $filmeId]);
    $existente = $stmt->fetch();
    
    if ($existente) {
        $stmt = $pdo->prepare("UPDATE usuarios_filmes 
                               SET status = ?, data_atualizacao = NOW()
                               WHERE usuario_id = ? AND filme_id = ?");
        $stmt->execute([$status, $usuarioId, $filmeId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios_filmes 
                               (usuario_id, filme_id, status) 
                               VALUES (?, ?, ?)");
        $stmt->execute([$usuarioId, $filmeId, $status]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Status atualizado com sucesso!'
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status: ' . $e->getMessage()]);
}
?>