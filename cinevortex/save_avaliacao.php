<?php
// api/save_avaliacao.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Criar tabela de avaliações se não existir
$db->exec("CREATE TABLE IF NOT EXISTS `avaliacoes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filme_id` int(11) NOT NULL,
    `usuario_id` int(11) DEFAULT NULL,
    `nota` int(11) NOT NULL,
    `comentario` text,
    `data_avaliacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `filme_id` (`filme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Receber dados
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!$data && $_POST) {
    $data = $_POST;
}

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados não recebidos'
    ]);
    exit;
}

// Validar dados
if (empty($data['filmeId']) || empty($data['nota'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Filme e nota são obrigatórios'
    ]);
    exit;
}

$nota = (int)$data['nota'];
if ($nota < 1 || $nota > 5) {
    echo json_encode([
        'success' => false,
        'message' => 'Nota deve ser entre 1 e 5'
    ]);
    exit;
}

try {
    $usuario_id = 1; // Temporário
    
    $query = "INSERT INTO avaliacoes (filme_id, usuario_id, nota, comentario) 
              VALUES (:filme_id, :usuario_id, :nota, :comentario)";
    
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':filme_id' => (int)$data['filmeId'],
        ':usuario_id' => $usuario_id,
        ':nota' => $nota,
        ':comentario' => $data['comentario'] ?? null
    ]);
    
    // Buscar o nome do filme
    $stmtFilme = $db->prepare("SELECT titulo FROM filmes WHERE id = ?");
    $stmtFilme->execute([(int)$data['filmeId']]);
    $filme = $stmtFilme->fetch();
    
    echo json_encode([
        'success' => true,
        'message' => 'Avaliação salva com sucesso!',
        'avaliacao' => [
            'id' => $db->lastInsertId(),
            'filmeId' => (int)$data['filmeId'],
            'filmeNome' => $filme['titulo'] ?? 'Filme',
            'nota' => $nota,
            'comentario' => $data['comentario'] ?? 'Sem comentário',
            'data' => date('d/m/Y')
        ]
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao salvar avaliação: ' . $e->getMessage()
    ]);
}
?>