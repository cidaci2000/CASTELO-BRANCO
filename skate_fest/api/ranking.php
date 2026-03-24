<?php
// api/ranking.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';
require_once '../includes/Skater.php';

$database = new Database();
$db = $database->getConnection();
$skater = new Skater($db);

// Obter tipo de ranking (completo ou podium)
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'completo';

if($tipo === 'podium') {
    $stmt = $skater->getPodium();
} else {
    $stmt = $skater->getRankingCompleto();
}

$num = $stmt->rowCount();

if($num > 0) {
    $ranking_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ranking_item = [
            "posicao" => $row['posicao_ranking'],
            "id" => $row['id'],
            "nome" => $row['nome'],
            "pais" => $row['pais'],
            "idade" => $row['idade'],
            "media_geral" => $row['media_geral'],
            "pontuacao_total" => $row['pontuacao_total'],
            "kickflip" => $row['kickflip'],
            "heelflip" => $row['heelflip'],
            "tre_flip" => $row['tre_flip'],
            "varial" => $row['varial'],
            "laser" => $row['laser']
        ];
        array_push($ranking_array, $ranking_item);
    }
    
    // Obter estatísticas
    $estatisticas = $skater->getEstatisticas();
    
    echo json_encode([
        "success" => true,
        "tipo" => $tipo,
        "data" => $ranking_array,
        "estatisticas" => $estatisticas,
        "total" => $num
    ]);
} else {
    echo json_encode([
        "success" => true,
        "tipo" => $tipo,
        "data" => [],
        "estatisticas" => null,
        "total" => 0,
        "message" => "Nenhum skatista cadastrado."
    ]);
}
?>