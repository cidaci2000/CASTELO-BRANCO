<?php
// api/listar.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';
require_once '../includes/Skater.php';

$database = new Database();
$db = $database->getConnection();
$skater = new Skater($db);

$stmt = $skater->listarTodos();
$num = $stmt->rowCount();

if($num > 0) {
    $skaters_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $skater_item = [
            "id" => $row['id'],
            "nome" => $row['nome'],
            "pais" => $row['pais'],
            "idade" => $row['idade'],
            "kickflip" => $row['kickflip'],
            "heelflip" => $row['heelflip'],
            "tre_flip" => $row['tre_flip'],
            "varial" => $row['varial'],
            "laser" => $row['laser'],
            "media_geral" => $row['media_geral'],
            "pontuacao_total" => $row['pontuacao_total'],
            "data_cadastro" => $row['data_cadastro']
        ];
        array_push($skaters_array, $skater_item);
    }
    
    echo json_encode([
        "success" => true,
        "data" => $skaters_array,
        "total" => $num
    ]);
} else {
    echo json_encode([
        "success" => true,
        "data" => [],
        "total" => 0,
        "message" => "Nenhum skatista cadastrado."
    ]);
}
?>