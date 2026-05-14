<?php
// api/estatisticas.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../database.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

try {
    // Total de filmes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM filmes");
    $total = $stmt->fetch();
    
    // Assistidos
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios_filmes 
                           WHERE usuario_id = ? AND status IN ('assistido', 'favorito')");
    $stmt->execute([$usuarioId]);
    $assistidos = $stmt->fetch();
    
    // Favoritos
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios_filmes 
                           WHERE usuario_id = ? AND status = 'favorito'");
    $stmt->execute([$usuarioId]);
    $favoritos = $stmt->fetch();
    
    // Quero ver
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios_filmes 
                           WHERE usuario_id = ? AND status = 'quero_ver'");
    $stmt->execute([$usuarioId]);
    $queroVer = $stmt->fetch();
    
    // Média das notas do usuário
    $stmt = $pdo->prepare("SELECT AVG(nota) as media FROM usuarios_filmes 
                           WHERE usuario_id = ? AND nota IS NOT NULL");
    $stmt->execute([$usuarioId]);
    $media = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'estatisticas' => [
            'total_filmes' => (int)$total['total'],
            'assistidos' => (int)$assistidos['total'],
            'favoritos' => (int)$favoritos['total'],
            'quero_ver' => (int)$queroVer['total'],
            'media_notas' => round($media['media'] ?? 0, 1)
        ]
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar estatísticas: ' . $e->getMessage()]);
}
?>