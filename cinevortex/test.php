<?php
// api/test.php - Arquivo para testar se a API está funcionando
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'success' => true,
    'message' => 'API está funcionando!',
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido',
    'php_version' => PHP_VERSION
]);
?>
