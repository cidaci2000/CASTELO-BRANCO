<?php
// api/listar_filmes.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once '../database.php';

session_start();
$usuarioId = $_SESSION['usuario_id'] ?? null;

try {
    $query = "SELECT f.*, 
              COALESCE(AVG(a.nota), 0) as media_nota,
              COUNT(a.id) as total_avaliacoes
              FROM filmes f
              LEFT JOIN avaliacoes a ON f.id = a.filme_id
              GROUP BY f.id
              ORDER BY f.id DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $filmes = $stmt->fetchAll();
    
    // Para cada filme, verificar status do usuário atual
    if ($usuarioId) {
        foreach ($filmes as &$filme) {
            $stmt = $pdo->prepare("SELECT status, nota FROM usuarios_filmes 
                                   WHERE usuario_id = ? AND filme_id = ?");
            $stmt->execute([$usuarioId, $filme['id']]);
            $status = $stmt->fetch();
            
            $filme['status_usuario'] = $status['status'] ?? null;
            $filme['nota_usuario'] = $status['nota'] ?? null;
        }
    }
    
    echo json_encode([
        'success' => true,
        'filmes' => $filmes
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao listar filmes: ' . $e->getMessage()]);
}
?>