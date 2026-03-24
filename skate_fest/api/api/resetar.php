<?php
// api/resetar.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once '../config/database.php';
require_once '../includes/Skater.php';

$database = new Database();
$db = $database->getConnection();
$skater = new Skater($db);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($skater->resetarCompeticao()) {
        echo json_encode([
            "success" => true,
            "message" => "Competição resetada com sucesso!"
        ]);
    } else {
        http_response_code(503);
        echo json_encode([
            "success" => false,
            "message" => "Erro ao resetar competição."
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método não permitido."
    ]);
}
?>