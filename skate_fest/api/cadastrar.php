<?php
// api/cadastrar.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/Skater.php';

$database = new Database();
$db = $database->getConnection();
$skater = new Skater($db);

// Pegar dados do POST
$data = json_decode(file_get_contents("php://input"));

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se todos os campos estão presentes
    if(
        !empty($data->nome) &&
        !empty($data->pais) &&
        isset($data->idade) &&
        isset($data->kickflip) &&
        isset($data->heelflip) &&
        isset($data->tre_flip) &&
        isset($data->varial) &&
        isset($data->laser)
    ) {
        // Atribuir valores
        $skater->nome = $data->nome;
        $skater->pais = $data->pais;
        $skater->idade = $data->idade;
        $skater->kickflip = $data->kickflip;
        $skater->heelflip = $data->heelflip;
        $skater->tre_flip = $data->tre_flip;
        $skater->varial = $data->varial;
        $skater->laser = $data->laser;

        // Validar dados
        $erros = $skater->validarDados();

        if(empty($erros)) {
            // Tentar cadastrar
            if($skater->cadastrar()) {
                http_response_code(201);
                echo json_encode([
                    "success" => true,
                    "message" => "Skatista cadastrado com sucesso!"
                ]);
            } else {
                http_response_code(503);
                echo json_encode([
                    "success" => false,
                    "message" => "Erro ao cadastrar skatista."
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Erro de validação",
                "errors" => $erros
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Dados incompletos."
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