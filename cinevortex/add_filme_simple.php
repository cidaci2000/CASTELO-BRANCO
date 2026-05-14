<?php
// api/add_filme_simple.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Pegar os dados enviados
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Se não veio JSON, tenta POST normal
if (!$data && $_POST) {
    $data = $_POST;
}

echo json_encode([
    'success' => true,
    'message' => 'Dados recebidos com sucesso!',
    'received_data' => $data,
    'raw_input' => $input,
    'post_data' => $_POST,
    'method' => $_SERVER['REQUEST_METHOD']
]);
?>