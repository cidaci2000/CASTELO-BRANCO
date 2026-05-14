<?php
// api/verificar_sessao.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

if (isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => true,
        'usuario' => [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
}
?>