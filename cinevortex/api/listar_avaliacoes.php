<?php
// api/listar_avaliacoes.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../database.php';

session_start();

$filmeId = $_GET['filme_id'] ?? null;
$usuarioId = $_SESSION['usuario_id'] ?? null;

try {
    $query = "SELECT a.*, u.nome as usuario_nome, f.titulo as filme_titulo
              FROM avaliacoes a
              JOIN usuarios u ON a.usuario_id = u.id
              JOIN filmes f ON a.filme_id = f.id";
    
    $params = [];
    
    if ($filmeId) {
        $query .= " WHERE a.filme_id = ?";
        $params[] = $filmeId;
    } elseif ($usuarioId && !isset($_GET['todas'])) {
        $query .= " WHERE a.usuario_id = ?";
        $params[] = $usuarioId;
    }
    
    $query .= " ORDER BY a.data_avaliacao DESC LIMIT 20";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $avaliacoes = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'avaliacoes' => $avaliacoes
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao listar avaliações: ' . $e->getMessage()]);
}
?>